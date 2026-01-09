@extends('layouts.app')

@section('title', 'Home - University Hostel Management System')

@section('content')
<!-- Hero Section -->
<section class="hero-section">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6 hero-content">
                <h1 class="hero-title">Find Your Perfect University Hostel in Sri Lanka</h1>
                <p class="hero-subtitle">Comfortable, secure, and affordable accommodation for students with modern facilities and excellent service.</p>
                <div class="hero-buttons">
                    <a href="{{ route('hostels') }}" class="btn btn-primary-custom me-3">
                        <i class="fas fa-search me-2"></i>Explore Hostels
                    </a>
                    <a href="{{ route('about') }}" class="btn btn-secondary-custom">
                        <i class="fas fa-info-circle me-2"></i>Learn More
                    </a>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="hero-image">
                    <img src="{{ asset('images/hero-hostel.jpg') }}" alt="University Hostel" class="img-fluid rounded-3 shadow-lg">
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Statistics Section -->
<section class="py-5 bg-light">
    <div class="container">
        <div class="row text-center">
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="stat-card">
                    <div class="stat-number">{{ $stats['total_students'] ?? 0 }}+</div>
                    <div class="stat-label">Happy Students</div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="stat-card" style="background: linear-gradient(135deg, var(--primary-orange), var(--light-orange));">
                    <div class="stat-number">{{ $stats['total_hostels'] ?? 0 }}</div>
                    <div class="stat-label">Available Hostels</div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="stat-card">
                    <div class="stat-number">{{ $stats['total_bookings'] ?? 0 }}+</div>
                    <div class="stat-label">Successful Bookings</div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="stat-card" style="background: linear-gradient(135deg, var(--primary-orange), var(--light-orange));">
                    <div class="stat-number">{{ number_format($stats['average_rating'] ?? 0, 1) }}/5</div>
                    <div class="stat-label">Average Rating</div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Boys Hostels Section -->
<section class="py-5">
    <div class="container">
        <div class="row mb-5">
            <div class="col-lg-8 mx-auto text-center">
                <h2 class="section-title mb-3">Boys Hostels</h2>
                <p class="section-subtitle">Modern and comfortable accommodation facilities designed specifically for male university students.</p>
            </div>
        </div>
        
        <div class="row">
            @forelse($boysHostels ?? [] as $hostel)
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="hostel-package-card boys-hostel">
                        <div class="package-header">
                            <h4 class="package-title">{{ $hostel->name }}</h4>
                            <div class="package-price">{{ $hostel->formatted_price }}</div>
                            <div class="package-duration">per {{ $hostel->duration }}</div>
                        </div>
                        
                        <div class="package-features">
                            @foreach($hostel->facilities as $facility)
                                <div class="feature-item">
                                    <i class="fas fa-check feature-icon"></i>
                                    {{ $facility }}
                                </div>
                            @endforeach
                            
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
                                    <button type="button" class="btn btn-primary-custom book-hostel-btn" 
                                            data-hostel-id="{{ $hostel->id }}" 
                                            data-hostel-name="{{ $hostel->name }}" 
                                            data-hostel-price="{{ $hostel->price }}"
                                            data-bs-toggle="modal" 
                                            data-bs-target="#hostelBookingModal">
                                        <i class="fas fa-calendar-plus me-2"></i>Book Now
                                    </button>
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
                <div class="col-12 text-center">
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i>No boys hostels available at the moment.
                    </div>
                </div>
            @endforelse
        </div>
        
        @if(isset($boysHostels) && $boysHostels->count() > 0)
        <div class="text-center mt-4">
            <a href="{{ route('hostels', ['type' => 'boys']) }}" class="btn btn-secondary-custom">
                <i class="fas fa-arrow-right me-2"></i>View All Boys Hostels
            </a>
        </div>
        @endif
    </div>
</section>

<!-- Girls Hostels Section -->
<section class="py-5 bg-light">
    <div class="container">
        <div class="row mb-5">
            <div class="col-lg-8 mx-auto text-center">
                <h2 class="section-title mb-3">Girls Hostels</h2>
                <p class="section-subtitle">Safe and secure accommodation facilities designed specifically for female university students.</p>
            </div>
        </div>
        
        <div class="row">
            @forelse($girlsHostels ?? [] as $hostel)
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="hostel-package-card girls-hostel">
                        <div class="package-header">
                            <h4 class="package-title">{{ $hostel->name }}</h4>
                            <div class="package-price">{{ $hostel->formatted_price }}</div>
                            <div class="package-duration">per {{ $hostel->duration }}</div>
                        </div>
                        
                        <div class="package-features">
                            @foreach($hostel->facilities as $facility)
                                <div class="feature-item">
                                    <i class="fas fa-check feature-icon"></i>
                                    {{ $facility }}
                                </div>
                            @endforeach
                            
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
                                    <button type="button" class="btn btn-primary-custom book-hostel-btn" 
                                            data-hostel-id="{{ $hostel->id }}" 
                                            data-hostel-name="{{ $hostel->name }}" 
                                            data-hostel-price="{{ $hostel->price }}"
                                            data-bs-toggle="modal" 
                                            data-bs-target="#hostelBookingModal">
                                        <i class="fas fa-calendar-plus me-2"></i>Book Now
                                    </button>
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
                <div class="col-12 text-center">
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i>No girls hostels available at the moment.
                    </div>
                </div>
            @endforelse
        </div>
        
        @if(isset($girlsHostels) && $girlsHostels->count() > 0)
        <div class="text-center mt-4">
            <a href="{{ route('hostels', ['type' => 'girls']) }}" class="btn btn-secondary-custom">
                <i class="fas fa-arrow-right me-2"></i>View All Girls Hostels
            </a>
        </div>
        @endif
    </div>
</section>

<!-- Call to Action Section -->
<section class="py-5" style="background: linear-gradient(135deg, var(--primary-blue), var(--light-blue));">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 mx-auto text-center text-white">
                <h2 class="mb-3">Ready to Find Your Perfect Hostel?</h2>
                <p class="mb-4 lead">Join thousands of satisfied students who have found their ideal accommodation with us.</p>
                <div class="cta-buttons">
                    @guest
                        <a href="{{ route('register') }}" class="btn btn-light btn-lg me-3">
                            <i class="fas fa-user-plus me-2"></i>Register Now
                        </a>
                        <a href="{{ route('hostels') }}" class="btn btn-outline-light btn-lg">
                            <i class="fas fa-search me-2"></i>Browse Hostels
                        </a>
                    @else
                        <a href="{{ route('hostels') }}" class="btn btn-light btn-lg me-3">
                            <i class="fas fa-search me-2"></i>Browse Hostels
                        </a>
                        <a href="{{ route('dashboard') }}" class="btn btn-outline-light btn-lg">
                            <i class="fas fa-tachometer-alt me-2"></i>Go to Dashboard
                        </a>
                    @endguest
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@push('styles')
<style>
    .section-title {
        font-size: 2.5rem;
        font-weight: 700;
        color: var(--dark-blue);
        margin-bottom: 1rem;
    }
    
    .section-subtitle {
        font-size: 1.1rem;
        color: #666;
        line-height: 1.6;
    }
    
    .cta-buttons .btn {
        padding: 15px 30px;
        font-size: 1.1rem;
        font-weight: 600;
    }
    
    @media (max-width: 768px) {
        .section-title {
            font-size: 2rem;
        }
        
        .cta-buttons .btn {
            display: block;
            width: 100%;
            margin-bottom: 1rem;
        }
        
        .cta-buttons .btn:last-child {
            margin-bottom: 0;
        }
    }
</style>
@endpush