@extends('layouts.app')

@section('title', 'Payment Cancelled - University Hostel Management')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <!-- Cancelled Header -->
            <div class="text-center mb-5">
                <div class="cancelled-icon mb-4">
                    <i class="fas fa-ban text-warning" style="font-size: 5rem;"></i>
                </div>
                <h1 class="h2 text-warning mb-3">Payment Cancelled</h1>
                <p class="lead text-muted">Your payment was cancelled. No charges were made to your account.</p>
            </div>

            <!-- Cancellation Details Card -->
            <div class="card mb-4">
                <div class="card-header bg-warning text-dark">
                    <h5 class="mb-0">
                        <i class="fas fa-info-circle me-2"></i>Cancellation Details
                    </h5>
                </div>
                <div class="card-body">
                    <div class="alert alert-info">
                        <h6><i class="fas fa-info-circle me-2"></i>What happened?</h6>
                        <p class="mb-0">You cancelled the payment process before it was completed. Your booking is still pending and you can complete the payment at any time.</p>
                    </div>
                    
                    <div class="row mt-4">
                        <div class="col-md-6">
                            <h6 class="mb-3">Payment Reference</h6>
                            <p class="text-muted">Payment ID: {{ $payment_id ?? 'N/A' }}</p>
                        </div>
                        <div class="col-md-6">
                            <h6 class="mb-3">Next Steps</h6>
                            <p class="text-muted">You can retry the payment or contact support for assistance.</p>
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
                    <a href="{{ route('contact') ?? '#' }}" class="btn btn-primary w-100">
                        <i class="fas fa-headset me-2"></i>Contact Support
                    </a>
                </div>
            </div>

            <!-- Help Section -->
            <div class="card mt-4">
                <div class="card-body">
                    <h6 class="mb-3">
                        <i class="fas fa-question-circle me-2"></i>Need Help?
                    </h6>
                    <p class="mb-3">If you have any questions about your booking or payment, our support team is here to help.</p>
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









