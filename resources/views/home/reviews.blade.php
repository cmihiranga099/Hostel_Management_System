@extends('layouts.app')

@section('title', 'Student Reviews - University Hostel Management System')

@section('content')
<!-- Hero Section -->
<section class="hero-section" style="min-height: 60vh;">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6">
                <h1 class="hero-title">Student Reviews</h1>
                <p class="hero-subtitle">Real experiences from students who have stayed in our partner hostels across Sri Lanka. Their honest feedback helps us maintain quality and helps you make informed decisions.</p>
            </div>
            <div class="col-lg-6">
                <img src="{{ asset('images/reviews-hero.jpg') }}" alt="Student Reviews" class="img-fluid rounded-3 shadow-lg">
            </div>
        </div>
    </div>
</section>

<!-- Statistics Section -->
<section class="py-5">
    <div class="container">
        <div class="row mb-5">
            <div class="col-lg-8 mx-auto text-center">
                <h2 class="section-title">Review Statistics</h2>
                <p class="section-subtitle">What our students are saying about their hostel experiences</p>
            </div>
        </div>
        
        <div class="row text-center">
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="stat-card">
                    <div class="stat-number">{{ $reviews->count() }}</div>
                    <div class="stat-label">Total Reviews</div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="stat-card" style="background: linear-gradient(135deg, var(--primary-orange), var(--light-orange));">
                    <div class="stat-number">{{ number_format($reviews->avg('rating'), 1) }}</div>
                    <div class="stat-label">Average Rating</div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="stat-card">
                    <div class="stat-number">{{ $reviews->where('rating', 5)->count() }}</div>
                    <div class="stat-label">5-Star Reviews</div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="stat-card" style="background: linear-gradient(135deg, var(--primary-orange), var(--light-orange));">
                    <div class="stat-number">{{ $reviews->where('is_recent', true)->count() }}</div>
                    <div class="stat-label">Recent Reviews</div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Rating Breakdown Section -->
<section class="py-5 bg-light">
    <div class="container">
        <div class="row">
            <div class="col-lg-6 mb-4">
                <div class="card-custom">
                    <div class="card-body-custom">
                        <h4 class="mb-4 text-center" style="color: var(--dark-blue);">Rating Breakdown</h4>
                        @php
                            $totalReviews = $reviews->count();
                            $ratingCounts = $reviews->groupBy('rating')->map->count();
                        @endphp
                        
                        @for($i = 5; $i >= 1; $i--)
                            @php
                                $count = $ratingCounts->get($i, 0);
                                $percentage = $totalReviews > 0 ? ($count / $totalReviews) * 100 : 0;
                            @endphp
                            <div class="rating-bar">
                                <div class="rating-bar-label">{{ $i }} Star{{ $i > 1 ? 's' : '' }}</div>
                                <div class="rating-bar-container">
                                    <div class="rating-bar-fill" style="width: {{ $percentage }}%"></div>
                                </div>
                                <div class="rating-bar-count">{{ $count }}</div>
                            </div>
                        @endfor
                    </div>
                </div>
            </div>
            <div class="col-lg-6 mb-4">
                <div class="card-custom h-100">
                    <div class="card-body-custom">
                        <h4 class="mb-4 text-center" style="color: var(--dark-blue);">Student Satisfaction</h4>
                        <div class="text-center">
                            <div class="mb-3">
                                <i class="fas fa-smile" style="font-size: 4rem; color: var(--primary-orange);"></i>
                            </div>
                            @php
                                $satisfiedReviews = $reviews->where('rating', '>=', 4)->count();
                                $satisfactionRate = $totalReviews > 0 ? ($satisfiedReviews / $totalReviews) * 100 : 0;
                            @endphp
                            <h3 style="color: var(--dark-blue);">{{ number_format($satisfactionRate, 1) }}% Satisfied</h3>
                            <p class="text-muted">Students would recommend our hostels to friends</p>
                            <div class="mt-4">
                                @php
                                    $verySatisfied = $reviews->where('rating', 5)->count();
                                    $satisfied = $reviews->where('rating', 4)->count();
                                    $neutral = $reviews->where('rating', '<=', 3)->count();
                                    $verySatisfiedPercent = $totalReviews > 0 ? ($verySatisfied / $totalReviews) * 100 : 0;
                                    $satisfiedPercent = $totalReviews > 0 ? ($satisfied / $totalReviews) * 100 : 0;
                                    $neutralPercent = $totalReviews > 0 ? ($neutral / $totalReviews) * 100 : 0;
                                @endphp
                                <div class="d-flex justify-content-between mb-2">
                                    <span>Very Satisfied</span>
                                    <span>{{ number_format($verySatisfiedPercent) }}%</span>
                                </div>
                                <div class="d-flex justify-content-between mb-2">
                                    <span>Satisfied</span>
                                    <span>{{ number_format($satisfiedPercent) }}%</span>
                                </div>
                                <div class="d-flex justify-content-between">
                                    <span>Neutral/Unsatisfied</span>
                                    <span>{{ number_format($neutralPercent) }}%</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Filter Tabs -->
