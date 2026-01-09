<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\PDF;
use App\Models\Payment;
use App\Models\Booking;
use Illuminate\Support\Facades\Response;

class DummyPaymentController extends Controller
{
    // Show dummy payment form
    public function showForm($bookingId)
    {
        $booking = Booking::findOrFail($bookingId);
        return view('payments.dummy', compact('booking'));
    }

    // Process dummy payment
    public function process(Request $request, $bookingId)
    {
        $booking = Booking::findOrFail($bookingId);
        $payment = Payment::create([
            'user_id' => $booking->user_id,
            'booking_id' => $booking->id,
            'amount' => $booking->price,
            'currency' => 'LKR',
            'payment_method' => 'dummy_gateway',
            'payment_status' => 'completed',
            'status' => 'completed',
            'payment_reference' => 'DUMMY-' . uniqid(),
            'transaction_id' => 'TXN-' . uniqid(),
            'gateway_response' => ['dummy' => true, 'message' => 'Payment successful'],
            'processed_at' => now(),
            'paid_at' => now(),
        ]);
        return redirect()->route('payments.receipt', $payment->id);
    }

    // Generate and download PDF receipt
    public function receipt($paymentId)
    {
        $payment = Payment::findOrFail($paymentId);
        $pdf = app('dompdf.wrapper');
        $pdf->loadView('payments.receipt', compact('payment'));
        return $pdf->download('payment_receipt_' . $payment->id . '.pdf');
    }
}
