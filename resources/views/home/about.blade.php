@extends('layouts.app')

@section('title', 'About Us - University Hostel Management System')

@section('content')
<!-- Hero Section -->
<section class="hero-section" style="min-height: 60vh;">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6">
                <h1 class="hero-title">About University Hostel Management</h1>
                <p class="hero-subtitle">Committed to providing safe, comfortable, and affordable accommodation for university students across Sri Lanka since {{ $stats['established_year'] }}.</p>
            </div>
            <div class="col-lg-6">
                <img src="{{ asset('images/about-hero.jpg') }}" alt="About Us" class="img-fluid rounded-3 shadow-lg">
            </div>
        </div>
    </div>
</section>

<!-- Our Story Section -->
<section class="py-5 bg-light">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 mx-auto">
                <div class="text-center mb-5">
                    <h2 class="section-title">Our Story</h2>
                    <p class="section-subtitle">How we became Sri Lanka's trusted hostel management platform</p>
                </div>
                
                <div class="card-custom">
                    <div class="card-body-custom">
                        <p class="lead">
                            University Hostel Management was founded in {{ $stats['established_year'] }} with a simple yet powerful vision: to bridge the gap between university students and quality accommodation across Sri Lanka.
                        </p>
                        
                        <p>
                            Our journey began when a group of university graduates realized the challenges students face in finding safe, affordable, and convenient accommodation near their universities. Drawing from their own experiences, they set out to create a comprehensive platform that would simplify the hostel booking process while maintaining the highest standards of safety and comfort.
                        </p>
                        
                        <p>
                            Today, we proudly serve thousands of students from leading universities including the University of Colombo, University of Peradeniya, University of Moratuwa, University of Sri Jayewardenepura, and many more. Our network spans across major cities and university towns throughout the island.
                        </p>
                        
                        <p>
                            We understand that choosing accommodation is not just about finding a place to stay – it's about finding a home away from home where students can focus on their academic journey while building lifelong friendships and memories.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Mission & Vision Section -->
<section class="py-5">
    <div class="container">
        <div class="row mb-5">
            <div class="col-lg-8 mx-auto text-center">
                <h2 class="section-title">Our Mission & Vision</h2>
                <p class="section-subtitle">Guiding principles that drive our commitment to student accommodation</p>
            </div>
        </div>
        
        <div class="row">
            <div class="col-lg-6 mb-4">
                <div class="card-custom h-100">
                    <div class="card-body-custom text-center">
                        <div class="feature-icon-large mb-4">
                            <i class="fas fa-bullseye text-primary" style="font-size: 4rem;"></i>
                        </div>
                        <h4 class="mb-3">Our Mission</h4>
                        <p>
                            To provide university students across Sri Lanka with access to safe, comfortable, and affordable accommodation through our innovative digital platform, while fostering a supportive community environment that enhances their academic journey.
                        </p>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-6 mb-4">
                <div class="card-custom h-100">
                    <div class="card-body-custom text-center">
                        <div class="feature-icon-large mb-4">
                            <i class="fas fa-eye text-primary" style="font-size: 4rem;"></i>
                        </div>
                        <h4 class="mb-3">Our Vision</h4>
                        <p>
                            To become Sri Lanka's leading student accommodation platform, recognized for excellence in service, innovation in technology, and unwavering commitment to student welfare and academic success.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Values Section -->
