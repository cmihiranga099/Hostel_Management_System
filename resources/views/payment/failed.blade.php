@extends('layouts.app')

@section('title', 'Payment Failed - University Hostel Management')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <!-- Failed Header -->
            <div class="text-center mb-5">
                <div class="failed-icon mb-4">
                    <i class="fas fa-times-circle text-danger" style="font-size: 5rem;"></i>
                </div>
                <h1 class="h2 text-danger mb-3">Payment Failed</h1>
                <p class="lead text-muted">We're sorry, but your payment could not be processed.</p>
            </div>

            <!-- Error Details Card -->
            <div class="card mb-4">
                <div class="card-header bg-danger text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-exclamation-triangle me-2"></i>Payment Error Details
                    </h5>
                </div>
                <div class="card-body">
                    <div class="alert alert-warning">
                        <h6><i class="fas fa-info-circle me-2"></i>What happened?</h6>
                        <p class="mb-0">Your payment was not completed successfully. This could be due to:</p>
                        <ul class="mb-0 mt-2">
                            <li>Insufficient funds on your card</li>
                            <li>Card declined by your bank</li>
                            <li>Invalid card information</li>
                            <li>Network connectivity issues</li>
                        </ul>
                    </div>
                    
                    <div class="row mt-4">
                        <div class="col-md-6">
                            <h6 class="mb-3">Payment Reference</h6>
                            <p class="text-muted">Payment ID: {{ $payment_id ?? 'N/A' }}</p>
                        </div>
                        <div class="col-md-6">
                            <h6 class="mb-3">Next Steps</h6>
                            <p class="text-muted">Please try again or contact support if the problem persists.</p>
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
                    <p class="mb-3">If you continue to experience issues, our support team is here to help.</p>
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









