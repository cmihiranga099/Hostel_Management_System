@extends('layouts.app')

@section('title', 'Available Hostels - University Hostel Management System')

@section('content')
<!-- Hero Section -->
<section class="hero-section" style="min-height: 50vh;">
    <div class="container">
        <div class="row align-items-center text-center">
            <div class="col-12">
                <h1 class="hero-title">Find Your Perfect Hostel</h1>
                <p class="hero-subtitle">Discover comfortable and affordable accommodation for your university journey</p>
            </div>
        </div>
    </div>
</section>

<!-- Search & Filter Section -->
<section class="py-4 bg-light">
    <div class="container">
        <form method="GET" action="{{ route('hostels') }}" class="row g-3" id="searchForm">
            <div class="col-md-4">
                <input type="text" class="form-control" name="search" placeholder="Search hostels..." 
                       value="{{ request('search') }}">
            </div>
            <div class="col-md-2">
                <select class="form-control" name="type">
                    <option value="">All Types</option>
                    <option value="boys" {{ request('type') == 'boys' ? 'selected' : '' }}>Boys Hostels</option>
                    <option value="girls" {{ request('type') == 'girls' ? 'selected' : '' }}>Girls Hostels</option>
                    <option value="mixed" {{ request('type') == 'mixed' ? 'selected' : '' }}>Mixed Hostels</option>
                </select>
            </div>
            <div class="col-md-2">
                <select class="form-control" name="location">
                    <option value="">All Locations</option>
                    <option value="Colombo" {{ request('location') == 'Colombo' ? 'selected' : '' }}>Colombo</option>
                    <option value="Kelaniya" {{ request('location') == 'Kelaniya' ? 'selected' : '' }}>Kelaniya</option>
                    <option value="Pitipana" {{ request('location') == 'Pitipana' ? 'selected' : '' }}>Pitipana</option>
                    <option value="Malabe" {{ request('location') == 'Malabe' ? 'selected' : '' }}>Malabe</option>
                    <option value="Kandy" {{ request('location') == 'Kandy' ? 'selected' : '' }}>Kandy</option>
                    <option value="Galle" {{ request('location') == 'Galle' ? 'selected' : '' }}>Galle</option>
                </select>
            </div>
            <div class="col-md-2">
                <input type="number" class="form-control" name="max_price" placeholder="Max Price" 
                       value="{{ request('max_price') }}">
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary w-100">
                    <i class="fas fa-search me-2"></i>Search
                </button>
            </div>
        </form>
    </div>
</section>