<section class="py-3">
    <div class="container">
        <div class="filter-tabs justify-content-center">
            <a href="#" class="filter-tab active" data-filter="all">All Reviews</a>
            <a href="#" class="filter-tab" data-filter="recent">Recent</a>
            <a href="#" class="filter-tab" data-filter="5-star">5 Stars</a>
            <a href="#" class="filter-tab" data-filter="boys">Boys Hostels</a>
            <a href="#" class="filter-tab" data-filter="girls">Girls Hostels</a>
        </div>
    </div>
</section>

<!-- Reviews Section -->
<section class="py-5">
    <div class="container">
        <div class="row mb-5">
            <div class="col-lg-8 mx-auto text-center">
                <h2 class="section-title">Latest Student Reviews</h2>
                <p class="section-subtitle">Honest feedback from our hostel community</p>
            </div>
        </div>
        
        <div class="row" id="reviews-container">
            @foreach($reviews as $review)
                <div class="col-lg-6 mb-4 review-item" 
                     data-rating="{{ $review->rating }}" 
                     data-type="{{ $review->hostel_type }}" 
                     data-recent="{{ $review->is_recent ? 'true' : 'false' }}">
                    <div class="review-card">
                        <div class="card-body-custom">
                            <div class="review-header">
                                <img src="{{ $review->profile_image }}" alt="{{ $review->user_name }}" class="review-avatar">
                                <div class="review-user-info">
                                    <h5>{{ $review->user_name }}</h5>
                                    <div class="review-date">{{ $review->formatted_date }}</div>
                                    @if($review->is_recent)
                                        <span class="recent-badge">NEW</span>
                                    @endif
                                </div>
                            </div>
                            
                            <div class="star-rating mb-2">
                                {!! $review->star_rating !!}
                            </div>
                            
                            <div class="hostel-info">
                                <h6 class="mb-1">{{ $review->hostel_name }}</h6>
                                <span class="hostel-type-badge {{ $review->hostel_type == 'boys' ? 'boys-badge' : 'girls-badge' }}">
                                    {{ $review->hostel_type == 'boys' ? '👨 Boys Hostel' : '👩 Girls Hostel' }}
                                </span>
                            </div>
                            
                            <div class="review-comment">
                                "{{ $review->comment }}"
                            </div>
                            
                            <div class="review-footer">
                                <small class="text-muted">{{ $review->time_ago }}</small>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        
        <!-- Load More Button -->
        <div class="text-center mt-4">
            <button class="btn btn-primary-custom btn-lg" id="load-more-btn">
                <i class="fas fa-chevron-down me-2"></i>Load More Reviews
            </button>
        </div>
    </div>
</section>

