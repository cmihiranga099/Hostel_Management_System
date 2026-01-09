<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Validator;
use Illuminate\Routing\Controller as BaseController;
use Carbon\Carbon;

class BookingController extends BaseController
{
    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        // Apply auth middleware to all methods
        if (method_exists($this, 'middleware')) {
            $this->middleware('auth');
        }
    }

    /**
     * Display a listing of user's bookings
     */
    public function index()
    {
        try {
            // Try to get real bookings if Booking model exists
            if (class_exists('\App\Models\Booking')) {
                $bookings = \App\Models\Booking::where('user_id', Auth::id())
                    ->with(['hostel', 'hostelPackage', 'package'])
                    ->orderBy('created_at', 'desc')
                    ->paginate(10);
            } else {
                // Fallback to sample data
                $bookings = $this->getSampleBookings();
            }

            return view('bookings.index', compact('bookings'));
        } catch (\Exception $e) {
            Log::error('Error in bookings index: ' . $e->getMessage());
            return view('bookings.index', ['bookings' => collect()]);
        }
    }

    /**
     * Show the form for creating a new booking
     */
    public function create($hostelId = null)
    {
        try {
            // Validate hostel ID
            if (!$hostelId) {
                return redirect()->back()->with('error', 'Hostel ID is required.');
            }

            // Try to get hostel data
            if (class_exists('\App\Models\Hostel')) {
                $hostel = \App\Models\Hostel::find($hostelId);
                
                if (!$hostel) {
                    return redirect()->back()->with('error', 'Hostel not found.');
                }
            } else {
                // Create sample hostel data if model doesn't exist
                $hostel = $this->getSampleHostel($hostelId);
            }

            $user = Auth::user();
            
            // Get packages for this hostel if available
            $packages = [];
            if (class_exists('\App\Models\HostelPackage')) {
                $packages = \App\Models\HostelPackage::where('hostel_id', $hostelId)->get();
            } else {
                $packages = $this->getSamplePackages($hostelId);
            }

            return view('bookings.create', compact('hostel', 'user', 'packages'));
        } catch (\Exception $e) {
            Log::error('Error in booking create: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Unable to process booking request. Please try again.');
        }
    }

    /**
     * Alternative create method for direct booking
     */
    public function book($hostelId)
    {
        return $this->create($hostelId);
    }

    /**
     * Store a newly created booking (FIXED VERSION)
     */
    public function store(Request $request)
    {
        try {
            // Log the incoming request for debugging
            Log::info('Booking store method called', [
                'user_id' => Auth::id(),
                'request_data' => $request->except(['password']),
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent()
            ]);

            // Check if user is authenticated
            if (!Auth::check()) {
                Log::warning('Unauthenticated booking attempt', ['ip' => $request->ip()]);
                
                if ($request->expectsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Please log in to make a booking.',
                        'redirect' => route('login')
                    ], 401);
                }
                
                return redirect()->route('login')->with('error', 'Please log in to make a booking.');
            }

            $user = Auth::user();

            // FIXED VALIDATION - Only booking information, no payment details
            $validator = Validator::make($request->all(), [
                'hostel_id' => 'required|integer',
                'check_in_date' => 'required|date|after_or_equal:today',
                'check_out_date' => 'required|date|after:check_in_date',
                'hostel_package_id' => 'required|integer',
                'guest_name' => 'required|string|max:255',
                'guest_email' => 'required|email',
                'guest_phone' => 'required|string|max:20',
                'emergency_contact_name' => 'nullable|string|max:255',
                'emergency_contact_phone' => 'nullable|string|max:20',
                'terms_accepted' => 'required|accepted',
                
                // Optional fields
                'special_requirements' => 'nullable|string|max:1000',
                'student_id' => 'nullable|string|max:50',
                'university' => 'nullable|string|max:255',
                'dietary_requirements' => 'nullable|string|max:255'
            ]);

            if ($validator->fails()) {
                Log::warning('Booking validation failed', [
                    'user_id' => $user->id,
                    'errors' => $validator->errors()->toArray()
                ]);

                if ($request->expectsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Please correct the following errors:',
                        'errors' => $validator->errors()
                    ], 422);
                }
                
                return back()->withErrors($validator->errors())->withInput();
            }

            $validatedData = $validator->validated();

            Log::info('Validation passed', ['user_id' => Auth::id()]);

            // Check if hostel exists
            $hostelExists = $this->checkHostelExists($validatedData['hostel_id']);
            if (!$hostelExists) {
                Log::error('Booking for non-existent hostel', [
                    'user_id' => $user->id,
                    'hostel_id' => $validatedData['hostel_id']
                ]);

                if ($request->expectsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Selected hostel is not available.'
                    ], 404);
                }
                
                return back()->with('error', 'Selected hostel is not available.')->withInput();
            }

            // Calculate values
            $checkInDate = Carbon::parse($request->check_in_date);
            $checkOutDate = Carbon::parse($request->check_out_date);
            $durationDays = $checkInDate->diffInDays($checkOutDate);
            
            // Generate unique booking reference
            $bookingReference = $this->generateBookingReference();

            // Check for duplicate bookings
            $duplicateBooking = $this->checkDuplicateBooking($user->id, [
                'hostel_id' => $validatedData['hostel_id'],
                'check_in_date' => $validatedData['check_in_date'],
                'check_out_date' => $validatedData['check_out_date']
            ]);

            if ($duplicateBooking) {
                Log::warning('Duplicate booking attempt', [
                    'user_id' => $user->id,
                    'existing_booking_id' => $duplicateBooking
                ]);

                if ($request->expectsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'You already have a booking for this period.'
                    ], 409);
                }
                
                return back()->with('error', 'You already have a booking for this period.')->withInput();
            }

            // Start database transaction
            DB::beginTransaction();

            try {
                // Prepare booking data with fallbacks
                $bookingData = [
                    'user_id' => $user->id,
                    'hostel_id' => $validatedData['hostel_id'],
                    'booking_reference' => $bookingReference,
                    'check_in_date' => $validatedData['check_in_date'],
                    'check_out_date' => $validatedData['check_out_date'],
                    'duration_days' => $durationDays,
                    'total_amount' => 0, // Placeholder, will be updated after payment
                    'amount' => 0, // Placeholder, will be updated after payment
                    'status' => 'pending',
                    'booking_status' => 'pending',
                    'payment_status' => 'pending',
                    'payment_method' => null, // Placeholder, will be updated after payment
                    'guest_name' => $validatedData['guest_name'],
                    'guest_email' => $validatedData['guest_email'],
                    'guest_phone' => $validatedData['guest_phone'],
                    'emergency_contact_name' => $validatedData['emergency_contact_name'] ?? null,
                    'emergency_contact_phone' => $validatedData['emergency_contact_phone'] ?? null,
                                         'special_requests' => $validatedData['special_requirements'] ?? null,
                    'student_id' => $validatedData['student_id'] ?? null,
                    'university' => $validatedData['university'] ?? null,
                    'package' => null, // Placeholder, will be updated after payment
                    'duration' => null, // Placeholder, will be updated after payment
                    'emergency_contact' => $validatedData['emergency_contact_name'],
                    'dietary_requirements' => $validatedData['dietary_requirements'] ?? null,
                    'booked_at' => now(),
                    'created_at' => now(),
                    'updated_at' => now(),
                ];

                // Add package_id fields with fallbacks
                if (isset($validatedData['hostel_package_id'])) {
                    $bookingData['package_id'] = $validatedData['hostel_package_id'];
                    $bookingData['hostel_package_id'] = $validatedData['hostel_package_id'];
                }

                // Create booking record
                if ($this->hasBookingsTable()) {
                    if (class_exists('\App\Models\Booking')) {
                        $booking = \App\Models\Booking::create($bookingData);
                        Log::info('Booking created via Eloquent', ['booking_id' => $booking->id]);
                    } else {
                        $bookingId = DB::table('bookings')->insertGetId($bookingData);
                        $booking = (object) array_merge($bookingData, ['id' => $bookingId]);
                        Log::info('Booking created via Query Builder', ['booking_id' => $booking->id]);
                    }
                } else {
                    // Create bookings table if it doesn't exist
                    $this->createBookingsTable();
                    $bookingId = DB::table('bookings')->insertGetId($bookingData);
                    $booking = (object) array_merge($bookingData, ['id' => $bookingId]);
                    Log::info('Bookings table created and booking inserted', ['booking_id' => $booking->id]);
                }

                DB::commit();
                Log::info('Transaction committed successfully');

                                 // Redirect to payment page
                 if (class_exists('\App\Http\Controllers\PaymentController')) {
                     return redirect()->route('payments.show', $booking->id)
                         ->with('success', 'Booking created! Please complete payment to confirm.');
                 } else {
                     return redirect()->route('student.bookings')
                         ->with('success', 'Booking created successfully! Reference: ' . $bookingReference);
                 }

            } catch (\Exception $e) {
                DB::rollback();
                throw $e;
            }

        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollback();
            Log::error('Validation failed', ['errors' => $e->errors()]);
            
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $e->errors()
                ], 422);
            }
            
            return back()->withErrors($e->errors())->withInput();
            
        } catch (\Illuminate\Database\QueryException $e) {
            DB::rollback();
            Log::error('Database error during booking', [
                'user_id' => Auth::id(),
                'error' => $e->getMessage(),
                'sql' => $e->getSql() ?? 'N/A',
                'code' => $e->getCode()
            ]);

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Database error occurred. Please try again.',
                    'debug' => app()->environment('local') ? $e->getMessage() : null
                ], 500);
            }

            return back()->with('error', 'Database error occurred. Please try again.')->withInput();
            
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Booking creation failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'user_id' => Auth::id(),
                'line' => $e->getLine(),
                'file' => $e->getFile()
            ]);

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Server error occurred. Please try again in a few moments.',
                    'error' => app()->environment('local') ? $e->getMessage() : 'Internal server error'
                ], 500);
            }

            return back()->with('error', 'Failed to create booking. Please try again.')->withInput();
        }
    }

    /**
     * Display the specified booking
     */
    public function show($id)
    {
        try {
            if (class_exists('\App\Models\Booking')) {
                $booking = \App\Models\Booking::where('user_id', Auth::id())
                    ->with(['hostel', 'hostelPackage', 'package', 'payments'])
                    ->findOrFail($id);
            } else {
                $booking = $this->getSampleBooking($id);
            }

            return view('bookings.show', compact('booking'));
        } catch (\Exception $e) {
            Log::error('Error showing booking', ['booking_id' => $id, 'error' => $e->getMessage()]);
            
            // Check if we're in student context
            if (request()->is('student/*')) {
                return redirect()->route('student.bookings')->with('error', 'Booking not found.');
            } else {
                return redirect()->route('bookings.index')->with('error', 'Booking not found.');
            }
        }
    }

    /**
     * Handle AJAX booking submissions
     */
    public function ajaxStore(Request $request)
    {
        return $this->store($request);
    }

    /**
     * Show booking success page
     */
    public function showSuccess($id)
    {
        try {
            if ($this->hasBookingsTable()) {
                $booking = DB::table('bookings')
                    ->where('id', $id)
                    ->where('user_id', Auth::id())
                    ->first();

                if (!$booking) {
                    return redirect()->route('hostels')->with('error', 'Booking not found.');
                }
            } else {
                $booking = $this->getSampleBooking($id);
            }

            return view('bookings.success', compact('booking'));
        } catch (\Exception $e) {
            Log::error('Error showing booking success page', [
                'booking_id' => $id,
                'error' => $e->getMessage()
            ]);

            return redirect()->route('hostels')->with('error', 'Unable to load booking details.');
        }
    }

    // ==========================================
    // HELPER METHODS (IMPROVED WITH ERROR HANDLING)
    // ==========================================

    /**
     * Check if bookings table exists
     */
    private function hasBookingsTable()
    {
        try {
            return Schema::hasTable('bookings');
        } catch (\Exception $e) {
            Log::error('Error checking bookings table', ['error' => $e->getMessage()]);
            return false;
        }
    }

    /**
     * Create bookings table if it doesn't exist
     */
    private function createBookingsTable()
    {
        try {
            if (!Schema::hasTable('bookings')) {
                Schema::create('bookings', function ($table) {
                    $table->id();
                    $table->foreignId('user_id')->constrained()->onDelete('cascade');
                    $table->unsignedBigInteger('hostel_id');
                    $table->string('booking_reference')->unique();
                    $table->string('package')->nullable();
                    $table->string('duration')->nullable();
                    $table->integer('package_id')->nullable();
                    $table->integer('hostel_package_id')->nullable();
                    $table->date('check_in_date');
                    $table->date('check_out_date');
                    $table->integer('duration_days')->nullable();
                    $table->decimal('total_amount', 10, 2);
                    $table->decimal('amount', 10, 2)->nullable();
                    $table->enum('status', ['pending', 'confirmed', 'cancelled', 'completed'])->default('pending');
                    $table->string('booking_status')->default('pending');
                    $table->string('payment_status')->default('pending');
                    $table->string('payment_method');
                    $table->string('emergency_contact_name');
                    $table->string('emergency_contact_phone');
                    $table->string('emergency_contact')->nullable();
                    $table->text('special_requests')->nullable();
                    $table->string('student_id')->nullable();
                    $table->string('university')->nullable();
                    $table->text('dietary_requirements')->nullable();
                    $table->timestamp('booked_at')->nullable();
                    $table->timestamps();

                    // Indexes
                    $table->index(['user_id', 'status']);
                    $table->index(['hostel_id', 'check_in_date']);
                    $table->index('booking_reference');
                });
                
                Log::info('Bookings table created successfully');
                return true;
            }
            return true;
        } catch (\Exception $e) {
            Log::error('Error creating bookings table', ['error' => $e->getMessage()]);
            return false;
        }
    }

    /**
     * Check if hostel exists
     */
    private function checkHostelExists($hostelId)
    {
        try {
            if (Schema::hasTable('hostels')) {
                return DB::table('hostels')->where('id', $hostelId)->exists();
            }
            
            // If no hostels table, return true for demo
            return true;
        } catch (\Exception $e) {
            Log::error('Error checking hostel existence', [
                'hostel_id' => $hostelId,
                'error' => $e->getMessage()
            ]);
            return true; // Allow booking to proceed
        }
    }

    /**
     * Check for duplicate bookings
     */
    private function checkDuplicateBooking($userId, $bookingData)
    {
        try {
            if (!$this->hasBookingsTable()) {
                return false;
            }

            return DB::table('bookings')
                ->where('user_id', $userId)
                ->where('hostel_id', $bookingData['hostel_id'])
                ->where('status', '!=', 'cancelled')
                ->where(function ($query) use ($bookingData) {
                    $query->whereBetween('check_in_date', [
                        $bookingData['check_in_date'], 
                        $bookingData['check_out_date']
                    ])
                    ->orWhereBetween('check_out_date', [
                        $bookingData['check_in_date'], 
                        $bookingData['check_out_date']
                    ]);
                })
                ->value('id');
        } catch (\Exception $e) {
            Log::error('Error checking duplicate booking', [
                'user_id' => $userId,
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }

    /**
     * Generate unique booking reference
     */
    private function generateBookingReference()
    {
        $maxAttempts = 5;
        $attempts = 0;
        
        do {
            $reference = 'BK-' . strtoupper(substr(uniqid(), -8)) . '-' . time();
            $attempts++;
            
            if (!$this->hasBookingsTable()) {
                break;
            }
            
            $exists = DB::table('bookings')->where('booking_reference', $reference)->exists();
        } while ($exists && $attempts < $maxAttempts);

        return $reference;
    }

    /**
     * Create payment record
     */
    private function createPaymentRecord($paymentData)
    {
        try {
            if (Schema::hasTable('payments')) {
                return DB::table('payments')->insertGetId($paymentData);
            } else {
                // Create payments table if needed
                Schema::create('payments', function ($table) {
                    $table->id();
                    $table->foreignId('user_id')->constrained();
                    $table->foreignId('booking_id')->constrained();
                    $table->decimal('amount', 10, 2);
                    $table->string('payment_method');
                    $table->string('card_last_four', 4)->nullable();
                    $table->string('transaction_id')->nullable();
                    $table->string('status')->default('pending');
                    $table->timestamps();
                });
                
                return DB::table('payments')->insertGetId($paymentData);
            }
        } catch (\Exception $e) {
            Log::error('Error creating payment record', [
                'payment_data' => $paymentData,
                'error' => $e->getMessage()
            ]);
            return null; // Don't throw error, just log it
        }
    }

    /**
     * Process payment (Enhanced with better error handling)
     */
    private function processPayment($request, $booking, $amount)
    {
        try {
            Log::info('Processing payment', [
                'booking_id' => $booking->id ?? 'demo',
                'amount' => $amount,
                'payment_method' => $request->payment_method
            ]);

            // Simulate payment processing delay
            sleep(1);

            // Get card number for testing scenarios
            $cardNumber = str_replace(' ', '', $request->card_number);
            $lastFourDigits = substr($cardNumber, -4);

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
                    // Simulate successful payment
                    $transactionId = 'TXN_' . strtoupper(uniqid()) . '_' . time();
                    
                    Log::info('Payment processed successfully', [
                        'transaction_id' => $transactionId,
                        'amount' => $amount
                    ]);

                    return [
                        'success' => true,
                        'transaction_id' => $transactionId,
                        'gateway_response' => 'Payment processed successfully',
                        'authorization_code' => 'AUTH_' . rand(100000, 999999),
                        'message' => 'Payment completed successfully'
                    ];
            }
        } catch (\Exception $e) {
            Log::error('Payment processing error', [
                'error' => $e->getMessage(),
                'booking_id' => $booking->id ?? 'demo'
            ]);

            return [
                'success' => false,
                'message' => 'Payment processing failed: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Helper: Get package price
     */
    private function getPackagePrice($packageId)
    {
        try {
            // Try to get from database first
            if (class_exists('\App\Models\HostelPackage')) {
                $package = \App\Models\HostelPackage::find($packageId);
                if ($package && $package->monthly_price) {
                    return $package->monthly_price;
                }
            }
            
            if (class_exists('\App\Models\Package')) {
                $package = \App\Models\Package::find($packageId);
                if ($package && $package->monthly_price) {
                    return $package->monthly_price;
                }
            }
            
            // Default pricing based on package ID
            return $packageId == 2 ? 25000 : 18000;
        } catch (\Exception $e) {
            Log::error('Error getting package price', ['package_id' => $packageId, 'error' => $e->getMessage()]);
            return 18000; // Default price
        }
    }

    /**
     * Get sample hostel for demo
     */
    private function getSampleHostel($hostelId)
    {
        return (object) [
            'id' => $hostelId,
            'name' => 'SLIIT Boys Hostel',
            'location' => 'Malabe',
            'address' => '123 Malabe Road, Malabe',
            'phone' => '+94 11 123 4567',
            'email' => 'info@sliithostel.com',
            'description' => 'A comfortable hostel for students',
            'facilities' => 'WiFi, Meals, Laundry, Security',
            'created_at' => Carbon::now()
        ];
    }

    /**
     * Get sample packages for demo
     */
    private function getSamplePackages($hostelId)
    {
        return collect([
            (object) [
                'id' => 1,
                'hostel_id' => $hostelId,
                'name' => 'Standard Room',
                'description' => 'Basic accommodation with essential amenities',
                'daily_price' => 1000,
                'weekly_price' => 6000,
                'monthly_price' => 18000,
                'facilities' => 'Bed, Desk, Wardrobe, Shared Bathroom'
            ],
            (object) [
                'id' => 2,
                'hostel_id' => $hostelId,
                'name' => 'Premium Room',
                'description' => 'Enhanced accommodation with additional amenities',
                'daily_price' => 1500,
                'weekly_price' => 9000,
                'monthly_price' => 25000,
                'facilities' => 'Bed, Desk, Wardrobe, Private Bathroom, AC'
            ]
        ]);
    }

    /**
     * Get sample bookings for demo
     */
    private function getSampleBookings()
    {
        return collect([
            (object) [
                'id' => 1,
                'booking_reference' => 'BK-DEMO001',
                'hostel' => (object) ['name' => 'SLIIT Boys Hostel'],
                'hostelPackage' => (object) ['name' => 'Standard Room'],
                'check_in_date' => Carbon::now()->addDays(10),
                'check_out_date' => Carbon::now()->addDays(40),
                'status' => 'confirmed',
                'booking_status' => 'confirmed',
                'payment_status' => 'paid',
                'total_amount' => 25000,
                'amount' => 25000,
                'formatted_amount' => 'LKR 25,000',
                'status_badge' => '<span class="badge bg-success">Confirmed</span>',
                'created_at' => Carbon::now()->subDays(5)
            ],
            (object) [
                'id' => 2,
                'booking_reference' => 'BK-DEMO002',
                'hostel' => (object) ['name' => 'University Boys Hostel'],
                'hostelPackage' => (object) ['name' => 'Premium Room'],
                'check_in_date' => Carbon::now()->addDays(5),
                'check_out_date' => Carbon::now()->addDays(35),
                'status' => 'pending',
                'booking_status' => 'pending',
                'payment_status' => 'pending',
                'total_amount' => 22000,
                'amount' => 22000,
                'formatted_amount' => 'LKR 22,000',
                'status_badge' => '<span class="badge bg-warning">Pending</span>',
                'created_at' => Carbon::now()->subDays(2)
            ]
        ]);
    }

    /**
     * Get sample booking for demo
     */
    private function getSampleBooking($id)
    {
        return (object) [
            'id' => $id,
            'booking_reference' => 'BK-DEMO' . str_pad($id, 3, '0', STR_PAD_LEFT),
            'hostel' => (object) [
                'id' => 3,
                'name' => 'SLIIT Boys Hostel',
                'location' => 'Malabe',
                'phone' => '+94 11 123 4567'
            ],
            'hostelPackage' => (object) [
                'name' => 'Standard Room',
                'description' => 'Comfortable accommodation with basic amenities'
            ],
            'package' => (object) [
                'id' => 1,
                'name' => 'Standard Room',
                'monthly_price' => 18000
            ],
            'user' => Auth::user(),
            'hostel_id' => 3,
            'package_id' => 1,
            'check_in_date' => Carbon::now()->addDays(10),
            'check_out_date' => Carbon::now()->addDays(40),
            'status' => 'confirmed',
            'booking_status' => 'confirmed',
            'payment_status' => 'paid',
            'total_amount' => 19000,
            'amount' => 19000,
            'formatted_amount' => 'LKR 19,000',
            'special_requests' => 'Ground floor room preferred',
            'emergency_contact_name' => 'John Doe',
            'emergency_contact_phone' => '+94 77 123 4567',
            'duration_days' => 30,
            'created_at' => Carbon::now()->subDays(5)
        ];
    }

    /**
     * Check availability for booking dates
     */
    public function checkAvailability(Request $request)
    {
        $request->validate([
            'hostel_id' => 'required|integer',
            'check_in_date' => 'required|date|after:today',
            'check_out_date' => 'required|date|after:check_in_date'
        ]);

        try {
            if ($this->hasBookingsTable() && class_exists('\App\Models\Booking')) {
                // Real availability check logic
                $conflictingBookings = \App\Models\Booking::where('hostel_id', $request->hostel_id)
                    ->whereIn('booking_status', ['confirmed', 'checked_in'])
                    ->orWhereIn('status', ['confirmed', 'checked_in'])
                    ->where(function($query) use ($request) {
                        $query->whereBetween('check_in_date', [$request->check_in_date, $request->check_out_date])
                              ->orWhereBetween('check_out_date', [$request->check_in_date, $request->check_out_date])
                              ->orWhere(function($q) use ($request) {
                                  $q->where('check_in_date', '<=', $request->check_in_date)
                                    ->where('check_out_date', '>=', $request->check_out_date);
                              });
                    })
                    ->count();

                $available = $conflictingBookings === 0;
            } else {
                // Demo mode - always available
                $available = true;
                $conflictingBookings = 0;
            }

            return response()->json([
                'available' => $available,
                'message' => $available ? 'Dates are available!' : 'Selected dates are not available.',
                'conflicting_bookings' => $conflictingBookings
            ]);
        } catch (\Exception $e) {
            Log::error('Availability check error: ' . $e->getMessage());
            return response()->json([
                'available' => false,
                'message' => 'Error checking availability. Please try again.'
            ], 500);
        }
    }

    /**
     * AJAX: Calculate booking price
     */
    public function calculatePrice(Request $request)
    {
        $request->validate([
            'check_in_date' => 'required|date',
            'check_out_date' => 'required|date|after:check_in_date',
            'package_id' => 'required|integer'
        ]);
        
        try {
            $checkInDate = Carbon::parse($request->check_in_date);
            $checkOutDate = Carbon::parse($request->check_out_date);
            $daysDiff = $checkOutDate->diffInDays($checkInDate);
            $months = max(1, ceil($daysDiff / 30));
            
            $packagePrice = $this->getPackagePrice($request->package_id);
            $subtotal = $packagePrice * $months;
            $serviceFee = 1000;
            $total = $subtotal + $serviceFee;
            
            return response()->json([
                'success' => true,
                'data' => [
                    'months' => $months,
                    'monthly_rate' => $packagePrice,
                    'subtotal' => $subtotal,
                    'service_fee' => $serviceFee,
                    'total' => $total
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('Price calculation error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error calculating price.'
            ], 500);
        }
    }

    /**
     * Quick booking for API calls
     */
    public function quickBook(Request $request)
    {
        $request->validate([
            'hostel_id' => 'required|integer',
            'check_in_date' => 'required|date|after:today',
            'check_out_date' => 'required|date|after:check_in_date',
            'emergency_contact_name' => 'required|string|max:255',
            'emergency_contact_phone' => 'required|string|max:20',
        ]);

        return $this->store($request);
    }

    /**
     * API method for checking availability
     */
    public function apiCheckAvailability(Request $request)
    {
        return $this->checkAvailability($request);
    }

    /**
     * Update booking status (for admin/API use)
     */
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,confirmed,cancelled,completed,checked_in,checked_out'
        ]);

        try {
            if ($this->hasBookingsTable() && class_exists('\App\Models\Booking')) {
                $booking = \App\Models\Booking::findOrFail($id);
                
                // Update both status fields for compatibility
                $booking->update([
                    'status' => $request->status,
                    'booking_status' => $request->status
                ]);

                return response()->json([
                    'success' => true,
                    'message' => 'Booking status updated successfully.',
                    'new_status' => $request->status
                ]);
            } else {
                return response()->json([
                    'success' => true,
                    'message' => 'Status updated! (Demo mode)',
                    'new_status' => $request->status
                ]);
            }
        } catch (\Exception $e) {
            Log::error('Status update error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to update booking status.'
            ], 500);
        }
    }

    /**
     * User's booking history (alias for index)
     */
    public function myBookings()
    {
        return $this->index();
    }
}