<!-- Hostels Listing Section -->
<section class="py-5">
    <div class="container">
        <div class="row mb-4">
            <div class="col-md-6">
                <h3>Available Hostels</h3>
                @php
                    $totalHostels = $hostels instanceof \Illuminate\Pagination\LengthAwarePaginator 
                        ? $hostels->total() 
                        : $hostels->count();
                @endphp
                <p class="text-muted">{{ $totalHostels }} hostels found</p>
            </div>
            <div class="col-md-6 text-end">
                <form method="GET" action="{{ route('hostels') }}" class="d-inline">
                    @foreach(request()->except(['sort', 'order']) as $key => $value)
                        <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                    @endforeach
                    <select name="sort" class="form-select d-inline-block w-auto" onchange="this.form.submit()">
                        <option value="name" {{ request('sort') == 'name' ? 'selected' : '' }}>Sort by Name</option>
                        <option value="price" {{ request('sort') == 'price' ? 'selected' : '' }}>Sort by Price</option>
                        <option value="rating" {{ request('sort') == 'rating' ? 'selected' : '' }}>Sort by Rating</option>
                        <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>Newest First</option>
                    </select>
                </form>
            </div>
        </div>

        <div class="row">
            @forelse($hostels as $hostel)
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="card hostel-card h-100">
                        <!-- Hostel Image -->
                        <div class="position-relative">
                            <img src="{{ $hostel->image_url ?? asset('images/default-hostel.jpg') }}" 
                                 class="card-img-top" alt="{{ $hostel->name }}" style="height: 200px; object-fit: cover;">
                            
                            <!-- Type Badge -->
                            <span class="position-absolute top-0 end-0 m-2">
                                @if($hostel->type === 'boys')
                                    <span class="badge bg-primary">BOYS</span>
                                @elseif($hostel->type === 'girls')
                                    <span class="badge bg-warning text-dark">GIRLS</span>
                                @else
                                    <span class="badge bg-success">MIXED</span>
                                @endif
                            </span>
                        </div>
                        
                        <div class="card-body d-flex flex-column">
                            <!-- Hostel Title -->
                            <h5 class="card-title">{{ $hostel->name }}</h5>
                            
                            <!-- Location -->
                            <p class="text-muted mb-2">
                                <i class="fas fa-map-marker-alt me-1"></i>
                                {{ $hostel->location ?? $hostel->city ?? 'Location not specified' }}
                            </p>
                            
                            <!-- Description -->
                            <p class="card-text">{{ Str::limit($hostel->description ?? 'Modern hostel with great facilities.', 80) }}</p>
                            
                            <!-- Rating -->
                            <div class="mb-2">
                                @php 
                                    $rating = $hostel->reviews_avg_rating ?? $hostel->average_rating ?? 4.0;
                                    $reviewCount = $hostel->reviews_count ?? $hostel->total_reviews ?? 25;
                                @endphp
                                <div class="d-flex align-items-center">
                                    @for($i = 1; $i <= 5; $i++)
                                        @if($i <= $rating)
                                            <i class="fas fa-star text-warning"></i>
                                        @else
                                            <i class="far fa-star text-muted"></i>
                                        @endif
                                    @endfor
                                    <span class="ms-2 text-muted">({{ $reviewCount }})</span>
                                </div>
                            </div>
                            
                            <!-- Price -->
                            <h4 class="text-primary mb-3">
                                {{ $hostel->formatted_price ?? 'LKR ' . number_format($hostel->price ?? 22000) }}
                                <small class="text-muted">/month</small>
                            </h4>
                            
                            <!-- Available Slots -->
                            @php 
                                $availableSlots = $hostel->available_slots ?? 18;
                                $totalCapacity = $hostel->capacity ?? 50;
                            @endphp
                            <p class="text-muted mb-3">
                                Available Slots: 
                                <strong class="{{ $availableSlots > 0 ? 'text-success' : 'text-danger' }}">
                                    {{ $availableSlots > 0 ? $availableSlots : 'Full' }}
                                </strong>
                            </p>
                            
                            <!-- Facilities -->
                            <div class="mb-3">
                                @php 
                                    $facilities = ['WI-FI', 'STUDY ROOM', 'TRANSPORT', 'CAFETERIA'];
                                    if(isset($hostel->facilities)) {
                                        if(is_string($hostel->facilities)) {
                                            $facilities = explode(',', $hostel->facilities);
                                        } elseif(is_array($hostel->facilities)) {
                                            $facilities = $hostel->facilities;
                                        }
                                    }
                                @endphp
                                @foreach(array_slice($facilities, 0, 3) as $facility)
                                    <span class="badge bg-light text-dark me-1">{{ strtoupper(trim($facility)) }}</span>
                                @endforeach
                                @if(count($facilities) > 3)
                                    <span class="badge bg-secondary">+{{ count($facilities) - 3 }} MORE</span>
                                @endif
                            </div>
                            
                            <!-- Action Buttons -->
                            <div class="mt-auto">
                                <div class="d-grid gap-2">
                                    <!-- View Details Button -->
                                    <a href="{{ route('hostels.show', $hostel->id) }}" class="btn btn-outline-primary">
                                        <i class="fas fa-eye me-2"></i>View Details
                                    </a>
                                    
                                    <!-- Book Now Button - WORKING VERSION -->
                                    @if($availableSlots > 0)
                                        @auth
                                            <a href="{{ route('hostels.book', $hostel->id) }}" class="btn btn-primary book-now-btn" 
                                               data-hostel-id="{{ $hostel->id }}" data-hostel-name="{{ $hostel->name }}">
                                                <i class="fas fa-calendar-plus me-2"></i>📅 Book Now
                                            </a>
                                        @else
                                            <a href="{{ route('login') }}" class="btn btn-primary">
                                                <i class="fas fa-sign-in-alt me-2"></i>Login to Book
                                            </a>
                                        @endauth
                                    @else
                                        <button class="btn btn-secondary" disabled>
                                            <i class="fas fa-times me-2"></i>Fully Booked
                                        </button>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="text-center py-5">
                        <i class="fas fa-search fa-3x text-muted mb-3"></i>
                        <h4>No hostels found</h4>
                        <p class="text-muted">Try adjusting your search criteria or filters.</p>
                        <a href="{{ route('hostels') }}" class="btn btn-primary">View All Hostels</a>
                    </div>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if(method_exists($hostels, 'hasPages') && $hostels->hasPages())
            <div class="row">
                <div class="col-12 d-flex justify-content-center">
                    {{ $hostels->links() }}
                </div>
            </div>
        @endif
    </div>
</section>

<!-- Filter Summary Section (if filters applied) -->
@if(request()->hasAny(['search', 'type', 'location', 'max_price']))
    <section class="py-3 bg-light">
        <div class="container">
            <div class="d-flex flex-wrap align-items-center gap-2">
                <span class="text-muted">Active filters:</span>
                
                @if(request('search'))
                    <span class="badge bg-primary">Search: {{ request('search') }}</span>
                @endif
                
                @if(request('type'))
                    <span class="badge bg-primary">Type: {{ ucfirst(request('type')) }}</span>
                @endif
                
                @if(request('location'))
                    <span class="badge bg-primary">Location: {{ request('location') }}</span>
                @endif
                
                @if(request('max_price'))
                    <span class="badge bg-primary">Max Price: LKR {{ number_format(request('max_price')) }}</span>
                @endif
                
                <a href="{{ route('hostels') }}" class="btn btn-sm btn-outline-secondary">
                    <i class="fas fa-times me-1"></i>Clear Filters
                </a>
            </div>
        </div>
    </section>
@endif