<!-- Testimonials Summary Section -->
<section class="py-5 bg-light">
    <div class="container">
        <div class="row mb-5">
            <div class="col-lg-8 mx-auto text-center">
                <h2 class="section-title">What Students Say About Us</h2>
                <p class="section-subtitle">Common themes from our student reviews</p>
            </div>
        </div>
        
        <div class="row">
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="card-custom text-center h-100">
                    <div class="card-body-custom">
                        <div class="feature-icon-large mb-3">
                            <i class="fas fa-shield-alt text-primary" style="font-size: 3rem;"></i>
                        </div>
                        <h5 class="mb-3">Safety & Security</h5>
                        <p class="text-muted">87% of students highlight excellent security measures and safe environment in their reviews.</p>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="card-custom text-center h-100">
                    <div class="card-body-custom">
                        <div class="feature-icon-large mb-3">
                            <i class="fas fa-utensils text-primary" style="font-size: 3rem;"></i>
                        </div>
                        <h5 class="mb-3">Food Quality</h5>
                        <p class="text-muted">92% of students are satisfied with the food quality and variety offered at our hostels.</p>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="card-custom text-center h-100">
                    <div class="card-body-custom">
                        <div class="feature-icon-large mb-3">
                            <i class="fas fa-wifi text-primary" style="font-size: 3rem;"></i>
                        </div>
                        <h5 class="mb-3">Internet & Facilities</h5>
                        <p class="text-muted">95% of students praise our high-speed internet and modern study facilities.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Write Review CTA Section -->
