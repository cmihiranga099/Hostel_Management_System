@extends('layouts.app')

@section('title', 'Payment Successful - University Hostel Management')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <!-- Success Header -->
            <div class="text-center mb-5">
                <div class="success-icon mb-4">
                    <i class="fas fa-check-circle text-success" style="font-size: 5rem;"></i>
                </div>
                <h1 class="h2 text-success mb-3">Payment Successful!</h1>
                <p class="lead text-muted">Your booking has been confirmed and payment processed successfully.</p>
            </div>

            <!-- Payment Details Card -->
            <div class="card mb-4">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-receipt me-2"></i>Payment Receipt
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6 class="mb-3">Payment Information</h6>
                            <div class="payment-info">
                                <div class="d-flex justify-content-between mb-2">
                                    <span>Payment ID:</span>
                                    <strong>{{ $payment->payment_reference ?? 'PAY-DEMO001' }}</strong>
                                </div>
                                <div class="d-flex justify-content-between mb-2">
                                    <span>Transaction ID:</span>
                                    <strong>{{ $payment->transaction_id ?? 'TXN_DEMO123' }}</strong>
                                </div>
                                <div class="d-flex justify-content-between mb-2">
                                    <span>Amount Paid:</span>
                                    <strong class="text-success">LKR {{ number_format($payment->amount ?? 25000) }}</strong>
                                </div>
                                <div class="d-flex justify-content-between mb-2">
                                    <span>Payment Method:</span>
                                    <strong>{{ ucfirst($payment->payment_method ?? 'Visa') }} Card</strong>
                                </div>
                                <div class="d-flex justify-content-between mb-2">
                                    <span>Status:</span>
                                    <span class="badge bg-success">{{ ucfirst($payment->status ?? 'Completed') }}</span>
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
                                @php 
                                    $booking = $payment->booking ?? (object)[
                                        'booking_reference' => 'BK-DEMO001',
                                        'hostel' => (object)[
                                            'name' => 'University Boys Hostel - Block A',
                                            'location' => 'Colombo'
                                        ]
                                    ];
                                @endphp
                                <div class="d-flex justify-content-between mb-2">
                                    <span>Booking ID:</span>
                                    <strong>{{ $booking->booking_reference }}</strong>
                                </div>
                                <div class="d-flex justify-content-between mb-2">
                                    <span>Hostel:</span>
                                    <strong>{{ $booking->hostel->name }}</strong>
                                </div>
                                <div class="d-flex justify-content-between mb-2">
                                    <span>Location:</span>
                                    <strong>{{ $booking->hostel->location ?? 'Colombo' }}</strong>
                                </div>
                                @if(isset($booking->check_in_date))
                                <div class="d-flex justify-content-between mb-2">
                                    <span>Check-in:</span>
                                    <strong>{{ Carbon\Carbon::parse($booking->check_in_date)->format('M d, Y') }}</strong>
                                </div>
                                @endif
                                @if(isset($booking->check_out_date))
                                <div class="d-flex justify-content-between">
                                    <span>Check-out:</span>
                                    <strong>{{ Carbon\Carbon::parse($booking->check_out_date)->format('M d, Y') }}</strong>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Next Steps Card -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-list-check me-2"></i>What's Next?
                    </h5>
                </div>
                <div class="card-body">
                    <div class="next-steps">
                        <div class="step-item d-flex align-items-start mb-3">
                            <div class="step-number me-3">
                                <span class="badge bg-primary rounded-circle">1</span>
                            </div>
                            <div>
                                <h6 class="mb-1">Confirmation Email</h6>
                                <p class="text-muted mb-0">You'll receive a confirmation email with your booking details within the next few minutes.</p>
                            </div>
                        </div>
                        
                        <div class="step-item d-flex align-items-start mb-3">
                            <div class="step-number me-3">
                                <span class="badge bg-primary rounded-circle">2</span>
                            </div>
                            <div>
                                <h6 class="mb-1">Contact from Hostel</h6>
                                <p class="text-muted mb-0">The hostel management will contact you within 24 hours with check-in instructions.</p>
                            </div>
                        </div>
                        
                        <div class="step-item d-flex align-items-start mb-3">
                            <div class="step-number me-3">
                                <span class="badge bg-primary rounded-circle">3</span>
                            </div>
                            <div>
                                <h6 class="mb-1">Prepare Documents</h6>
                                <p class="text-muted mb-0">Keep your ID, student card, and admission letter ready for check-in.</p>
                            </div>
                        </div>
                        
                        <div class="step-item d-flex align-items-start">
                            <div class="step-number me-3">
                                <span class="badge bg-primary rounded-circle">4</span>
                            </div>
                            <div>
                                <h6 class="mb-1">Check-in Day</h6>
                                <p class="text-muted mb-0">Arrive at the hostel on your check-in date with all required documents.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="text-center">
                <div class="d-grid gap-2 d-md-block">
                    <a href="{{ route('student.bookings') }}" class="btn btn-primary btn-lg me-md-2">
                        <i class="fas fa-list me-2"></i>View My Bookings
                    </a>
                    
                    <a href="{{ route('hostels') }}" class="btn btn-outline-primary btn-lg me-md-2">
                        <i class="fas fa-building me-2"></i>Browse More Hostels
                    </a>
                    
                    <button onclick="window.print()" class="btn btn-outline-secondary btn-lg">
                        <i class="fas fa-print me-2"></i>Print Receipt
                    </button>
                </div>
                
                <div class="mt-4">
                    <p class="text-muted">
                        Need help? <a href="{{ route('contact') }}">Contact our support team</a>
                    </p>
                </div>
            </div>

            <!-- Important Notes -->
            <div class="alert alert-info mt-4">
                <h6 class="alert-heading">
                    <i class="fas fa-info-circle me-2"></i>Important Notes:
                </h6>
                <ul class="mb-0">
                    <li>Keep this receipt for your records</li>
                    <li>Cancellation policy: 48 hours notice required</li>
                    <li>Check-in time: 2:00 PM - 10:00 PM</li>
                    <li>For any changes to your booking, contact us immediately</li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.success-icon {
    animation: bounceIn 1s ease-in-out;
}