<!-- Quick Booking Modal (Optional) -->
<div class="modal fade" id="quickBookModal" tabindex="-1" aria-labelledby="quickBookModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="quickBookModalLabel">Quick Book Hostel</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Redirecting you to the booking page for <strong id="modal-hostel-name"></strong>...</p>
                <div class="text-center">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
:root {
    --primary-blue: #007bff;
    --primary-orange: #fd7e14;
    --dark-blue: #0056b3;
}

.hero-section {
    background: linear-gradient(135deg, var(--primary-blue), var(--primary-orange));
    color: white;
    position: relative;
    overflow: hidden;
}

.hero-section::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.1'%3E%3Ccircle cx='30' cy='30' r='2'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E") repeat;
    opacity: 0.1;
}

.hero-title {
    font-size: 3rem;
    font-weight: 700;
    margin-bottom: 1rem;
    position: relative;
    z-index: 2;
}

.hero-subtitle {
    font-size: 1.2rem;
    opacity: 0.9;
    position: relative;
    z-index: 2;
}

.hostel-card {
    border: none;
    border-radius: 15px;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    transition: all 0.3s ease;
    overflow: hidden;
}

.hostel-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
}

.card-img-top {
    border-radius: 15px 15px 0 0;
    transition: transform 0.3s ease;
}

.hostel-card:hover .card-img-top {
    transform: scale(1.05);
}

.btn {
    border-radius: 8px;
    font-weight: 500;
    transition: all 0.3s ease;
}

.btn:hover {
    transform: translateY(-2px);
}

.btn-primary {
    background: var(--primary-blue);
    border-color: var(--primary-blue);
}

.btn-primary:hover {
    background: var(--dark-blue);
    border-color: var(--dark-blue);
}

.badge {
    font-size: 0.75rem;
    padding: 0.4rem 0.6rem;
}

.form-control, .form-select {
    border-radius: 8px;
    border: 1px solid #ddd;
    transition: all 0.3s ease;
}

.form-control:focus, .form-select:focus {
    border-color: var(--primary-blue);
    box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
}

.book-now-btn {
    position: relative;
    overflow: hidden;
}

.book-now-btn::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
    transition: left 0.6s ease;
}

.book-now-btn:hover::before {
    left: 100%;
}

@media (max-width: 768px) {
    .hero-title {
        font-size: 2rem;
    }
    
    .hero-subtitle {
        font-size: 1rem;
    }
    
    .form-control, .form-select {
        margin-bottom: 0.5rem;
    }
}

/* Loading animation for book now buttons */
.book-now-btn.loading {
    pointer-events: none;
    opacity: 0.7;
}

.book-now-btn.loading i {
    animation: spin 1s linear infinite;
}

@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Enhanced Book Now button functionality
    const bookNowButtons = document.querySelectorAll('.book-now-btn');
    
    bookNowButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            const hostelId = this.getAttribute('data-hostel-id');
            const hostelName = this.getAttribute('data-hostel-name');
            
            // Add loading state
            this.classList.add('loading');
            const originalText = this.innerHTML;
            this.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Loading...';
            
            // Optional: Show modal with loading state
            if (hostelName) {
                document.getElementById('modal-hostel-name').textContent = hostelName;
                const modal = new bootstrap.Modal(document.getElementById('quickBookModal'));
                modal.show();
                
                // Redirect after showing modal
                setTimeout(() => {
                    window.location.href = this.href;
                }, 1500);
                
                // Prevent default link behavior
                e.preventDefault();
            }
        });
    });
    
    // Auto-submit search form on select change (optional)
    const searchForm = document.getElementById('searchForm');
    const selectElements = searchForm.querySelectorAll('select');
    
    selectElements.forEach(select => {
        select.addEventListener('change', function() {
            // Uncomment the line below if you want auto-submit on filter change
            // searchForm.submit();
        });
    });
    
    // Enhanced search functionality
    const searchInput = document.querySelector('input[name="search"]');
    if (searchInput) {
        let searchTimeout;
        searchInput.addEventListener('input', function() {
            clearTimeout(searchTimeout);
            // Uncomment for live search (be careful with performance)
            // searchTimeout = setTimeout(() => {
            //     searchForm.submit();
            // }, 500);
        });
    }
    
    // Smooth scroll for hero section
    const heroSection = document.querySelector('.hero-section');
    if (heroSection) {
        window.addEventListener('scroll', function() {
            const scrolled = window.pageYOffset;
            const parallax = scrolled * 0.5;
            heroSection.style.transform = `translateY(${parallax}px)`;
        });
    }
    
    // Add click tracking for analytics (optional)
    bookNowButtons.forEach(button => {
        button.addEventListener('click', function() {
            const hostelId = this.getAttribute('data-hostel-id');
            const hostelName = this.getAttribute('data-hostel-name');
            
            // Track the click event (replace with your analytics code)
            if (typeof gtag !== 'undefined') {
                gtag('event', 'book_now_click', {
                    'event_category': 'engagement',
                    'event_label': hostelName,
                    'hostel_id': hostelId
                });
            }
            
            console.log('Book Now clicked for:', hostelName, 'ID:', hostelId);
        });
    });
});
</script>
@endpush