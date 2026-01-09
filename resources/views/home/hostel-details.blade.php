@extends('layouts.app')

@section('title', $hostel->name . ' - University Hostel Management System')

@section('content')
<div class="container py-5">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('hostels') }}">Hostels</a></li>
            <li class="breadcrumb-item active">{{ $hostel->name }}</li>
        </ol>
    </nav>

    <div class="row">
        <!-- Main Content -->
        <div class="col-lg-8 mb-4">
            <!-- Hostel Header -->
            <div class="card-custom mb-4">
                <div class="card-body-custom">
                    <div class="row">
                        <div class="col-md-6">
                            <img src="{{ $hostel->image_url }}" alt="{{ $hostel->name }}" 
                                 class="img-fluid rounded shadow">
                        </div>
                        <div class="col-md-6">
                            <span class="badge bg-primary mb-2">{{ $hostel->type_display }}</span>
                            <h1 class="h3 fw-bold mb-3">{{ $hostel->name }}</h1>
                            
                            <!-- Rating -->
                            <div class="mb-3">
                                @php $rating = $hostel->getAverageRating(); @endphp
                                @for($i = 1; $i <= 5; $i++)
                                    @if($i <= $rating)
                                        <i class="fas fa-star text-warning"></i>
                                    @else
                                        <i class="far fa-star text-muted"></i>
                                    @endif
                                @endfor
                                <span class="ms-2">{{ number_format($rating, 1) }} ({{ $hostel->getTotalReviews() }} reviews)</span>
                            </div>
                            
                            <div class="price-info mb-3">
                                <h3 class="text-primary mb-1">{{ $hostel->formatted_price }}</h3>
                                <p class="text-muted">per {{ $hostel->duration }}</p>
                            </div>
                            
                            <div class="availability-info mb-3">
                                <p class="mb-1">
                                    <i class="fas fa-users text-primary me-2"></i>
                                    <strong>{{ $hostel->available_slots }}</strong> slots available out of {{ $hostel->capacity }}
                                </p>
                                <div class="progress" style="height: 10px;">
                                    <div class="progress-bar bg-success" style="width: {{ ($hostel->available_slots / $hostel->capacity) * 100 }}%"></div>
                                </div>
                            </div>
                            
                            @auth
                                @if($hostel->available_slots > 0)
                                    <button type="button" class="btn btn-primary-custom btn-lg book-hostel-btn" 
                                            data-hostel-id="{{ $hostel->id }}" 
                                            data-hostel-name="{{ $hostel->name }}" 
                                            data-hostel-price="{{ $hostel->price }}"
                                            data-bs-toggle="modal" 
                                            data-bs-target="#hostelBookingModal">
                                        <i class="fas fa-calendar-plus me-2"></i>Book This Hostel
                                    </button>
                                @else
                                    <button class="btn btn-secondary btn-lg" disabled>
                                        <i class="fas fa-times me-2"></i>Fully Booked
                                    </button>
                                @endif
                            @else
                                <a href="{{ route('login') }}" class="btn btn-primary-custom btn-lg">
                                    <i class="fas fa-sign-in-alt me-2"></i>Login to Book
                                </a>
                            @endauth
                        </div>
                    </div>
                </div>
            </div>

            <!-- Description -->
            <div class="card-custom mb-4">
                <div class="card-header-custom">
                    <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i>About This Hostel</h5>
                </div>
                <div class="card-body-custom">
                    <p>{{ $hostel->description }}</p>
                </div>
            </div>

            <!-- Facilities -->
            <div class="card-custom mb-4">
                <div class="card-header-custom">
                    <h5 class="mb-0"><i class="fas fa-star me-2"></i>Facilities & Amenities</h5>
                </div>
                <div class="card-body-custom">
                    <div class="row">
                        @foreach($hostel->facilities as $facility)
                            <div class="col-md-6 mb-2">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-check-circle text-success me-2"></i>
                                    <span>{{ $facility }}</span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Rules -->
            <div class="card-custom mb-4">
                <div class="card-header-custom">
                    <h5 class="mb-0"><i class="fas fa-list-alt me-2"></i>Hostel Rules</h5>
                </div>
                <div class="card-body-custom">
                    <div class="row">
                        @foreach($hostel->rules as $rule)
                            <div class="col-md-6 mb-2">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-exclamation-circle text-warning me-2"></i>
                                    <span>{{ $rule }}</span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Reviews Section -->
            <div class="card-custom">
                <div class="card-header-custom d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="fas fa-star me-2"></i>Student Reviews</h5>
                    @auth
                        <a href="{{ route('reviews.create', $hostel->id) }}" class="btn btn-sm btn-outline-primary">
                            <i class="fas fa-plus me-1"></i>Write Review
                        </a>
                    @endauth
                </div>
                <div class="card-body-custom">
                    @forelse($reviews as $review)
                        <div class="review-item border-bottom pb-3 mb-3">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <div>
                                    <strong>{{ $review->user->name }}</strong>
                                    <div class="rating">
                                        {!! $review->star_rating !!}
                                    </div>
                                </div>
                                <small class="text-muted">{{ $review->formatted_date }}</small>
                            </div>
                            <p class="mb-0">{{ $review->comment }}</p>
                        </div>
                    @empty
                        <div class="text-center py-4">
                            <i class="fas fa-comment text-muted" style="font-size: 3rem;"></i>
                            <h6 class="mt-3 text-muted">No reviews yet</h6>
                            <p class="text-muted">Be the first to share your experience!</p>
                        </div>
                    @endforelse

                    @if($reviews->hasPages())
                        <div class="mt-4">
                            {{ $reviews->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Quick Info -->
            <div class="card-custom mb-4">
                <div class="card-header-custom">
                    <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i>Quick Info</h5>
                </div>
                <div class="card-body-custom">
                    <div class="info-item d-flex justify-content-between mb-2">
                        <span>Type:</span>
                        <strong>{{ $hostel->type_display }}</strong>
                    </div>
                    <div class="info-item d-flex justify-content-between mb-2">
                        <span>Price:</span>
                        <strong>{{ $hostel->formatted_price }}</strong>
                    </div>
                    <div class="info-item d-flex justify-content-between mb-2">
                        <span>Duration:</span>
                        <strong>{{ ucfirst($hostel->duration) }}</strong>
                    </div>
                    <div class="info-item d-flex justify-content-between mb-2">
                        <span>Total Capacity:</span>
                        <strong>{{ $hostel->capacity }} students</strong>
                    </div>
                    <div class="info-item d-flex justify-content-between mb-2">
                        <span>Available:</span>
                        <strong class="text-success">{{ $hostel->available_slots }} slots</strong>
                    </div>
                    <div class="info-item d-flex justify-content-between">
                        <span>Status:</span>
                        <span class="badge bg-success">{{ $hostel->availability_status }}</span>
                    </div>
                </div>
            </div>

            <!-- Contact Info -->
            <div class="card-custom mb-4">
                <div class="card-header-custom">
                    <h5 class="mb-0"><i class="fas fa-phone me-2"></i>Contact Information</h5>
                </div>
                <div class="card-body-custom">
                    <div class="contact-item mb-3">
                        <i class="fas fa-phone text-primary me-2"></i>
                        <strong>Hotline:</strong> +94 11 234 5678
                    </div>
                    <div class="contact-item mb-3">
                        <i class="fas fa-envelope text-primary me-2"></i>
                        <strong>Email:</strong> bookings@universityhostel.lk
                    </div>
                    <div class="contact-item mb-3">
                        <i class="fas fa-clock text-primary me-2"></i>
                        <strong>Support:</strong> 24/7 Available
                    </div>
                    <div class="d-grid">
                        <a href="{{ route('contact') }}" class="btn btn-outline-primary">
                            <i class="fas fa-envelope me-2"></i>Contact Support
                        </a>
                    </div>
                </div>
            </div>

            <!-- Similar Hostels -->
            @if(isset($similarHostels) && $similarHostels->count() > 0)
            <div class="card-custom">
                <div class="card-header-custom">
                    <h5 class="mb-0"><i class="fas fa-building me-2"></i>Similar Hostels</h5>
                </div>
                <div class="card-body-custom">
                    @foreach($similarHostels as $similarHostel)
                        <div class="similar-hostel-item d-flex mb-3 pb-3 border-bottom">
                            <img src="{{ $similarHostel->image_url }}" alt="{{ $similarHostel->name }}" 
                                 class="rounded me-3" style="width: 60px; height: 60px; object-fit: cover;">
                            <div class="flex-grow-1">
                                <h6 class="mb-1">{{ Str::limit($similarHostel->name, 25) }}</h6>
                                <p class="text-muted mb-1 small">{{ $similarHostel->formatted_price }}</p>
                                <a href="{{ route('hostel.details', $similarHostel->id) }}" class="btn btn-sm btn-outline-primary">
                                    View Details
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .review-item:last-child {
        border-bottom: none !important;
        margin-bottom: 0 !important;
        padding-bottom: 0 !important;
    }
    
    .info-item {
        padding: 0.5rem 0;
    }
    
    .contact-item {
        display: flex;
        align-items: center;
    }
    
    .similar-hostel-item:last-child {
        border-bottom: none !important;
        margin-bottom: 0 !important;
        padding-bottom: 0 !important;
    }
    
    .progress {
        border-radius: 10px;
    }
    
    .progress-bar {
        border-radius: 10px;
    }
    
    @media (max-width: 768px) {
        .btn-lg {
            width: 100%;
            margin-top: 1rem;
        }
    }
</style>
@endpush