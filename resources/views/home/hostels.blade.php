@extends('layouts.app')

@section('title', 'Available Hostels - University Hostel Management System')

@section('content')
<div class="container py-5">
    <!-- Page Header -->
    <div class="row mb-5">
        <div class="col-12">
            <div class="text-center">
                <h1 class="display-4 fw-bold text-primary mb-3">Available Hostels</h1>
                <p class="lead text-muted">Find the perfect accommodation for your university journey</p>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card-custom">
                <div class="card-body-custom">
                    <form method="GET" action="{{ route('hostels') }}" class="row g-3">
                        <!-- Search -->
                        <div class="col-md-3">
                            <label for="search" class="form-label-custom">Search</label>
                            <input type="text" class="form-control form-control-custom" id="search" name="search" 
                                   value="{{ request('search') }}" placeholder="Search hostels...">
                        </div>
                        
                        <!-- Type Filter -->
                        <div class="col-md-2">
                            <label for="type" class="form-label-custom">Type</label>
                            <select class="form-control form-control-custom" id="type" name="type">
                                <option value="">All Types</option>
                                <option value="boys" {{ request('type') == 'boys' ? 'selected' : '' }}>Boys Hostel</option>
                                <option value="girls" {{ request('type') == 'girls' ? 'selected' : '' }}>Girls Hostel</option>
                            </select>
                        </div>
                        
                        <!-- Price Range -->
                        <div class="col-md-2">
                            <label for="min_price" class="form-label-custom">Min Price</label>
                            <input type="number" class="form-control form-control-custom" id="min_price" name="min_price" 
                                   value="{{ request('min_price') }}" placeholder="0">
                        </div>
                        
                        <div class="col-md-2">
                            <label for="max_price" class="form-label-custom">Max Price</label>
                            <input type="number" class="form-control form-control-custom" id="max_price" name="max_price" 
                                   value="{{ request('max_price') }}" placeholder="100000">
                        </div>
                        
                        <!-- Sort -->
                        <div class="col-md-2">
                            <label for="sort" class="form-label-custom">Sort By</label>
                            <select class="form-control form-control-custom" id="sort" name="sort">
                                <option value="name" {{ request('sort') == 'name' ? 'selected' : '' }}>Name</option>
                                <option value="price" {{ request('sort') == 'price' ? 'selected' : '' }}>Price</option>
                                <option value="created_at" {{ request('sort') == 'created_at' ? 'selected' : '' }}>Newest</option>
                            </select>
                        </div>
                        
                        <!-- Filter Button -->
                        <div class="col-md-1 d-flex align-items-end">
                            <button type="submit" class="btn btn-primary-custom w-100">
                                <i class="fas fa-filter"></i>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Results Count -->
    <div class="row mb-3">
        <div class="col-12">
            <p class="text-muted">
                Showing {{ $hostels->count() }} of {{ $hostels->total() }} hostels
                @if(request('search'))
                    for "<strong>{{ request('search') }}</strong>"
                @endif
                @if(request('type'))
                    in <strong>{{ ucfirst(request('type')) }} Hostels</strong>
                @endif
            </p>
        </div>
    </div>

    <!-- Hostels Grid -->
    <div class="row">
        @forelse($hostels as $hostel)
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="hostel-package-card {{ $hostel->type }}-hostel">
                    <!-- Hostel Image -->
                    <div class="hostel-image">
                        <img src="{{ $hostel->image_url }}" alt="{{ $hostel->name }}" 
                             class="img-fluid" style="width: 100%; height: 200px; object-fit: cover;">
                        <div class="hostel-badge">
                            <span class="badge bg-primary">{{ $hostel->type_display }}</span>
                        </div>
                    </div>
                    
                    <div class="package-header">
                        <h4 class="package-title">{{ $hostel->name }}</h4>
                        <div class="package-price">{{ $hostel->formatted_price }}</div>
                        <div class="package-duration">per {{ $hostel->duration }}</div>
                        
                        <!-- Rating -->
                        <div class="hostel-rating mt-2">
                            @php $rating = $hostel->getAverageRating(); @endphp
                            @for($i = 1; $i <= 5; $i++)
                                @if($i <= $rating)
                                    <i class="fas fa-star text-warning"></i>
                                @else
                                    <i class="far fa-star text-muted"></i>
                                @endif
                            @endfor
                            <span class="ms-2 text-muted">({{ $hostel->getTotalReviews() }} reviews)</span>
                        </div>
                    </div>
                    
                    <div class="package-features">
                        @foreach(array_slice($hostel->facilities, 0, 4) as $facility)
                            <div class="feature-item">
                                <i class="fas fa-check feature-icon"></i>
                                {{ $facility }}
                            </div>
                        @endforeach
                        
                        @if(count($hostel->facilities) > 4)
                            <div class="feature-item">
                                <i class="fas fa-plus feature-icon"></i>
                                {{ count($hostel->facilities) - 4 }} more facilities
                            </div>
                        @endif
                        
                        <div class="feature-item">
                            <i class="fas fa-users feature-icon"></i>
                            {{ $hostel->available_slots }} slots available
                        </div>
                    </div>
                    
                    <div class="p-3">
                        <div class="d-grid gap-2">
                            <a href="{{ route('hostel.details', $hostel->id) }}" class="btn btn-outline-primary">
                                <i class="fas fa-eye me-2"></i>View Details
                            </a>
                            @auth
                                @if($hostel->available_slots > 0)
                                    <button type="button" class="btn btn-primary-custom book-hostel-btn" 
                                            data-hostel-id="{{ $hostel->id }}" 
                                            data-hostel-name="{{ $hostel->name }}" 
                                            data-hostel-price="{{ $hostel->price }}"
                                            data-bs-toggle="modal" 
                                            data-bs-target="#hostelBookingModal">
                                        <i class="fas fa-calendar-plus me-2"></i>Book Now
                                    </button>
                                @else
                                    <button class="btn btn-secondary" disabled>
                                        <i class="fas fa-times me-2"></i>Fully Booked
                                    </button>
                                @endif
                            @else
                                <a href="{{ route('login') }}" class="btn btn-primary-custom">
                                    <i class="fas fa-sign-in-alt me-2"></i>Login to Book
                                </a>
                            @endauth
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="text-center py-5">
                    <i class="fas fa-search text-muted" style="font-size: 4rem;"></i>
                    <h4 class="mt-3 text-muted">No hostels found</h4>
                    <p class="text-muted">Try adjusting your search criteria or browse all hostels.</p>
                    <a href="{{ route('hostels') }}" class="btn btn-primary-custom">
                        <i class="fas fa-refresh me-2"></i>View All Hostels
                    </a>
                </div>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($hostels->hasPages())
    <div class="row mt-5">
        <div class="col-12">
            <div class="d-flex justify-content-center">
                {{ $hostels->withQueryString()->links() }}
            </div>
        </div>
    </div>
    @endif
</div>
@endsection

@push('styles')
<style>
    .hostel-image {
        position: relative;
        overflow: hidden;
        border-radius: 15px 15px 0 0;
    }
    
    .hostel-badge {
        position: absolute;
        top: 10px;
        right: 10px;
    }
    
    .hostel-rating {
        font-size: 0.9rem;
    }
    
    .boys-hostel {
        border-left: 5px solid var(--primary-blue);
    }
    
    .girls-hostel {
        border-left: 5px solid var(--primary-orange);
    }
</style>
@endpush