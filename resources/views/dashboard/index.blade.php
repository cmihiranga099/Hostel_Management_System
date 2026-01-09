@extends('layouts.app')

@section('title', 'Dashboard - University Hostel Management System')

@section('content')
<div class="container py-4">
    <!-- Welcome Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card-custom">
                <div class="card-body-custom">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <h2 class="mb-2">Welcome back, {{ Auth::user()->name }}! 👋</h2>
                            <p class="text-muted mb-0">
                                Here's what's happening with your hostel bookings today.
                            </p>
                        </div>
                        <div class="col-md-4 text-md-end">
                            <img src="{{ Auth::user()->profile_image_url }}" alt="Profile" class="rounded-circle" style="width: 80px; height: 80px; object-fit: cover;">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="dashboard-card">
                <div class="card-body text-center p-4">
                    <div class="stat-icon mb-3">
                        <i class="fas fa-calendar-check text-primary" style="font-size: 2.5rem;"></i>
                    </div>
                    <h3 class="stat-number text-primary">{{ $stats['total_bookings'] }}</h3>
                    <p class="stat-label mb-0">Total Bookings</p>
                </div>
            </div>
        </div>
        
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="dashboard-card">
                <div class="card-body text-center p-4">
                    <div class="stat-icon mb-3">
                        <i class="fas fa-bed text-success" style="font-size: 2.5rem;"></i>
                    </div>
                    <h3 class="stat-number text-success">{{ $stats['active_bookings'] }}</h3>
                    <p class="stat-label mb-0">Active Bookings</p>
                </div>
            </div>
        </div>
        
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="dashboard-card">
                <div class="card-body text-center p-4">
                    <div class="stat-icon mb-3">
                        <i class="fas fa-rupee-sign text-warning" style="font-size: 2.5rem;"></i>
                    </div>
                    <h3 class="stat-number text-warning">{{ number_format($stats['total_payments']) }}</h3>
                    <p class="stat-label mb-0">Total Paid (LKR)</p>
                </div>
            </div>
        </div>
        
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="dashboard-card">
                <div class="card-body text-center p-4">
                    <div class="stat-icon mb-3">
                        <i class="fas fa-star text-info" style="font-size: 2.5rem;"></i>
                    </div>
                    <h3 class="stat-number text-info">{{ $stats['total_reviews'] }}</h3>
                    <p class="stat-label mb-0">Reviews Given</p>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Recent Bookings -->
        <div class="col-lg-8 mb-4">
            <div class="card-custom">
                <div class="card-header-custom d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="fas fa-calendar-alt me-2"></i>Recent Bookings
                    </h5>
                    <a href="{{ route('dashboard.bookings') }}" class="btn btn-sm btn-outline-primary">
                        View All
                    </a>
                </div>
                <div class="card-body-custom">
                    @forelse($bookings as $booking)
                        <div class="booking-item d-flex justify-content-between align-items-center p-3 border-bottom">
                            <div class="booking-info">
                                <h6 class="mb-1">{{ $booking->hostelPackage->name }}</h6>
                                <p class="text-muted mb-1">
                                    <i class="fas fa-calendar me-1"></i>
                                    {{ $booking->check_in_date->format('M d, Y') }} - {{ $booking->check_out_date->format('M d, Y') }}
                                </p>
                                <p class="mb-0">
                                    <small class="text-muted">Booking #{{ $booking->booking_reference }}</small>
                                </p>
                            </div>
                            <div class="booking-status text-end">
                                <div class="mb-2">
                                    {!! $booking->status_badge !!}
                                </div>
                                <div class="mb-2">
                                    {!! $booking->payment_status_badge !!}
                                </div>
                                <p class="mb-0 fw-bold">{{ $booking->formatted_amount }}</p>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-4">
                            <i class="fas fa-calendar-times text-muted" style="font-size: 3rem;"></i>
                            <h6 class="mt-3 text-muted">No bookings yet</h6>
                            <p class="text-muted">Start by browsing available hostels</p>
                            <a href="{{ route('hostels') }}" class="btn btn-primary-custom">
                                <i class="fas fa-search me-2"></i>Browse Hostels
                            </a>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Quick Actions & Available Hostels -->
        <div class="col-lg-4">
            <!-- Quick Actions -->
            <div class="card-custom mb-4">
                <div class="card-header-custom">
                    <h5 class="mb-0">
                        <i class="fas fa-bolt me-2"></i>Quick Actions
                    </h5>
                </div>
                <div class="card-body-custom">
                    <div class="d-grid gap-2">
                        <a href="{{ route('hostels') }}" class="btn btn-primary-custom">
                            <i class="fas fa-search me-2"></i>Browse Hostels
                        </a>
                        <a href="{{ route('dashboard.bookings') }}" class="btn btn-outline-primary">
                            <i class="fas fa-calendar-check me-2"></i>My Bookings
                        </a>
                        <a href="{{ route('dashboard.profile') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-user me-2"></i>Update Profile
                        </a>
                    </div>
                </div>
            </div>

            <!-- Available Hostels -->
            <div class="card-custom">
                <div class="card-header-custom">
                    <h5 class="mb-0">
                        <i class="fas fa-building me-2"></i>Available Hostels
                    </h5>
                </div>
                <div class="card-body-custom">
                    @forelse($availableHostels as $hostel)
                        <div class="hostel-quick-item d-flex justify-content-between align-items-center p-2 border-bottom">
                            <div class="hostel-info">
                                <h6 class="mb-1">{{ Str::limit($hostel->name, 20) }}</h6>
                                <p class="text-muted mb-0">
                                    <small>{{ $hostel->formatted_price }}</small>
                                </p>
                            </div>
                            <div>
                                <a href="{{ route('hostel.details', $hostel->id) }}" class="btn btn-sm btn-outline-primary">
                                    View
                                </a>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-3">
                            <i class="fas fa-building text-muted" style="font-size: 2rem;"></i>
                            <p class="text-muted mb-0 mt-2">No hostels available</p>
                        </div>
                    @endforelse
                    
                    @if($availableHostels->count() > 0)
                        <div class="text-center mt-3">
                            <a href="{{ route('hostels') }}" class="btn btn-sm btn-secondary-custom">
                                View All Hostels
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Reviews -->
    @if($reviews->count() > 0)
    <div class="row mt-4">
        <div class="col-12">
            <div class="card-custom">
                <div class="card-header-custom d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="fas fa-star me-2"></i>My Recent Reviews
                    </h5>
                    <a href="{{ route('dashboard.reviews') }}" class="btn btn-sm btn-outline-primary">
                        View All
                    </a>
                </div>
                <div class="card-body-custom">
                    <div class="row">
                        @foreach($reviews as $review)
                            <div class="col-md-6 mb-3">
                                <div class="review-card">
                                    <div class="review-rating">
                                        {!! $review->star_rating !!}
                                    </div>
                                    <h6 class="review-hostel">{{ $review->hostelPackage->name }}</h6>
                                    <p class="review-text">{{ Str::limit($review->comment, 100) }}</p>
                                    <small class="text-muted">{{ $review->formatted_date }}</small>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Profile Completion Notice -->
    @if(!Auth::user()->nic || !Auth::user()->university)
    <div class="row mt-4">
        <div class="col-12">
            <div class="alert alert-warning d-flex align-items-center" role="alert">
                <i class="fas fa-exclamation-triangle me-3" style="font-size: 1.5rem;"></i>
                <div>
                    <h6 class="alert-heading mb-1">Complete Your Profile</h6>
                    <p class="mb-2">Please complete your profile information to make booking easier.</p>
                    <a href="{{ route('dashboard.profile') }}" class="btn btn-sm btn-warning">
                        <i class="fas fa-user-edit me-1"></i>Complete Profile
                    </a>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
@endsection

@push('styles')
<style>
    .stat-icon {
        opacity: 0.8;
    }
    
    .stat-number {
        font-size: 2rem;
        font-weight: 700;
        margin-bottom: 0.5rem;
    }
    
    .stat-label {
        font-size: 0.9rem;
        font-weight: 500;
        color: #6c757d;
    }
    
    .booking-item:last-child {
        border-bottom: none !important;
    }
    
    .hostel-quick-item:last-child {
        border-bottom: none !important;
    }
    
    .booking-item:hover,
    .hostel-quick-item:hover {
        background-color: #f8f9fa;
        border-radius: 8px;
    }
    
    @media (max-width: 768px) {
        .stat-number {
            font-size: 1.5rem;
        }
        
        .booking-item {
            flex-direction: column;
            align-items: flex-start !important;
        }
        
        .booking-status {
            text-align: left !important;
            margin-top: 1rem;
        }
    }
</style>
@endpush