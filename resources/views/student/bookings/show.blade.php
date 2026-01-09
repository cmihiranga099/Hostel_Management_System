@extends('layouts.app')

@section('title', 'Booking Details - ' . ($booking->booking_reference ?? 'Booking'))

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <!-- Header -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h2 class="mb-1">
                        <i class="fas fa-file-alt text-primary me-2"></i>Booking Details
                    </h2>
                    <p class="text-muted mb-0">Reference: {{ $booking->booking_reference }}</p>
                </div>
                <div>
                    <a href="{{ route('student.bookings.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left me-2"></i>Back to Bookings
                    </a>
                </div>
            </div>

            <div class="row">
                <!-- Main Booking Details -->
                <div class="col-lg-8">
                    <!-- Booking Status Card -->
                    <div class="card mb-4 shadow-sm">
                        <div class="card-header">
                            <h5 class="mb-0">
                                <i class="fas fa-info-circle me-2"></i>Booking Status
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="small text-muted">Booking Status</label>
                                    @php
                                        $status = $booking->booking_status ?? $booking->status ?? 'pending';
                                        $badgeClass = match($status) {
                                            'confirmed' => 'bg-success',
                                            'pending' => 'bg-warning',
                                            'cancelled' => 'bg-danger',
                                            'completed' => 'bg-info',
                                            default => 'bg-secondary'
                                        };
                                    @endphp
                                    <div>
                                        <span class="badge {{ $badgeClass }} fs-6">
                                            {{ ucfirst($status) }}
                                        </span>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="small text-muted">Payment Status</label>
                                    @php
                                        $paymentStatus = $booking->payment_status ?? 'pending';
                                        $paymentBadgeClass = match($paymentStatus) {
                                            'paid', 'completed' => 'bg-success',
                                            'pending' => 'bg-warning',
                                            'failed' => 'bg-danger',
                                            'refunded' => 'bg-info',
                                            default => 'bg-secondary'
                                        };
                                    @endphp
                                    <div>
                                        <span class="badge {{ $paymentBadgeClass }} fs-6">
                                            {{ ucfirst($paymentStatus) }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                            
                            @if($paymentStatus === 'pending')
                                <div class="alert alert-warning mt-3">
                                    <i class="fas fa-exclamation-triangle me-2"></i>
                                    <strong>Payment Required:</strong> Please complete your payment to confirm this booking.
                                    <div class="mt-2">
                                        <a href="{{ route('payments.show', $booking->id) }}" class="btn btn-success btn-sm">
                                            <i class="fas fa-credit-card me-1"></i>Pay Now
                                        </a>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Hostel Information -->
                    <div class="card mb-4 shadow-sm">
                        <div class="card-header">
                            <h5 class="mb-0">
                                <i class="fas fa-building me-2"></i>Hostel Information
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-8">
                                    <h6 class="fw-bold mb-2">{{ $booking->hostel->name ?? 'University Boys Hostel - Block A' }}</h6>
                                    <p class="text-muted mb-2">
                                        <i class="fas fa-map-marker-alt me-1"></i>
                                        {{ $booking->hostel->location ?? 'Colombo' }}
                                    </p>
                                    @if(isset($booking->hostel->phone))
                                        <p class="text-muted mb-2">
                                            <i class="fas fa-phone me-1"></i>
                                            {{ $booking->hostel->phone }}
                                        </p>
                                    @endif
                                    @if(isset($booking->hostelPackage->name) || isset($booking->package))
                                        <div class="mt-3">
                                            <span class="badge bg-primary">
                                                {{ $booking->hostelPackage->name ?? $booking->package ?? 'Standard Room' }}
                                            </span>
                                        </div>
                                    @endif
                                </div>
                                <div class="col-md-4 text-end">
                                    @if(isset($booking->hostel->image_url))
                                        <img src="{{ $booking->hostel->image_url }}" alt="Hostel" class="img-fluid rounded" style="max-height: 100px;">
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Booking Dates -->
                    <div class="card mb-4 shadow-sm">
                        <div class="card-header">
                            <h5 class="mb-0">
                                <i class="fas fa-calendar me-2"></i>Stay Dates
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="row text-center">
                                <div class="col-md-4">
                                    <div class="border-end">
                                        <label class="small text-muted d-block">Check-in Date</label>
                                        <h5 class="text-success mb-0">
                                            {{ $booking->check_in_date ? \Carbon\Carbon::parse($booking->check_in_date)->format('M d, Y') : 'N/A' }}
                                        </h5>
                                        <small class="text-muted">
                                            {{ $booking->check_in_date ? \Carbon\Carbon::parse($booking->check_in_date)->format('l') : '' }}
                                        </small>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="border-end">
                                        <label class="small text-muted d-block">Check-out Date</label>
                                        <h5 class="text-danger mb-0">
                                            {{ $booking->check_out_date ? \Carbon\Carbon::parse($booking->check_out_date)->format('M d, Y') : 'N/A' }}
                                        </h5>
                                        <small class="text-muted">
                                            {{ $booking->check_out_date ? \Carbon\Carbon::parse($booking->check_out_date)->format('l') : '' }}
                                        </small>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <label class="small text-muted d-block">Duration</label>
                                    <h5 class="text-primary mb-0">
                                        {{ $booking->duration_days ?? 30 }} Days
                                    </h5>
                                    <small class="text-muted">
                                        {{ ceil(($booking->duration_days ?? 30) / 30) }} Month(s)
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Guest Information -->
                    <div class="card mb-4 shadow-sm">
                        <div class="card-header">
                            <h5 class="mb-0">
                                <i class="fas fa-user me-2"></i>Guest Information
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="small text-muted">Full Name</label>
                                    <div class="fw-bold">{{ $booking->guest_name ?? $booking->user->name ?? 'N/A' }}</div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="small text-muted">Email</label>
                                    <div>{{ $booking->guest_email ?? $booking->user->email ?? 'N/A' }}</div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="small text-muted">Phone</label>
                                    <div>{{ $booking->guest_phone ?? $booking->user->phone ?? 'N/A' }}</div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="small text-muted">Student ID</label>
                                    <div>{{ $booking->student_id ?? 'N/A' }}</div>
                                </div>
                                @if($booking->university)
                                    <div class="col-md-6 mb-3">
                                        <label class="small text-muted">University</label>
                                        <div>{{ $booking->university }}</div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Emergency Contact -->
                    @if($booking->emergency_contact_name || $booking->emergency_contact_phone)
                        <div class="card mb-4 shadow-sm">
                            <div class="card-header">
                                <h5 class="mb-0">
                                    <i class="fas fa-phone me-2"></i>Emergency Contact
                                </h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="small text-muted">Contact Name</label>
                                        <div class="fw-bold">{{ $booking->emergency_contact_name ?? 'N/A' }}</div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="small text-muted">Contact Phone</label>
                                        <div>{{ $booking->emergency_contact_phone ?? 'N/A' }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Special Requests -->
                    @if($booking->special_requests || $booking->special_requirements)
                        <div class="card mb-4 shadow-sm">
                            <div class="card-header">
                                <h5 class="mb-0">
                                    <i class="fas fa-clipboard-list me-2"></i>Special Requests
                                </h5>
                            </div>
                            <div class="card-body">
                                <p class="mb-0">{{ $booking->special_requests ?? $booking->special_requirements ?? 'None' }}</p>
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Sidebar -->
                <div class="col-lg-4">
                    <!-- Payment Summary -->
                    <div class="card mb-4 shadow-sm sticky-top" style="top: 2rem;">
                        <div class="card-header bg-light">
                            <h5 class="mb-0">
                                <i class="fas fa-receipt me-2"></i>Payment Summary
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="pricing-breakdown">
                                @if(isset($booking->subtotal))
                                    <div class="d-flex justify-content-between mb-2">
                                        <span>Accommodation:</span>
                                        <span>LKR {{ number_format($booking->subtotal) }}</span>
                                    </div>
                                @endif
                                
                                @if(isset($booking->service_fee))
                                    <div class="d-flex justify-content-between mb-2">
                                        <span>Service Fee:</span>
                                        <span>LKR {{ number_format($booking->service_fee) }}</span>
                                    </div>
                                @endif
                                
                                <div class="d-flex justify-content-between mb-2">
                                    <span>Taxes:</span>
                                    <span>Included</span>
                                </div>
                                
                                <hr>
                                
                                <div class="d-flex justify-content-between">
                                    <span class="fw-bold fs-5">Total Amount:</span>
                                    <span class="fw-bold fs-5 text-primary">
                                        {{ $booking->formatted_amount ?? 'LKR ' . number_format($booking->total_amount ?? $booking->amount ?? 0) }}
                                    </span>
                                </div>
                            </div>

                            @if($paymentStatus === 'pending')
                                <div class="d-grid mt-3">
                                    <a href="{{ route('payments.show', $booking->id) }}" class="btn btn-success">
                                        <i class="fas fa-credit-card me-2"></i>Complete Payment
                                    </a>
                                </div>
                            @elseif($paymentStatus === 'paid' || $paymentStatus === 'completed')
                                <div class="alert alert-success mt-3 mb-0">
                                    <i class="fas fa-check-circle me-2"></i>
                                    <strong>Payment Completed</strong>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Quick Actions -->
                    <div class="card shadow-sm">
                        <div class="card-header bg-light">
                            <h6 class="mb-0">
                                <i class="fas fa-tools me-2"></i>Quick Actions
                            </h6>
                        </div>
                        <div class="card-body">
                            <div class="d-grid gap-2">
                                @if($status === 'confirmed')
                                    <button class="btn btn-outline-info btn-sm" onclick="downloadBookingPDF()">
                                        <i class="fas fa-download me-1"></i>Download Booking
                                    </button>
                                @endif
                                
                                <button class="btn btn-outline-primary btn-sm" onclick="printBooking()">
                                    <i class="fas fa-print me-1"></i>Print Details
                                </button>
                                
                                @if($status === 'pending' || $status === 'confirmed')
                                    <button class="btn btn-outline-warning btn-sm" onclick="requestCancellation()">
                                        <i class="fas fa-times me-1"></i>Request Cancellation
                                    </button>
                                @endif
                                
                                <a href="{{ route('contact') }}" class="btn btn-outline-secondary btn-sm">
                                    <i class="fas fa-headset me-1"></i>Contact Support
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Important Information -->
                    <div class="card mt-3 shadow-sm">
                        <div class="card-body">
                            <h6 class="card-title">
                                <i class="fas fa-info-circle me-2 text-info"></i>Important Information
                            </h6>
                            <ul class="list-unstyled small mb-0">
                                <li class="mb-2">
                                    <i class="fas fa-check text-success me-2"></i>
                                    Booking confirmation sent to your email
                                </li>
                                <li class="mb-2">
                                    <i class="fas fa-check text-success me-2"></i>
                                    Valid ID required at check-in
                                </li>
                                <li class="mb-2">
                                    <i class="fas fa-check text-success me-2"></i>
                                    Check-in time: 2:00 PM onwards
                                </li>
                                <li class="mb-2">
                                    <i class="fas fa-check text-success me-2"></i>
                                    Check-out time: Before 12:00 PM
                                </li>
                                <li class="mb-0">
                                    <i class="fas fa-phone text-info me-2"></i>
                                    Questions? Call {{ $booking->hostel->phone ?? '+94 11 123 4567' }}
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Cancellation Modal -->
<div class="modal fade" id="cancellationModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Request Cancellation</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="alert alert-warning">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    <strong>Cancellation Policy:</strong> Cancellations must be requested at least 48 hours before check-in. 
                    Refund processing may take 5-7 business days.
                </div>
                <form id="cancellationForm">
                    <div class="mb-3">
                        <label for="cancellationReason" class="form-label">Reason for Cancellation *</label>
                        <select class="form-select" id="cancellationReason" required>
                            <option value="">Select a reason</option>
                            <option value="change_of_plans">Change of Plans</option>
                            <option value="found_alternative">Found Alternative Accommodation</option>
                            <option value="emergency">Emergency</option>
                            <option value="financial_reasons">Financial Reasons</option>
                            <option value="other">Other</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="additionalComments" class="form-label">Additional Comments</label>
                        <textarea class="form-control" id="additionalComments" rows="3" 
                                  placeholder="Please provide any additional details..."></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger" onclick="submitCancellation()">
                    Submit Request
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.card {
    border: none;
    border-radius: 15px;
}

.card-header {
    background: linear-gradient(135deg, #f8f9fa, #e9ecef);
    border-bottom: 1px solid #dee2e6;
    border-radius: 15px 15px 0 0 !important;
}

.badge {
    font-size: 0.8rem;
    padding: 0.5em 0.75em;
}

.border-end {
    border-right: 1px solid #dee2e6 !important;
}

.sticky-top {
    top: 2rem !important;
}

@media (max-width: 768px) {
    .border-end {
        border-right: none !important;
        border-bottom: 1px solid #dee2e6 !important;
        margin-bottom: 1rem;
        padding-bottom: 1rem;
    }
    
    .sticky-top {
        position: relative !important;
        top: 0 !important;
    }
}

@media print {
    .btn, .card-footer, #cancellationModal {
        display: none !important;
    }
    
    .card {
        box-shadow: none !important;
        border: 1px solid #ddd !important;
    }
}
</style>
@endpush

@push('scripts')
<script>
function printBooking() {
    window.print();
}

function downloadBookingPDF() {
    // In a real application, you would generate a PDF
    alert('PDF download feature will be implemented with a PDF generation library.');
}

function requestCancellation() {
    const modal = new bootstrap.Modal(document.getElementById('cancellationModal'));
    modal.show();
}

function submitCancellation() {
    const reason = document.getElementById('cancellationReason').value;
    const comments = document.getElementById('additionalComments').value;
    
    if (!reason) {
        alert('Please select a reason for cancellation.');
        return;
    }
    
    // In a real application, you would submit this to the server
    const formData = {
        booking_id: {{ $booking->id }},
        reason: reason,
        comments: comments,
        _token: '{{ csrf_token() }}'
    };
    
    // Simulate API call
    fetch('/api/booking-cancellation-request', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify(formData)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Cancellation request submitted successfully. You will receive an email confirmation shortly.');
            bootstrap.Modal.getInstance(document.getElementById('cancellationModal')).hide();
            location.reload();
        } else {
            alert('Error submitting cancellation request. Please try again or contact support.');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error submitting cancellation request. Please try again or contact support.');
    });
}

// Auto-refresh booking status every 30 seconds if payment is pending
@if($paymentStatus === 'pending')
setInterval(function() {
    if (document.visibilityState === 'visible') {
        fetch(window.location.href, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.text())
        .then(html => {
            // Check if payment status has changed
            if (html.includes('Payment Completed') && !document.body.innerHTML.includes('Payment Completed')) {
                location.reload();
            }
        })
        .catch(error => {
            console.log('Status check failed:', error);
        });
    }
}, 30000);
@endif

document.addEventListener('DOMContentLoaded', function() {
    console.log('Booking details page loaded');
    
    // Initialize tooltips if Bootstrap is available
    if (typeof bootstrap !== 'undefined') {
        var tooltips = document.querySelectorAll('[data-bs-toggle="tooltip"]');
        tooltips.forEach(function(tooltip) {
            new bootstrap.Tooltip(tooltip);
        });
    }
});
</script>
@endpush