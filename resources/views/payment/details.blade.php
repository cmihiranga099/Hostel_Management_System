@extends('layouts.app')

@section('title', 'Payment Details - University Hostel Management')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <!-- Payment Details Header -->
            <div class="text-center mb-5">
                <h1 class="h2 mb-3">Payment Details</h1>
                <p class="lead text-muted">Complete information about your payment transaction</p>
            </div>

            <!-- Payment Information Card -->
            <div class="card mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-receipt me-2"></i>Payment Information
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6 class="mb-3">Payment Details</h6>
                            <div class="payment-info">
                                <div class="d-flex justify-content-between mb-2">
                                    <span>Payment ID:</span>
                                    <strong>{{ $payment->payment_reference ?? 'N/A' }}</strong>
                                </div>
                                <div class="d-flex justify-content-between mb-2">
                                    <span>Transaction ID:</span>
                                    <strong>{{ $payment->transaction_id ?? 'N/A' }}</strong>
                                </div>
                                <div class="d-flex justify-content-between mb-2">
                                    <span>Amount Paid:</span>
                                    <strong class="text-success">{{ $payment->formatted_amount ?? 'LKR ' . number_format($payment->amount ?? 0, 2) }}</strong>
                                </div>
                                <div class="d-flex justify-content-between mb-2">
                                    <span>Payment Method:</span>
                                    <strong>{{ ucfirst($payment->payment_method ?? 'N/A') }}</strong>
                                </div>
                                <div class="d-flex justify-content-between mb-2">
                                    <span>Status:</span>
                                    <span class="badge bg-success">{{ ucfirst($payment->status ?? 'N/A') }}</span>
                                </div>
                                <div class="d-flex justify-content-between">
                                    <span>Date & Time:</span>
                                    <strong>{{ ($payment->processed_at ?? now())->format('M d, Y h:i A') }}</strong>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <h6 class="mb-3">Booking Information</h6>
                            <div class="booking-info">
                                @if(isset($payment->booking))
                                    <div class="d-flex justify-content-between mb-2">
                                        <span>Booking ID:</span>
                                        <strong>{{ $payment->booking->booking_reference ?? 'N/A' }}</strong>
                                    </div>
                                    <div class="d-flex justify-content-between mb-2">
                                        <span>Hostel:</span>
                                        <strong>{{ $payment->booking->hostel->name ?? 'N/A' }}</strong>
                                    </div>
                                    <div class="d-flex justify-content-between mb-2">
                                        <span>Location:</span>
                                        <strong>{{ $payment->booking->hostel->location ?? 'N/A' }}</strong>
                                    </div>
                                    @if(isset($payment->booking->check_in_date))
                                    <div class="d-flex justify-content-between mb-2">
                                        <span>Check-in:</span>
                                        <strong>{{ Carbon\Carbon::parse($payment->booking->check_in_date)->format('M d, Y') }}</strong>
                                    </div>
                                    @endif
                                    @if(isset($payment->booking->check_out_date))
                                    <div class="d-flex justify-content-between">
                                        <span>Check-out:</span>
                                        <strong>{{ Carbon\Carbon::parse($payment->booking->check_out_date)->format('M d, Y') }}</strong>
                                    </div>
                                    @endif
                                @else
                                    <p class="text-muted">No booking information available</p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="row">
                <div class="col-md-6 mb-3">
                    <a href="{{ route('student.bookings') }}" class="btn btn-outline-primary w-100">
                        <i class="fas fa-arrow-left me-2"></i>Back to Bookings
                    </a>
                </div>
                <div class="col-md-6 mb-3">
                    <a href="{{ route('payments.history') }}" class="btn btn-primary w-100">
                        <i class="fas fa-history me-2"></i>Payment History
                    </a>
                </div>
            </div>

            <!-- Help Section -->
            <div class="card mt-4">
                <div class="card-body">
                    <h6 class="mb-3">
                        <i class="fas fa-question-circle me-2"></i>Need Help?
                    </h6>
                    <p class="mb-3">If you have any questions about your payment, our support team is here to help.</p>
                    <div class="row">
                        <div class="col-md-4 mb-2">
                            <a href="tel:+94112345678" class="btn btn-outline-primary btn-sm w-100">
                                <i class="fas fa-phone me-1"></i>Call Us
                            </a>
                        </div>
                        <div class="col-md-4 mb-2">
                            <a href="mailto:support@hostel.com" class="btn btn-outline-primary btn-sm w-100">
                                <i class="fas fa-envelope me-1"></i>Email Support
                            </a>
                        </div>
                        <div class="col-md-4 mb-2">
                            <a href="#" class="btn btn-outline-primary btn-sm w-100">
                                <i class="fas fa-comments me-1"></i>Live Chat
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection









