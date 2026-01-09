@extends('layouts.app')

@section('title', 'My Bookings - University Hostel Management')

@section('content')
<div class="container py-5">
    <div class="row">
        <div class="col-12">
            <!-- Header -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h2 class="mb-1">
                        <i class="fas fa-calendar-check text-primary me-2"></i>My Bookings
                    </h2>
                    <p class="text-muted mb-0">Manage your hostel bookings and reservations</p>
                </div>
                <div>
                    <a href="{{ route('hostels') }}" class="btn btn-primary">
                        <i class="fas fa-plus me-2"></i>New Booking
                    </a>
                </div>
            </div>

            <!-- Success/Error Messages -->
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <!-- Bookings List -->
            @if($bookings && $bookings->count() > 0)
                <div class="row">
                    @foreach($bookings as $booking)
                        <div class="col-lg-6 col-xl-4 mb-4">
                            <div class="card booking-card h-100 shadow-sm">
                                <div class="card-header d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="mb-0 fw-bold">{{ $booking->booking_reference }}</h6>
                                        <small class="text-muted">
                                            {{ $booking->created_at ? $booking->created_at->format('M d, Y') : 'Recent' }}
                                        </small>
                                    </div>
                                    <div>
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
                                        <span class="badge {{ $badgeClass }}">
                                            {{ ucfirst($status) }}
                                        </span>
                                    </div>
                                </div>
                                
                                <div class="card-body">
                                    <!-- Hostel Info -->
                                    <div class="mb-3">
                                        <h6 class="card-title mb-2">
                                            <i class="fas fa-building text-primary me-2"></i>
                                            {{ $booking->hostel->name ?? 'University Boys Hostel - Block A' }}
                                        </h6>
                                        <p class="text-muted small mb-0">
                                            <i class="fas fa-map-marker-alt me-1"></i>
                                            {{ $booking->hostel->location ?? 'Colombo' }}
                                        </p>
                                    </div>

                                    <!-- Package Info -->
                                    @if(isset($booking->hostelPackage->name) || isset($booking->package))
                                        <div class="mb-3">
                                            <span class="badge bg-light text-dark">
                                                {{ $booking->hostelPackage->name ?? $booking->package ?? 'Standard Room' }}
                                            </span>
                                        </div>
                                    @endif

                                    <!-- Dates -->
                                    <div class="mb-3">
                                        <div class="row text-center">
                                            <div class="col-6">
                                                <div class="border-end">
                                                    <small class="text-muted d-block">Check-in</small>
                                                    <strong class="text-success">
                                                        {{ $booking->check_in_date ? \Carbon\Carbon::parse($booking->check_in_date)->format('M d') : 'N/A' }}
                                                    </strong>
                                                </div>
                                            </div>
                                            <div class="col-6">
                                                <small class="text-muted d-block">Check-out</small>
                                                <strong class="text-danger">
                                                    {{ $booking->check_out_date ? \Carbon\Carbon::parse($booking->check_out_date)->format('M d') : 'N/A' }}
                                                </strong>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Amount -->
                                    <div class="mb-3">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <span class="text-muted">Total Amount:</span>
                                            <strong class="text-primary fs-5">
                                                {{ $booking->formatted_amount ?? 'LKR ' . number_format($booking->total_amount ?? $booking->amount ?? 0) }}
                                            </strong>
                                        </div>
                                    </div>

                                    <!-- Payment Status -->
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
                                    <div class="mb-3">
                                        <small class="text-muted">Payment Status:</small>
                                        <span class="badge {{ $paymentBadgeClass }} ms-2">
                                            {{ ucfirst($paymentStatus) }}
                                        </span>
                                    </div>
                                </div>

                                <div class="card-footer bg-light">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            @if($paymentStatus === 'pending')
                                                <a href="{{ route('payments.show', $booking->id) }}" class="btn btn-sm btn-success">
                                                    <i class="fas fa-credit-card me-1"></i>Pay Now
                                                </a>
                                            @endif
                                        </div>
                                        <div>
                                            <a href="{{ route('student.bookings.show', $booking->id) }}" class="btn btn-sm btn-outline-primary">
                                                <i class="fas fa-eye me-1"></i>View Details
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                @if(method_exists($bookings, 'links'))
                    <div class="d-flex justify-content-center mt-4">
                        {{ $bookings->links() }}
                    </div>
                @endif

            @else
                <!-- No Bookings State -->
                <div class="text-center py-5">
                    <div class="mb-4">
                        <i class="fas fa-calendar-times text-muted" style="font-size: 4rem;"></i>
                    </div>
                    <h4 class="text-muted mb-3">No Bookings Found</h4>
                    <p class="text-muted mb-4">You haven't made any hostel bookings yet. Start by browsing available hostels.</p>
                    <a href="{{ route('hostels') }}" class="btn btn-primary btn-lg">
                        <i class="fas fa-building me-2"></i>Browse Hostels
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.booking-card {
    transition: all 0.3s ease;
    border: none;
    border-radius: 15px;
}

.booking-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 25px rgba(0,0,0,0.1) !important;
}

.card-header {
    background: linear-gradient(135deg, #f8f9fa, #e9ecef);
    border-bottom: 1px solid #dee2e6;
    border-radius: 15px 15px 0 0 !important;
}

.badge {
    font-size: 0.75rem;
    padding: 0.5em 0.75em;
}

.btn-sm {
    font-size: 0.875rem;
    padding: 0.375rem 0.75rem;
}

.text-primary {
    color: #0d6efd !important;
}

.border-end {
    border-right: 1px solid #dee2e6 !important;
}

@media (max-width: 768px) {
    .booking-card {
        margin-bottom: 1rem;
    }
    
    .card-footer .d-flex {
        flex-direction: column;
        gap: 0.5rem;
    }
    
    .card-footer .btn {
        width: 100%;
    }
}
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Add any interactive features here
    
    // Example: Auto-refresh bookings every 30 seconds
    // setInterval(function() {
    //     if (document.visibilityState === 'visible') {
    //         window.location.reload();
    //     }
    // }, 30000);
    
    console.log('Bookings page loaded');
});
</script>
@endpush