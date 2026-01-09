<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Routing\Controller as BaseController;
use Carbon\Carbon;

class PaymentController extends BaseController
{
    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        // Check if middleware method exists before calling it
        if (method_exists($this, 'middleware')) {
            $this->middleware('auth');
        }
    }

    /**
     * Show payment page for a booking
     */
    public function showPaymentPage($bookingId)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        try {
            if (class_exists('\App\Models\Booking')) {
                $booking = \App\Models\Booking::where('user_id', Auth::id())
                    ->with(['hostel', 'hostelPackage'])
                    ->findOrFail($bookingId);
            } else {
                $booking = $this->getSampleBooking($bookingId);
            }

            return view('payment.show', compact('booking'));
        } catch (\Exception $e) {
            Log::error('Payment page error: ' . $e->getMessage());
            return redirect()->route('student.bookings')
                ->with('error', 'Booking not found or payment not required.');
        }
    }

    /**
     * Process payment (Enhanced implementation)
     */
    public function processPayment(Request $request)
    {
        // Add CORS headers for AJAX requests
        if ($request->ajax()) {
            header('Access-Control-Allow-Origin: *');
            header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
            header('Access-Control-Allow-Headers: Content-Type, Authorization, X-CSRF-TOKEN, X-Requested-With');
        }

        // Log the incoming request for debugging
        Log::info('Payment processing started', [
            'user_id' => Auth::id(),
            'request_data' => $request->except(['card_number', 'card_cvv']), // Don't log sensitive data
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent()
        ]);

        if (!Auth::check()) {
            Log::warning('Unauthenticated payment attempt', [
                'ip' => $request->ip(),
                'booking_id' => $request->booking_id
            ]);
            return response()->json(['error' => 'Unauthenticated'], 401);
        }

        // Enhanced validation with better error messages
        try {
            $validatedData = $request->validate([
                'booking_id' => 'required|integer',
                'payment_method' => 'required|in:visa,mastercard,amex,bank_transfer',
                'card_number' => 'required_if:payment_method,visa,mastercard,amex|string',
                'card_name' => 'required_if:payment_method,visa,mastercard,amex|string|max:255',
                'card_expiry_month' => 'required_if:payment_method,visa,mastercard,amex|string',
                'card_expiry_year' => 'required_if:payment_method,visa,mastercard,amex|string',
                'card_cvv' => 'required_if:payment_method,visa,mastercard,amex|string',
                'amount' => 'required|numeric|min:0',
                'cardholder_name' => 'nullable|string|max:255',
                'email' => 'nullable|email|max:255'
            ], [
                'booking_id.required' => 'Booking ID is required',
                'payment_method.required' => 'Payment method is required',
                'card_number.required_if' => 'Card number is required for card payments',
                'card_name.required_if' => 'Cardholder name is required',
                'card_expiry_month.required_if' => 'Card expiry month is required',
                'card_expiry_year.required_if' => 'Card expiry year is required',
                'card_cvv.required_if' => 'Card CVV is required',
                'amount.required' => 'Payment amount is required',
                'amount.numeric' => 'Payment amount must be a valid number',
                'amount.min' => 'Payment amount must be greater than 0'
            ]);

            Log::info('Payment validation successful', [
                'user_id' => Auth::id(),
                'booking_id' => $validatedData['booking_id']
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Payment validation failed', [
                'user_id' => Auth::id(),
                'errors' => $e->errors(),
                'booking_id' => $request->booking_id
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Please check your payment information and try again.',
                'errors' => $e->errors()
            ], 422);
        }

        try {
            // Verify booking exists and belongs to user
            $bookingExists = $this->verifyBooking($validatedData['booking_id'], Auth::id());
            if (!$bookingExists) {
                Log::warning('Invalid booking access attempt', [
                    'user_id' => Auth::id(),
                    'booking_id' => $validatedData['booking_id']
                ]);
                
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid booking or you do not have permission to pay for this booking.'
                ], 403);
            }

            // Additional card validation for card payments
            if (in_array($validatedData['payment_method'], ['visa', 'mastercard', 'amex'])) {
                $cardValidation = $this->validateCardDetails($validatedData);
                if (!$cardValidation['valid']) {
                    Log::warning('Card validation failed', [
                        'user_id' => Auth::id(),
                        'booking_id' => $validatedData['booking_id'],
                        'reason' => $cardValidation['message']
                    ]);
                    
                    return response()->json([
                        'success' => false,
                        'message' => $cardValidation['message']
                    ], 400);
                }
            }

            Log::info('Starting payment processing', [
                'user_id' => Auth::id(),
                'booking_id' => $validatedData['booking_id'],
                'amount' => $validatedData['amount'],
                'payment_method' => $validatedData['payment_method']
            ]);

            // Simulate payment processing delay
            sleep(2);

            // Process payment
            $paymentResult = $this->processDummyPayment($request);

            if ($paymentResult['success']) {
                // Create payment record
                $payment = $this->createPaymentRecord($request, $paymentResult);

                // Update booking status if real booking exists
                $this->updateBookingStatus($validatedData['booking_id']);

                Log::info('Payment processed successfully', [
                    'payment_id' => $payment['id'],
                    'booking_id' => $validatedData['booking_id'],
                    'amount' => $validatedData['amount'],
                    'transaction_id' => $paymentResult['transaction_id']
                ]);

                return response()->json([
                    'success' => true,
                    'message' => 'Payment processed successfully!',
                    'payment_id' => $payment['id'],
                    'transaction_id' => $paymentResult['transaction_id'],
                    'redirect_url' => route('payments.success', $payment['id'])
                ]);
            } else {
                Log::warning('Payment failed', [
                    'user_id' => Auth::id(),
                    'booking_id' => $validatedData['booking_id'],
                    'reason' => $paymentResult['message'],
                    'payment_method' => $validatedData['payment_method']
                ]);

                return response()->json([
                    'success' => false,
                    'message' => $paymentResult['message'] ?? 'Payment failed. Please try again.'
                ], 400);
            }
        } catch (\Exception $e) {
            Log::error('Payment processing error: ' . $e->getMessage(), [
                'booking_id' => $validatedData['booking_id'] ?? null,
                'user_id' => Auth::id(),
                'trace' => $e->getTraceAsString(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Payment processing failed. Please try again later.',
                'debug_info' => app()->environment('local') ? $e->getMessage() : null
            ], 500);
        }
    }

    /**
     * Validate card details
     */
    private function validateCardDetails($data)
    {
        $cardNumber = str_replace(' ', '', $data['card_number']);
        
        // Validate card number using Luhn algorithm
        if (!$this->validateCardNumber($cardNumber)) {
            return [
                'valid' => false,
                'message' => 'Invalid card number. Please check and try again.'
            ];
        }

        // Check expiry date
        $currentYear = (int) date('Y');
        $currentMonth = (int) date('m');
        $expiryYear = (int) $data['card_expiry_year'];
        $expiryMonth = (int) $data['card_expiry_month'];

        if ($expiryYear < $currentYear || ($expiryYear == $currentYear && $expiryMonth < $currentMonth)) {
            return [
                'valid' => false,
                'message' => 'Your card has expired. Please use a valid card.'
            ];
        }

        // Validate CVV length based on card type
        $cardType = $this->getCardType($cardNumber);
        $cvvLength = strlen($data['card_cvv']);
        
        if ($cardType === 'amex' && $cvvLength !== 4) {
            return [
                'valid' => false,
                'message' => 'American Express cards require a 4-digit CVV.'
            ];
        } elseif ($cardType !== 'amex' && $cvvLength !== 3) {
            return [
                'valid' => false,
                'message' => 'Please enter a valid 3-digit CVV.'
            ];
        }

        return ['valid' => true];
    }
    /**
     * Verify booking exists and belongs to user
     */
    private function verifyBooking($bookingId, $userId)
    {
        if (class_exists('\App\Models\Booking')) {
            try {
                $booking = \App\Models\Booking::where('id', $bookingId)
                    ->where('user_id', $userId)
                    ->first();
                
                if (!$booking) {
                    Log::warning('Booking not found or access denied', [
                        'booking_id' => $bookingId,
                        'user_id' => $userId
                    ]);
                    return false;
                }
                
                // Check if booking is in a payable state
                if (in_array($booking->payment_status, ['paid', 'refunded'])) {
                    Log::warning('Attempt to pay for already paid booking', [
                        'booking_id' => $bookingId,
                        'user_id' => $userId,
                        'current_status' => $booking->payment_status
                    ]);
                    return false;
                }
                
                return true;
            } catch (\Exception $e) {
                Log::error('Booking verification error: ' . $e->getMessage(), [
                    'booking_id' => $bookingId,
                    'user_id' => $userId
                ]);
                return false;
            }
        }
        
        // Allow dummy bookings when no model exists (for development)
        Log::info('Using dummy booking verification', [
            'booking_id' => $bookingId,
            'user_id' => $userId
        ]);
        return true;
    }

    /**
     * Update booking status after successful payment
     */
    private function updateBookingStatus($bookingId)
    {
        if (class_exists('\App\Models\Booking')) {
            try {
                $booking = \App\Models\Booking::findOrFail($bookingId);
                $booking->update([
                    'payment_status' => 'paid',
                    'booking_status' => 'confirmed',
                    'updated_at' => now()
                ]);
                
                Log::info('Booking status updated successfully', [
                    'booking_id' => $bookingId,
                    'payment_status' => 'paid',
                    'booking_status' => 'confirmed'
                ]);
                
                // Trigger any post-payment actions (emails, notifications, etc.)
                $this->triggerPostPaymentActions($booking);
                
            } catch (\Exception $e) {
                Log::error('Could not update booking status: ' . $e->getMessage(), [
                    'booking_id' => $bookingId,
                    'trace' => $e->getTraceAsString()
                ]);
            }
        } else {
            Log::info('Skipping booking status update (no model)', [
                'booking_id' => $bookingId
            ]);
        }
    }

    /**
     * Trigger post-payment actions
     */
    private function triggerPostPaymentActions($booking)
    {
        try {
            // Send confirmation email
            Log::info('Triggering post-payment actions', [
                'booking_id' => $booking->id,
                'user_id' => $booking->user_id
            ]);
            
            // Here you would typically:
            // - Send confirmation email
            // - Send SMS notification
            // - Create notification in database
            // - Update hostel availability
            // - Log the transaction for reporting
            
        } catch (\Exception $e) {
            Log::error('Post-payment actions failed: ' . $e->getMessage(), [
                'booking_id' => $booking->id ?? null
            ]);
        }
    }

    /**
     * Payment success page
     */
    public function paymentSuccess($paymentId)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        try {
            if (class_exists('\App\Models\Payment')) {
                $payment = \App\Models\Payment::where('user_id', Auth::id())
                    ->with(['booking.hostel'])
                    ->findOrFail($paymentId);
            } else {
                $payment = $this->getSamplePayment($paymentId);
            }

            return view('payment.success', compact('payment'));
        } catch (\Exception $e) {
            Log::error('Payment success page error: ' . $e->getMessage());
            return redirect()->route('student.bookings')
                ->with('error', 'Payment record not found.');
        }
    }

    /**
     * Payment failed page
     */
    public function paymentFailed($paymentId)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        return view('payment.failed', ['payment_id' => $paymentId]);
    }

    /**
     * Payment cancelled page
     */
    public function paymentCancelled($paymentId)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        return view('payment.cancelled', ['payment_id' => $paymentId]);
    }

    /**
     * Show payment details
     */
    public function paymentDetails($paymentId)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        try {
            if (class_exists('\App\Models\Payment')) {
                $payment = \App\Models\Payment::where('user_id', Auth::id())
                    ->with(['booking.hostel'])
                    ->findOrFail($paymentId);
            } else {
                $payment = $this->getSamplePayment($paymentId);
            }

            return view('payment.details', compact('payment'));
        } catch (\Exception $e) {
            Log::error('Payment details error: ' . $e->getMessage());
            return redirect()->route('student.bookings')
                ->with('error', 'Payment record not found.');
        }
    }

    /**
     * Get payment history
     */
    public function paymentHistory()
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        try {
            if (class_exists('\App\Models\Payment')) {
                $payments = \App\Models\Payment::where('user_id', Auth::id())
                    ->with(['booking.hostel'])
                    ->orderBy('created_at', 'desc')
                    ->paginate(10);
            } else {
                $payments = $this->getSamplePayments();
            }

            return view('payment.history', compact('payments'));
        } catch (\Exception $e) {
            Log::error('Payment history error: ' . $e->getMessage());
            return view('payment.history', ['payments' => collect()]);
        }
    }

    /**
     * Process dummy payment (for demonstration)
     */
    private function processDummyPayment($request)
    {
        // Handle bank transfers differently
        if ($request->payment_method === 'bank_transfer') {
            return [
                'success' => true,
                'transaction_id' => 'BANK_' . strtoupper(uniqid()),
                'gateway_response' => 'Bank transfer initiated successfully',
                'authorization_code' => 'BANK_' . rand(100000, 999999)
            ];
        }

        // For card payments, validate card details
        $cardNumber = str_replace(' ', '', $request->card_number);
        $lastFourDigits = substr($cardNumber, -4);

        // Validate card number using Luhn algorithm
        if (!$this->validateCardNumber($cardNumber)) {
            return [
                'success' => false,
                'message' => 'Invalid card number. Please check and try again.'
            ];
        }

        // Check expiry date
        $currentYear = (int) date('Y');
        $currentMonth = (int) date('m');
        $expiryYear = (int) $request->card_expiry_year;
        $expiryMonth = (int) $request->card_expiry_month;

        if ($expiryYear < $currentYear || ($expiryYear == $currentYear && $expiryMonth < $currentMonth)) {
            return [
                'success' => false,
                'message' => 'Your card has expired. Please use a valid card.'
            ];
        }

        // Test card numbers for different scenarios
        switch ($lastFourDigits) {
            case '0002':
                return [
                    'success' => false,
                    'message' => 'Card declined. Please use a different payment method.'
                ];
            
            case '0003':
                return [
                    'success' => false,
                    'message' => 'Insufficient funds. Please check your account balance.'
                ];
            
            case '0004':
                return [
                    'success' => false,
                    'message' => 'Your card has expired. Please use a valid card.'
                ];
            
            default:
                return [
                    'success' => true,
                    'transaction_id' => 'TXN_' . strtoupper(uniqid()),
                    'gateway_response' => 'Payment processed successfully',
                    'authorization_code' => 'AUTH_' . rand(100000, 999999)
                ];
        }
    }

    /**
     * Create payment record
     */
    private function createPaymentRecord($request, $paymentResult)
    {
        $paymentData = [
            'id' => rand(1000, 9999),
            'user_id' => Auth::id(),
            'booking_id' => $request->booking_id,
            'amount' => $request->amount,
            'currency' => 'LKR',
            'payment_method' => $request->payment_method,
            'status' => 'completed',
            'payment_status' => 'completed',
            'transaction_id' => $paymentResult['transaction_id'],
            'gateway_response' => $paymentResult['gateway_response'] ?? null,
            'authorization_code' => $paymentResult['authorization_code'] ?? null,
            'payment_reference' => 'PAY_' . strtoupper(uniqid()),
            'processed_at' => now(),
            'paid_at' => now(),
            'created_at' => now(),
            'updated_at' => now()
        ];

        // Add card-specific data only for card payments
        if (in_array($request->payment_method, ['visa', 'mastercard', 'amex'])) {
            $paymentData['card_last_four'] = substr(str_replace(' ', '', $request->card_number), -4);
            $paymentData['card_type'] = $this->getCardType($request->card_number);
        }

        // If real Payment model exists, create record in database
        if (class_exists('\App\Models\Payment')) {
            try {
                $payment = \App\Models\Payment::create($paymentData);
                return $payment->toArray();
            } catch (\Exception $e) {
                Log::warning('Could not create payment record in database: ' . $e->getMessage());
            }
        }

        return $paymentData;
    }

    /**
     * Get sample booking for demo
     */
    private function getSampleBooking($bookingId)
    {
        return (object) [
            'id' => $bookingId,
            'booking_reference' => 'BK-DEMO' . str_pad($bookingId, 3, '0', STR_PAD_LEFT),
            'user_id' => Auth::id(),
            'hostel' => (object) [
                'id' => 1,
                'name' => 'Mixed University Hostel - Kandy',
                'location' => 'Kandy',
                'image_url' => null
            ],
            'hostelPackage' => (object) [
                'id' => 1,
                'name' => 'Standard Room',
                'type_display' => 'Mixed Hostel',
                'monthly_price' => 18000,
                'image_url' => '/images/hostels/default-room.jpg'
            ],
            'check_in_date' => Carbon::now()->addDays(10),
            'check_out_date' => Carbon::now()->addDays(40),
            'duration' => 30,
            'total_amount' => 19000,
            'amount' => 19000,
            'formatted_amount' => 'LKR 19,000',
            'payment_status' => 'pending',
            'booking_status' => 'pending',
            'created_at' => Carbon::now()->subDays(1),
            'user' => Auth::user()
        ];
    }

    /**
     * Get sample payment for demo
     */
    private function getSamplePayment($paymentId)
    {
        return (object) [
            'id' => $paymentId,
            'payment_reference' => 'PAY-DEMO' . str_pad($paymentId, 3, '0', STR_PAD_LEFT),
            'user_id' => Auth::id(),
            'booking_id' => 1,
            'amount' => 19000,
            'currency' => 'LKR',
            'payment_method' => 'visa',
            'status' => 'completed',
            'payment_status' => 'completed',
            'transaction_id' => 'TXN_DEMO' . $paymentId,
            'authorization_code' => 'AUTH_123456',
            'processed_at' => Carbon::now(),
            'paid_at' => Carbon::now(),
            'created_at' => Carbon::now(),
            'booking' => (object) [
                'id' => 1,
                'booking_reference' => 'BK-DEMO001',
                'hostel' => (object) [
                    'name' => 'Mixed University Hostel - Kandy',
                    'location' => 'Kandy'
                ],
                'check_in_date' => Carbon::now()->addDays(10),
                'check_out_date' => Carbon::now()->addDays(40)
            ]
        ];
    }

    /**
     * Get sample payments for demo
     */
    private function getSamplePayments()
    {
        return collect([
            (object) [
                'id' => 1,
                'payment_reference' => 'PAY-DEMO001',
                'amount' => 19000,
                'currency' => 'LKR',
                'payment_method' => 'visa',
                'status' => 'completed',
                'created_at' => Carbon::now()->subDays(5),
                'booking' => (object) [
                    'booking_reference' => 'BK-DEMO001',
                    'hostel' => (object) [
                        'name' => 'Mixed University Hostel - Kandy'
                    ]
                ]
            ]
        ]);
    }

    /**
     * Validate card number (Luhn algorithm)
     */
    private function validateCardNumber($cardNumber)
    {
        $cardNumber = preg_replace('/\D/', '', $cardNumber);
        
        if (strlen($cardNumber) < 13 || strlen($cardNumber) > 19) {
            return false;
        }
        
        $sum = 0;
        $length = strlen($cardNumber);
        
        for ($i = 0; $i < $length; $i++) {
            $digit = intval($cardNumber[$length - $i - 1]);
            
            if ($i % 2 == 1) {
                $digit *= 2;
                if ($digit > 9) {
                    $digit -= 9;
                }
            }
            
            $sum += $digit;
        }
        
        return $sum % 10 == 0;
    }

    /**
     * Get card type from card number
     */
    private function getCardType($cardNumber)
    {
        $cardNumber = preg_replace('/\D/', '', $cardNumber);
        
        if (preg_match('/^4/', $cardNumber)) {
            return 'visa';
        } elseif (preg_match('/^5[1-5]/', $cardNumber)) {
            return 'mastercard';
        } elseif (preg_match('/^3[47]/', $cardNumber)) {
            return 'amex';
        } else {
            return 'unknown';
        }
    }

    // Webhook handlers remain the same...
    // ...existing code...

    public function paypalWebhook(Request $request)
    {
        Log::info('PayPal webhook received', $request->all());
        return response()->json(['status' => 'success']);
    }

    public function razorpayWebhook(Request $request)
    {
        Log::info('Razorpay webhook received', $request->all());
        return response()->json(['status' => 'success']);
    }

    /**
     * Request refund for a payment
     */
    public function requestRefund($paymentId)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        try {
            if (class_exists('\App\Models\Payment')) {
                $payment = \App\Models\Payment::where('user_id', Auth::id())
                    ->findOrFail($paymentId);
                
                // Check if payment is eligible for refund
                if (!$payment->isSuccessful()) {
                    return back()->with('error', 'Only successful payments can be refunded.');
                }
                
                // Create refund request (you would implement this based on your refund system)
                Log::info('Refund requested', [
                    'payment_id' => $paymentId,
                    'user_id' => Auth::id()
                ]);
                
                return back()->with('success', 'Refund request submitted successfully.');
            }
            
            return back()->with('error', 'Payment not found.');
        } catch (\Exception $e) {
            Log::error('Refund request error: ' . $e->getMessage());
            return back()->with('error', 'Failed to submit refund request.');
        }
    }

    /**
     * Check refund status
     */
    public function refundStatus($paymentId)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        try {
            if (class_exists('\App\Models\Payment')) {
                $payment = \App\Models\Payment::where('user_id', Auth::id())
                    ->findOrFail($paymentId);
                
                // Return refund status (you would implement this based on your refund system)
                return response()->json([
                    'status' => 'pending',
                    'message' => 'Refund is being processed'
                ]);
            }
            
            return response()->json(['error' => 'Payment not found.'], 404);
        } catch (\Exception $e) {
            Log::error('Refund status error: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to get refund status.'], 500);
        }
    }

    /**
     * Admin refunds index
     */
    public function adminRefunds()
    {
        // This would typically check for admin permissions
        if (!Auth::check() || Auth::user()->role !== 'admin') {
            return redirect()->route('login');
        }

        try {
            if (class_exists('\App\Models\Payment')) {
                $refunds = \App\Models\Payment::where('status', 'refunded')
                    ->with(['user', 'booking'])
                    ->orderBy('created_at', 'desc')
                    ->paginate(20);
            } else {
                $refunds = collect();
            }

            return view('admin.refunds.index', compact('refunds'));
        } catch (\Exception $e) {
            Log::error('Admin refunds error: ' . $e->getMessage());
            return view('admin.refunds.index', ['refunds' => collect()]);
        }
    }

    /**
     * Show refund details
     */
    public function showRefund($refundId)
    {
        // This would typically check for admin permissions
        if (!Auth::check() || Auth::user()->role !== 'admin') {
            return redirect()->route('login');
        }

        try {
            if (class_exists('\App\Models\Payment')) {
                $refund = \App\Models\Payment::where('id', $refundId)
                    ->with(['user', 'booking'])
                    ->firstOrFail();
            } else {
                $refund = null;
            }

            return view('admin.refunds.show', compact('refund'));
        } catch (\Exception $e) {
            Log::error('Show refund error: ' . $e->getMessage());
            return redirect()->route('admin.refunds.index')
                ->with('error', 'Refund not found.');
        }
    }

    /**
     * Process refund
     */
    public function processRefund(Request $request, $refundId)
    {
        // This would typically check for admin permissions
        if (!Auth::check() || Auth::user()->role !== 'admin') {
            return redirect()->route('login');
        }

        try {
            if (class_exists('\App\Models\Payment')) {
                $refund = \App\Models\Payment::findOrFail($refundId);
                
                // Process the refund (you would implement this based on your refund system)
                $refund->update([
                    'status' => 'refunded',
                    'payment_status' => 'refunded',
                    'updated_at' => now()
                ]);
                
                Log::info('Refund processed', [
                    'refund_id' => $refundId,
                    'admin_id' => Auth::id()
                ]);
                
                return redirect()->route('admin.refunds.index')
                    ->with('success', 'Refund processed successfully.');
            }
            
            return redirect()->route('admin.refunds.index')
                ->with('error', 'Refund not found.');
        } catch (\Exception $e) {
            Log::error('Process refund error: ' . $e->getMessage());
            return redirect()->route('admin.refunds.index')
                ->with('error', 'Failed to process refund.');
        }
    }
}