<section class="py-5" style="background: linear-gradient(135deg, var(--primary-blue), var(--light-blue));">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 mx-auto text-center text-white">
                <h2 class="mb-3">Share Your Hostel Experience</h2>
                <p class="mb-4 lead">Help fellow students by sharing your honest review about your hostel stay.</p>
                <div class="cta-buttons">
                    <a href="{{ route('reviews.create') }}" class="btn btn-light btn-lg me-3">
                        <i class="fas fa-edit me-2"></i>Write a Review
                    </a>
                    <a href="{{ route('hostels') }}" class="btn btn-outline-light btn-lg">
                        <i class="fas fa-search me-2"></i>Browse Hostels
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Review Guidelines Section -->
<section class="py-5">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 mx-auto">
                <div class="card-custom">
                    <div class="card-body-custom">
                        <h3 class="text-center mb-4" style="color: var(--dark-blue);">Review Guidelines</h3>
                        <div class="row">
                            <div class="col-md-6">
                                <h5 style="color: var(--primary-blue);">✓ What to Include:</h5>
                                <ul class="list-unstyled">
                                    <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Room cleanliness and comfort</li>
                                    <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Food quality and variety</li>
                                    <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Staff behavior and helpfulness</li>
                                    <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Safety and security measures</li>
                                    <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Internet and study facilities</li>
                                    <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Location and accessibility</li>
                                </ul>
                            </div>
                            <div class="col-md-6">
                                <h5 style="color: var(--primary-orange);">✗ Please Avoid:</h5>
                                <ul class="list-unstyled">
                                    <li class="mb-2"><i class="fas fa-times text-danger me-2"></i>Personal attacks or offensive language</li>
                                    <li class="mb-2"><i class="fas fa-times text-danger me-2"></i>Fake or misleading information</li>
                                    <li class="mb-2"><i class="fas fa-times text-danger me-2"></i>Reviews about competitors</li>
                                    <li class="mb-2"><i class="fas fa-times text-danger me-2"></i>Spam or promotional content</li>
                                    <li class="mb-2"><i class="fas fa-times text-danger me-2"></i>Personal information sharing</li>
                                    <li class="mb-2"><i class="fas fa-times text-danger me-2"></i>Duplicate reviews</li>
                                </ul>
                            </div>
                        </div>
                    </div>
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
        position: relative;
    }
    
    .section-title::after {
        content: '';
        position: absolute;
        bottom: -10px;
        left: 50%;
        transform: translateX(-50%);
        width: 80px;
        height: 4px;
        background: linear-gradient(90deg, var(--primary-blue), var(--primary-orange));
        border-radius: 2px;
    }
    
    .section-subtitle {
        font-size: 1.1rem;
        color: #666;
        line-height: 1.6;
        margin-bottom: 3rem;
    }
    
    .review-card {
        background: white;
        border-radius: 15px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        border: none;
        transition: all 0.3s ease;
        overflow: hidden;
        height: 100%;
    }
    
    .review-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 20px 40px rgba(0,0,0,0.15);
    }
    
    .review-header {
        display: flex;
        align-items: center;
        margin-bottom: 1rem;
    }
    
    .review-avatar {
        width: 60px;
        height: 60px;
        border-radius: 50%;
        margin-right: 1rem;
        border: 3px solid var(--light-orange);
    }
    
    .review-user-info h5 {
        margin: 0;
        color: var(--dark-blue);
        font-weight: 600;
    }
    
    .review-date {
        color: #666;
        font-size: 0.9rem;
    }
    
    .star-rating {
        color: #ffc107;
        margin: 0.5rem 0;
    }
    
    .hostel-info {
        background: #f8f9fa;
        padding: 1rem;
        border-radius: 10px;
        margin-bottom: 1rem;
        border-left: 4px solid var(--primary-orange);
    }
    
    .hostel-type-badge {
        padding: 0.3rem 0.8rem;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: 500;
    }
    
    .boys-badge {
        background: var(--primary-blue);
        color: white;
    }
    
    .girls-badge {
        background: var(--primary-orange);
        color: white;
    }
    
    .review-comment {
        font-style: italic;
        color: #555;
        line-height: 1.6;
        margin: 1rem 0;
    }
    
    .stat-card {
        background: linear-gradient(135deg, var(--primary-blue), var(--light-blue));
        color: white;
        padding: 2rem;
        border-radius: 15px;
        text-align: center;
        transition: transform 0.3s ease;
    }
    
    .stat-card:hover {
        transform: scale(1.05);
    }
    
    .stat-number {
        font-size: 3rem;
        font-weight: 700;
        margin-bottom: 0.5rem;
    }
    
    .stat-label {
        font-size: 1.1rem;
        opacity: 0.9;
    }
    
    .rating-bar {
        display: flex;
        align-items: center;
        margin-bottom: 0.8rem;
    }
    
    .rating-bar-label {
        width: 60px;
        font-size: 0.9rem;
        font-weight: 500;
    }
    
    .rating-bar-container {
        flex-grow: 1;
        background: #e9ecef;
        height: 8px;
        border-radius: 4px;
        margin: 0 1rem;
        overflow: hidden;
    }
    
    .rating-bar-fill {
        height: 100%;
        background: linear-gradient(90deg, var(--primary-blue), var(--primary-orange));
        border-radius: 4px;
        transition: width 0.3s ease;
    }
    
    .rating-bar-count {
        width: 40px;
        text-align: right;
        font-size: 0.9rem;
        color: #666;
        font-weight: 500;
    }
    
    .recent-badge {
        background: var(--primary-blue);
        color: white;
        padding: 0.2rem 0.6rem;
        border-radius: 12px;
        font-size: 0.7rem;
        font-weight: 500;
    }
    
    .filter-tabs {
        display: flex;
        gap: 1rem;
        margin-bottom: 2rem;
        flex-wrap: wrap;
        justify-content: center;
    }
    
    .filter-tab {
        padding: 0.8rem 1.5rem;
        border-radius: 25px;
        border: 2px solid var(--primary-blue);
        background: white;
        color: var(--primary-blue);
        text-decoration: none;
        font-weight: 500;
        transition: all 0.3s ease;
    }
    
    .filter-tab:hover,
    .filter-tab.active {
        background: var(--primary-blue);
        color: white;
        text-decoration: none;
    }
    
    .feature-icon-large {
        margin-bottom: 1.5rem;
    }
    
    .cta-buttons .btn {
        padding: 15px 30px;
        font-size: 1.1rem;
        font-weight: 600;
        border-radius: 10px;
    }
    
    .btn-primary-custom {
        background: linear-gradient(135deg, var(--primary-blue), var(--primary-orange));
        border: none;
        color: white;
        padding: 12px 30px;
        border-radius: 25px;
        font-weight: 600;
        transition: all 0.3s ease;
    }
    
    .btn-primary-custom:hover {
        background: linear-gradient(135deg, var(--dark-blue), var(--dark-orange));
        transform: translateY(-2px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.2);
        color: white;
    }
    
    .review-item {
        transition: all 0.3s ease;
    }
    
    .review-item.hidden {
        display: none;
    }
    
    @media (max-width: 768px) {
        .section-title {
            font-size: 2rem;
        }
        
        .review-header {
            flex-direction: column;
            text-align: center;
        }
        
        .review-avatar {
            margin-right: 0;
            margin-bottom: 1rem;
        }
        
        .filter-tabs {
            justify-content: center;
        }
        
        .filter-tab {
            margin-bottom: 0.5rem;
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

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Filter functionality
        const filterTabs = document.querySelectorAll('.filter-tab');
        const reviewItems = document.querySelectorAll('.review-item');
        
        filterTabs.forEach(tab => {
            tab.addEventListener('click', function(e) {
                e.preventDefault();
                
                // Remove active class from all tabs
                filterTabs.forEach(t => t.classList.remove('active'));
                
                // Add active class to clicked tab
                this.classList.add('active');
                
                const filter = this.getAttribute('data-filter');
                
                // Filter reviews
                reviewItems.forEach(item => {
                    const rating = item.getAttribute('data-rating');
                    const type = item.getAttribute('data-type');
                    const isRecent = item.getAttribute('data-recent') === 'true';
                    
                    let shouldShow = false;
                    
                    switch(filter) {
                        case 'all':
                            shouldShow = true;
                            break;
                        case 'recent':
                            shouldShow = isRecent;
                            break;
                        case '5-star':
                            shouldShow = rating === '5';
                            break;
                        case 'boys':
                            shouldShow = type === 'boys';
                            break;
                        case 'girls':
                            shouldShow = type === 'girls';
                            break;
                    }
                    
                    if (shouldShow) {
                        item.style.display = 'block';
                        item.classList.remove('hidden');
                    } else {
                        item.style.display = 'none';
                        item.classList.add('hidden');
                    }
                });
            });
        });
        
        // Load more functionality
        const loadMoreBtn = document.getElementById('load-more-btn');
        if (loadMoreBtn) {
            loadMoreBtn.addEventListener('click', function() {
                // Here you would implement AJAX call to load more reviews
                this.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Loading...';
                
                // Simulate loading
                setTimeout(() => {
                    this.innerHTML = '<i class="fas fa-chevron-down me-2"></i>Load More Reviews';
                    // Add more reviews here
                }, 2000);
            });
        }
        
        // Smooth scrolling for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth'
                    });
                }
            });
        });
        
        // Animate statistics on scroll
        const observerOptions = {
            threshold: 0.5,
            rootMargin: '0px 0px -100px 0px'
        };

        const observer = new IntersectionObserver(function(entries) {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const statNumbers = entry.target.querySelectorAll('.stat-number');
                    statNumbers.forEach(stat => {
                        const finalValue = stat.textContent;
                        const numericValue = parseInt(finalValue.replace(/[^0-9]/g, ''));
                        
                        if (!isNaN(numericValue)) {
                            stat.textContent = '0';
                            
                            const increment = numericValue / 50;
                            let current = 0;
                            
                            const timer = setInterval(() => {
                                current += increment;
                                if (current >= numericValue) {
                                    stat.textContent = finalValue;
                                    clearInterval(timer);
                                } else {
                                    stat.textContent = Math.floor(current);
                                }
                            }, 30);
                        }
                    });
                    
                    observer.unobserve(entry.target);
                }
            });
        }, observerOptions);

        // Observe statistics section
        const statsSection = document.querySelector('.stat-card')?.closest('section');
        if (statsSection) {
            observer.observe(statsSection);
        }
    });
</script>
@endpush