@keyframes bounceIn {
    0% {
        transform: scale(0.3);
        opacity: 0;
    }
    50% {
        transform: scale(1.05);
    }
    70% {
        transform: scale(0.9);
    }
    100% {
        transform: scale(1);
        opacity: 1;
    }
}

.card {
    border: none;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    border-radius: 15px;
}

.card-header {
    border-radius: 15px 15px 0 0;
}

.step-number .badge {
    width: 30px;
    height: 30px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 0.875rem;
}

.payment-info, .booking-info {
    background: #f8f9fa;
    padding: 1rem;
    border-radius: 8px;
}

.btn {
    border-radius: 8px;
    font-weight: 500;
}

@media print {
    .btn, .alert {
        display: none !important;
    }
    
    .card {
        box-shadow: none !important;
        border: 1px solid #dee2e6 !important;
    }
}

@media (max-width: 768px) {
    .success-icon i {
        font-size: 3rem !important;
    }
    
    .d-md-block .btn {
        width: 100%;
        margin-bottom: 0.5rem;
    }
}
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Auto-scroll to top
    window.scrollTo({ top: 0, behavior: 'smooth' });
    
    // Show success animation
    setTimeout(function() {
        const successIcon = document.querySelector('.success-icon i');
        if (successIcon) {
            successIcon.style.transform = 'scale(1.1)';
            setTimeout(function() {
                successIcon.style.transform = 'scale(1)';
            }, 200);
        }
    }, 500);
    
    // Confetti effect (optional - requires confetti library)
    if (typeof confetti !== 'undefined') {
        confetti({
            particleCount: 100,
            spread: 70,
            origin: { y: 0.6 }
        });
    }
});
</script>
@endpush