<section class="py-5 bg-light">
    <div class="container">
        <div class="row mb-5">
            <div class="col-lg-8 mx-auto text-center">
                <h2 class="section-title">Our Core Values</h2>
                <p class="section-subtitle">The principles that guide everything we do</p>
            </div>
        </div>
        
        <div class="row">
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="card-custom text-center h-100">
                    <div class="card-body-custom">
                        <div class="feature-icon-large mb-3">
                            <i class="fas fa-shield-alt text-primary" style="font-size: 3rem;"></i>
                        </div>
                        <h5 class="mb-3">Safety First</h5>
                        <p>We prioritize the safety and security of our students above all else, ensuring every hostel meets our strict safety standards.</p>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="card-custom text-center h-100">
                    <div class="card-body-custom">
                        <div class="feature-icon-large mb-3">
                            <i class="fas fa-handshake text-primary" style="font-size: 3rem;"></i>
                        </div>
                        <h5 class="mb-3">Integrity</h5>
                        <p>We maintain transparency in all our dealings and build trust through honest communication and reliable service.</p>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="card-custom text-center h-100">
                    <div class="card-body-custom">
                        <div class="feature-icon-large mb-3">
                            <i class="fas fa-lightbulb text-primary" style="font-size: 3rem;"></i>
                        </div>
                        <h5 class="mb-3">Innovation</h5>
                        <p>We continuously improve our platform and services using the latest technology to enhance user experience.</p>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="card-custom text-center h-100">
                    <div class="card-body-custom">
                        <div class="feature-icon-large mb-3">
                            <i class="fas fa-heart text-primary" style="font-size: 3rem;"></i>
                        </div>
                        <h5 class="mb-3">Care</h5>
                        <p>We genuinely care about our students' well-being and strive to create a supportive community environment.</p>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="card-custom text-center h-100">
                    <div class="card-body-custom">
                        <div class="feature-icon-large mb-3">
                            <i class="fas fa-star text-primary" style="font-size: 3rem;"></i>
                        </div>
                        <h5 class="mb-3">Excellence</h5>
                        <p>We are committed to delivering exceptional service quality in every aspect of our operations.</p>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="card-custom text-center h-100">
                    <div class="card-body-custom">
                        <div class="feature-icon-large mb-3">
                            <i class="fas fa-users text-primary" style="font-size: 3rem;"></i>
                        </div>
                        <h5 class="mb-3">Community</h5>
                        <p>We foster a sense of belonging and community among students from diverse backgrounds and universities.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Statistics Section -->
<section class="py-5">
    <div class="container">
        <div class="row mb-5">
            <div class="col-lg-8 mx-auto text-center">
                <h2 class="section-title">Our Impact in Numbers</h2>
                <p class="section-subtitle">Proud achievements that reflect our commitment to students</p>
            </div>
        </div>
        
        <div class="row text-center">
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="stat-card">
                    <div class="stat-number">{{ $stats['total_students'] }}+</div>
                    <div class="stat-label">Students Served</div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="stat-card" style="background: linear-gradient(135deg, var(--primary-orange), var(--light-orange));">
                    <div class="stat-number">{{ $stats['total_hostels'] }}</div>
                    <div class="stat-label">Partner Hostels</div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="stat-card">
                    <div class="stat-number">15+</div>
                    <div class="stat-label">Cities Covered</div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="stat-card" style="background: linear-gradient(135deg, var(--primary-orange), var(--light-orange));">
                    <div class="stat-number">{{ $stats['success_rate'] }}</div>
                    <div class="stat-label">Success Rate</div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Team Section -->
<section class="py-5 bg-light">
    <div class="container">
        <div class="row mb-5">
            <div class="col-lg-8 mx-auto text-center">
                <h2 class="section-title">Meet Our Team</h2>
                <p class="section-subtitle">Dedicated professionals working to serve students better</p>
            </div>
        </div>
        
        <div class="row">
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="card-custom text-center">
                    <div class="card-body-custom">
                        <img src="{{ asset('images/team/ceo.jpg') }}" alt="CEO" class="rounded-circle mb-3" style="width: 120px; height: 120px; object-fit: cover;">
                        <h5 class="mb-1">Priyanka Jayawardena</h5>
                        <p class="text-muted mb-2">Chief Executive Officer</p>
                        <p class="small">Leading our mission with over 10 years of experience in student services and accommodation management.</p>
                        <div class="social-links">
                            <a href="#" class="text-decoration-none me-2"><i class="fab fa-linkedin"></i></a>
                            <a href="#" class="text-decoration-none me-2"><i class="fab fa-github"></i></a>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="card-custom text-center">
                    <div class="card-body-custom">
                        <img src="{{ asset('images/team/operations.jpg') }}" alt="Operations Manager" class="rounded-circle mb-3" style="width: 120px; height: 120px; object-fit: cover;">
                        <h5 class="mb-1">Nimali Fernando</h5>
                        <p class="text-muted mb-2">Operations Manager</p>
                        <p class="small">Overseeing day-to-day operations and ensuring exceptional service delivery to our student community.</p>
                        <div class="social-links">
                            <a href="#" class="text-decoration-none me-2"><i class="fab fa-linkedin"></i></a>
                            <a href="#" class="text-decoration-none me-2"><i class="fab fa-twitter"></i></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Why Choose Us Section -->
<section class="py-5">
    <div class="container">
        <div class="row mb-5">
            <div class="col-lg-8 mx-auto text-center">
                <h2 class="section-title">Why Choose Us?</h2>
                <p class="section-subtitle">What sets us apart in student accommodation services</p>
            </div>
        </div>
        
        <div class="row">
            <div class="col-lg-6 mb-4">
                <div class="d-flex align-items-start">
                    <div class="feature-icon me-4">
                        <i class="fas fa-award text-primary" style="font-size: 2.5rem;"></i>
                    </div>
                    <div>
                        <h5 class="mb-2">Verified Quality</h5>
                        <p>Every hostel in our network undergoes rigorous quality checks and regular inspections to ensure they meet our high standards for safety, cleanliness, and comfort.</p>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-6 mb-4">
                <div class="d-flex align-items-start">
                    <div class="feature-icon me-4">
                        <i class="fas fa-clock text-primary" style="font-size: 2.5rem;"></i>
                    </div>
                    <div>
                        <h5 class="mb-2">24/7 Support</h5>
                        <p>Our dedicated support team is available round the clock to assist you with any queries, concerns, or emergencies related to your accommodation.</p>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-6 mb-4">
                <div class="d-flex align-items-start">
                    <div class="feature-icon me-4">
                        <i class="fas fa-mobile-alt text-primary" style="font-size: 2.5rem;"></i>
                    </div>
                    <div>
                        <h5 class="mb-2">Easy Booking</h5>
                        <p>Our user-friendly platform makes it simple to search, compare, and book hostels online with secure payment options and instant confirmation.</p>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-6 mb-4">
                <div class="d-flex align-items-start">
                    <div class="feature-icon me-4">
                        <i class="fas fa-map-marker-alt text-primary" style="font-size: 2.5rem;"></i>
                    </div>
                    <div>
                        <h5 class="mb-2">Prime Locations</h5>
                        <p>All our hostels are strategically located near universities, with easy access to public transportation, markets, and other essential facilities.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- University Partners Section -->
<section class="py-5 bg-light">
    <div class="container">
        <div class="row mb-5">
            <div class="col-lg-8 mx-auto text-center">
                <h2 class="section-title">University Partners</h2>
                <p class="section-subtitle">Serving students from Sri Lanka's leading universities</p>
            </div>
        </div>
        
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="row text-center">
                    <div class="col-md-4 col-sm-6 mb-4">
                        <div class="university-partner">
                            <h6 class="mb-1">University of Colombo</h6>
                            <small class="text-muted">Colombo</small>
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-6 mb-4">
                        <div class="university-partner">
                            <h6 class="mb-1">University of Peradeniya</h6>
                            <small class="text-muted">Kandy</small>
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-6 mb-4">
                        <div class="university-partner">
                            <h6 class="mb-1">University of Moratuwa</h6>
                            <small class="text-muted">Moratuwa</small>
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-6 mb-4">
                        <div class="university-partner">
                            <h6 class="mb-1">University of Sri Jayewardenepura</h6>
                            <small class="text-muted">Nugegoda</small>
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-6 mb-4">
                        <div class="university-partner">
                            <h6 class="mb-1">University of Kelaniya</h6>
                            <small class="text-muted">Kelaniya</small>
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-6 mb-4">
                        <div class="university-partner">
                            <h6 class="mb-1">University of Ruhuna</h6>
                            <small class="text-muted">Matara</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Contact CTA Section -->
<section class="py-5" style="background: linear-gradient(135deg, var(--primary-blue), var(--light-blue));">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 mx-auto text-center text-white">
                <h2 class="mb-3">Ready to Find Your Perfect Hostel?</h2>
                <p class="mb-4 lead">Join thousands of satisfied students who have found their ideal accommodation with us.</p>
                <div class="cta-buttons">
                    <a href="{{ route('hostels') }}" class="btn btn-light btn-lg me-3">
                        <i class="fas fa-search me-2"></i>Browse Hostels
                    </a>
                    <a href="{{ route('contact') }}" class="btn btn-outline-light btn-lg">
                        <i class="fas fa-envelope me-2"></i>Contact Us
                    </a>
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
    
    .feature-icon-large {
        margin-bottom: 1.5rem;
    }
    
    .university-partner {
        padding: 1rem;
        background: white;
        border-radius: 10px;
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        transition: transform 0.3s ease;
    }
    
    .university-partner:hover {
        transform: translateY(-5px);
    }
    
    .feature-icon {
        flex-shrink: 0;
    }
    
    .social-links a {
        color: var(--primary-blue);
        font-size: 1.2rem;
        transition: color 0.3s ease;
    }
    
    .social-links a:hover {
        color: var(--primary-orange);
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
        
        .feature-icon {
            margin-bottom: 1rem;
        }
        
        .d-flex.align-items-start {
            flex-direction: column;
            text-align: center;
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