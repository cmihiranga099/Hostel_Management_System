<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'University Hostel Management System')</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=poppins:400,500,600,700,800&display=swap" rel="stylesheet" />
    
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="{{ asset('images/favicon.ico') }}">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/css/custom.css', 'resources/js/app.js', 'resources/js/custom.js'])

    <!-- ...existing code... -->

    <!-- Enhanced Navigation Styles -->
    <style>
        /* Enhanced Navigation Styles - Add this to your resources/css/custom.css */

.navbar-custom {
    background: rgba(255, 255, 255, 0.15) !important;
    backdrop-filter: blur(20px);
    -webkit-backdrop-filter: blur(20px);
    border-bottom: 1px solid rgba(255, 255, 255, 0.2);
    padding: 1rem 0 !important;
    box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
    transition: all 0.3s ease;
    min-height: 80px;
}

.navbar-brand {
    color: white !important;
    font-weight: 600 !important;
    font-size: 1.25rem !important;
    text-decoration: none !important;
    display: flex !important;
    align-items: center !important;
}

.navbar-brand:hover {
    color: rgba(255, 255, 255, 0.9) !important;
    transform: translateY(-1px);
}

.navbar-logo {
    width: 40px !important;
    height: 40px !important;
    margin-right: 12px !important;
    border-radius: 50% !important;
    object-fit: cover;
}

/* Force visibility of navigation links */
.navbar-nav {
    display: flex !important;
    visibility: visible !important;
    opacity: 1 !important;
}

.navbar-nav .nav-item {
    display: block !important;
    visibility: visible !important;
    opacity: 1 !important;
}

.navbar-nav .nav-link {
    color: white !important;
    font-weight: 500 !important;
    padding: 0.75rem 1.25rem !important;
    border-radius: 25px !important;
    margin: 0 0.25rem !important;
    transition: all 0.3s ease !important;
    text-decoration: none !important;
    display: flex !important;
    align-items: center !important;
    visibility: visible !important;
    opacity: 1 !important;
    background: transparent !important;
    border: none !important;
}

.navbar-nav .nav-link:hover,
.navbar-nav .nav-link.active {
    color: white !important;
    background: rgba(255, 255, 255, 0.2) !important;
    transform: translateY(-2px);
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
}

.navbar-nav .nav-link i {
    margin-right: 0.5rem !important;
    font-size: 0.9rem !important;
    color: inherit !important;
}

/* Register button styling */
.btn-primary-custom {
    background: linear-gradient(45deg, #ff6b6b, #ffa500) !important;
    border: none !important;
    color: white !important;
    font-weight: 600 !important;
    padding: 0.75rem 1.5rem !important;
    border-radius: 25px !important;
    transition: all 0.3s ease !important;
    text-decoration: none !important;
    display: inline-flex !important;
    align-items: center !important;
}

.btn-primary-custom:hover {
    transform: translateY(-2px) !important;
    box-shadow: 0 6px 20px rgba(255, 107, 107, 0.4) !important;
    color: white !important;
    text-decoration: none !important;
}

/* Dropdown styling */
.dropdown-menu {
    background: rgba(255, 255, 255, 0.95) !important;
    backdrop-filter: blur(20px) !important;
    -webkit-backdrop-filter: blur(20px) !important;
    border: 1px solid rgba(255, 255, 255, 0.2) !important;
    border-radius: 15px !important;
    box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1) !important;
    padding: 0.5rem 0 !important;
    margin-top: 0.5rem !important;
}

.dropdown-item {
    color: #333 !important;
    padding: 0.75rem 1.5rem !important;
    font-weight: 500 !important;
    transition: all 0.3s ease !important;
    border-radius: 10px !important;
    margin: 0.25rem 0.5rem !important;
    border: none !important;
    background: none !important;
    text-decoration: none !important;
    display: flex !important;
    align-items: center !important;
}

.dropdown-item:hover {
    background: rgba(102, 126, 234, 0.1) !important;
    color: #667eea !important;
    transform: translateX(5px);
    text-decoration: none !important;
}

.dropdown-item i {
    margin-right: 0.75rem !important;
    width: 16px !important;
}

/* Profile image styling */
.profile-img {
    width: 32px !important;
    height: 32px !important;
    border-radius: 50% !important;
    margin-right: 0.5rem !important;
    border: 2px solid rgba(255, 255, 255, 0.3) !important;
    object-fit: cover !important;
}

/* Mobile toggle button */
.navbar-toggler {
    border: 2px solid rgba(255, 255, 255, 0.5) !important;
    padding: 0.5rem !important;
    border-radius: 5px !important;
}

.navbar-toggler:focus {
    box-shadow: none !important;
    border-color: rgba(255, 255, 255, 0.8) !important;
}

.navbar-toggler-icon {
    background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 30 30'%3e%3cpath stroke='rgba%28255, 255, 255, 1%29' stroke-linecap='round' stroke-miterlimit='10' stroke-width='3' d='m4 7h22M4 15h22M4 23h22'/%3e%3c/svg%3e") !important;
    width: 24px !important;
    height: 24px !important;
}

/* Ensure collapse shows properly */
.navbar-collapse {
    display: flex !important;
    flex-basis: auto !important;
}

.navbar-collapse.show {
    display: flex !important;
}

.navbar-collapse.collapsing {
    display: flex !important;
}

/* Mobile responsive */
@media (max-width: 991.98px) {
    .navbar-nav {
        background: rgba(255, 255, 255, 0.1) !important;
        backdrop-filter: blur(20px) !important;
        -webkit-backdrop-filter: blur(20px) !important;
        border-radius: 15px !important;
        padding: 1rem !important;
        margin-top: 1rem !important;
        width: 100% !important;
    }
    
    .navbar-nav .nav-link {
        margin: 0.25rem 0 !important;
        width: 100% !important;
        text-align: left !important;
    }
    
    .btn-primary-custom {
        margin-top: 0.5rem !important;
        width: auto !important;
    }
}

/* Force show all navigation elements */
.navbar-nav,
.navbar-nav .nav-item,
.navbar-nav .nav-link {
    display: flex !important;
    visibility: visible !important;
    opacity: 1 !important;
}

/* Ensure Bootstrap classes don't hide elements */
.d-none {
    display: none !important;
}

.navbar-nav .nav-item:not(.d-none) {
    display: block !important;
}

/* Additional fallback for visibility */
nav .container > .navbar-collapse > .navbar-nav {
    display: flex !important;
    list-style: none !important;
    margin: 0 !important;
    padding: 0 !important;
}

nav .container > .navbar-collapse > .navbar-nav > .nav-item {
    display: list-item !important;
}

nav .container > .navbar-collapse > .navbar-nav > .nav-item > .nav-link {
    display: block !important;
    color: white !important;
}
        
        /* Mobile Responsive */
        @media (max-width: 991px) {
            .navbar-nav {
                background: rgba(255, 255, 255, 0.1);
                backdrop-filter: blur(20px);
                border-radius: 15px;
                padding: 1rem;
                margin-top: 1rem;
            }
            
            .navbar-nav .nav-link {
                margin: 0.25rem 0;
            }
        }
        
        /* Flash Messages Enhancement */
        .alert {
            border: none !important;
            border-radius: 0 !important;
            backdrop-filter: blur(10px);
        }
        
        .alert-success {
            background: rgba(40, 167, 69, 0.9) !important;
            color: white !important;
        }
        
        .alert-danger {
            background: rgba(220, 53, 69, 0.9) !important;
            color: white !important;
        }
        
        .alert-warning {
            background: rgba(255, 193, 7, 0.9) !important;
            color: white !important;
        }
        
        .btn-close {
            filter: invert(1);
        }
    </style>

    @stack('styles')
</head>
<body class="font-sans antialiased">
    <div id="app">
        <!-- Enhanced Navigation -->
<!-- Replace your existing navbar section with this -->
<nav class="navbar navbar-expand-lg navbar-custom sticky-top">
    <div class="container">
        <!-- Brand -->
        <a class="navbar-brand" href="{{ route('home') }}">
            <img src="{{ asset('images/university-hostel-logo.png') }}" alt="University Logo" class="navbar-logo">
            University Hostel Management
        </a>

        <!-- Mobile toggle button -->
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <!-- Navigation menu -->
        <div class="collapse navbar-collapse" id="navbarNav">
            <!-- Main navigation links -->
            <ul class="navbar-nav me-auto">
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}" href="{{ route('home') }}">
                        <i class="fas fa-home"></i> Home
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('hostels*') ? 'active' : '' }}" href="{{ route('hostels') }}">
                        <i class="fas fa-building"></i> Hostels
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('about') ? 'active' : '' }}" href="{{ route('about') }}">
                        <i class="fas fa-info-circle"></i> About Us
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('reviews') ? 'active' : '' }}" href="{{ route('reviews') }}">
                        <i class="fas fa-star"></i> Reviews
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('contact') ? 'active' : '' }}" href="{{ route('contact') }}">
                        <i class="fas fa-envelope"></i> Contact
                    </a>
                </li>
            </ul>

            <!-- Authentication links -->
            <ul class="navbar-nav">
                @guest
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('login') }}">
                            <i class="fas fa-sign-in-alt"></i> Login
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="btn btn-primary-custom ms-2" href="{{ route('register') }}">
                            <i class="fas fa-user-plus"></i> Register
                        </a>
                    </li>
                @else
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <img src="{{ Auth::user()->profile_image_url ?? 'https://ui-avatars.com/api/?name=' . urlencode(Auth::user()->name) . '&background=667eea&color=fff' }}" alt="Profile" class="profile-img">
                            {{ Auth::user()->name }}
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li>
                                <a class="dropdown-item" href="{{ route('dashboard') }}">
                                    <i class="fas fa-tachometer-alt"></i> Dashboard
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="{{ route('dashboard.profile') }}">
                                    <i class="fas fa-user"></i> Profile
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="{{ route('dashboard.bookings') }}">
                                    <i class="fas fa-calendar-check"></i> My Bookings
                                </a>
                            </li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form method="POST" action="{{ route('logout') }}" style="margin: 0;">
                                    @csrf
                                    <button type="submit" class="dropdown-item">
                                        <i class="fas fa-sign-out-alt"></i> Logout
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </li>
                @endguest
            </ul>
        </div>
    </div>
</nav>

        <!-- Enhanced Flash Messages -->
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show m-0" role="alert">
                <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show m-0" role="alert">
                <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if(session('warning'))
            <div class="alert alert-warning alert-dismissible fade show m-0" role="alert">
                <i class="fas fa-exclamation-triangle me-2"></i>{{ session('warning') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <!-- Main Content -->
        <main>
            @yield('content')
        </main>

        <!-- Footer -->
        <footer class="footer-custom mt-5">
            <div class="container">
                <div class="row">
                    <!-- About Section -->
                    <div class="col-lg-4 col-md-6 mb-4">
                        <div class="footer-brand">
                            <img src="{{ asset('') }}" alt="University Logo">
                            <h5>University Hostel Management</h5>
                        </div>
                        <p class="mb-3">Providing comfortable and secure accommodation for university students across Sri Lanka with modern facilities and excellent service.</p>
                        <div class="social-links">
                            <a href="#" class="footer-link me-3"><i class="fab fa-facebook-f"></i></a>
                            <a href="#" class="footer-link me-3"><i class="fab fa-twitter"></i></a>
                            <a href="#" class="footer-link me-3"><i class="fab fa-instagram"></i></a>
                            <a href="#" class="footer-link me-3"><i class="fab fa-linkedin-in"></i></a>
                        </div>
                    </div>

                    <!-- Quick Links -->
                    <div class="col-lg-2 col-md-6 mb-4">
                        <h6 class="footer-title">Quick Links</h6>
                        <a href="{{ route('home') }}" class="footer-link">Home</a>
                        <a href="{{ route('hostels') }}" class="footer-link">Hostels</a>
                        <a href="{{ route('about') }}" class="footer-link">About Us</a>
                        <a href="{{ route('contact') }}" class="footer-link">Contact</a>
                        <a href="{{ route('reviews') }}" class="footer-link">Reviews</a>
                    </div>

                    <!-- Services -->
                    <div class="col-lg-2 col-md-6 mb-4">
                        <h6 class="footer-title">Services</h6>
                        <a href="#" class="footer-link">Boys Hostels</a>
                        <a href="#" class="footer-link">Girls Hostels</a>
                        <a href="#" class="footer-link">Online Booking</a>
                        <a href="#" class="footer-link">Payment Gateway</a>
                        <a href="#" class="footer-link">24/7 Support</a>
                    </div>

                    <!-- Contact Info -->
                    <div class="col-lg-4 col-md-6 mb-4">
                        <h6 class="footer-title">Contact Information</h6>
                        <div class="contact-info">
                            <p><i class="fas fa-map-marker-alt me-2"></i>No. 123, University Road, Colombo 03, Sri Lanka</p>
                            <p><i class="fas fa-phone me-2"></i>+94 11 234 5678</p>
                            <p><i class="fas fa-envelope me-2"></i>info@universityhostel.lk</p>
                            <p><i class="fas fa-clock me-2"></i>24/7 Support Available</p>
                        </div>
                    </div>
                </div>

                <hr class="border-light my-4">

                <!-- Copyright -->
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <p class="mb-0">&copy; {{ date('Y') }} University Hostel Management System. All rights reserved.</p>
                    </div>
                    <div class="col-md-6 text-md-end">
                        <a href="#" class="footer-link me-3">Privacy Policy</a>
                        <a href="#" class="footer-link me-3">Terms of Service</a>
                        <a href="#" class="footer-link">FAQ</a>
                    </div>
                </div>
            </div>
        </footer>
    </div>

    <!-- Hostel Booking Modal -->
    <div class="modal fade" id="hostelBookingModal" tabindex="-1" aria-labelledby="hostelBookingModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="hostelBookingModalLabel">Book Hostel</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="booking-form" class="needs-validation" novalidate>
                    @csrf
                    <div class="modal-body">
                        <input type="hidden" id="hostel_id" name="hostel_package_id">
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="check_in_date" class="form-label-custom">Check-in Date</label>
                                <input type="date" class="form-control form-control-custom" id="check_in_date" name="check_in_date" required>
                                <div class="invalid-feedback">Please select a check-in date.</div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="check_out_date" class="form-label-custom">Check-out Date</label>
                                <input type="date" class="form-control form-control-custom" id="check_out_date" name="check_out_date" required>
                                <div class="invalid-feedback">Please select a check-out date.</div>
                            </div>
                        </div>

                        <h6 class="mb-3">Student Details</h6>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="student_full_name" class="form-label-custom">Full Name</label>
                                <input type="text" class="form-control form-control-custom" id="student_full_name" name="student_details[full_name]" value="{{ Auth::user()->name ?? '' }}" required>
                                <div class="invalid-feedback">Please enter your full name.</div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="student_nic" class="form-label-custom">NIC Number</label>
                                <input type="text" class="form-control form-control-custom" id="student_nic" name="student_details[nic]" value="{{ Auth::user()->nic ?? '' }}" required>
                                <div class="invalid-feedback">Please enter your NIC number.</div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="student_phone" class="form-label-custom">Phone Number</label>
                                <input type="tel" class="form-control form-control-custom" id="student_phone" name="student_details[phone]" value="{{ Auth::user()->phone ?? '' }}" required>
                                <div class="invalid-feedback">Please enter your phone number.</div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="student_university" class="form-label-custom">University</label>
                                <input type="text" class="form-control form-control-custom" id="student_university" name="student_details[university]" value="{{ Auth::user()->university ?? '' }}" required>
                                <div class="invalid-feedback">Please enter your university.</div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="student_faculty" class="form-label-custom">Faculty</label>
                                <input type="text" class="form-control form-control-custom" id="student_faculty" name="student_details[faculty]" value="{{ Auth::user()->faculty ?? '' }}" required>
                                <div class="invalid-feedback">Please enter your faculty.</div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="student_id" class="form-label-custom">Student ID</label>
                                <input type="text" class="form-control form-control-custom" id="student_id" name="student_details[student_id]" value="{{ Auth::user()->student_id ?? '' }}" required>
                                <div class="invalid-feedback">Please enter your student ID.</div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="year_of_study" class="form-label-custom">Year of Study</label>
                                <select class="form-control form-control-custom" id="year_of_study" name="student_details[year_of_study]" required>
                                    <option value="">Select Year</option>
                                    <option value="1" {{ (Auth::user()->year_of_study ?? '') == 1 ? 'selected' : '' }}>1st Year</option>
                                    <option value="2" {{ (Auth::user()->year_of_study ?? '') == 2 ? 'selected' : '' }}>2nd Year</option>
                                    <option value="3" {{ (Auth::user()->year_of_study ?? '') == 3 ? 'selected' : '' }}>3rd Year</option>
                                    <option value="4" {{ (Auth::user()->year_of_study ?? '') == 4 ? 'selected' : '' }}>4th Year</option>
                                    <option value="5" {{ (Auth::user()->year_of_study ?? '') == 5 ? 'selected' : '' }}>5th Year</option>
                                    <option value="6" {{ (Auth::user()->year_of_study ?? '') == 6 ? 'selected' : '' }}>6th Year</option>
                                </select>
                                <div class="invalid-feedback">Please select your year of study.</div>
                            </div>
                        </div>

                        <h6 class="mb-3">Emergency Contact</h6>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="emergency_contact_name" class="form-label-custom">Emergency Contact Name</label>
                                <input type="text" class="form-control form-control-custom" id="emergency_contact_name" name="student_details[emergency_contact_name]" value="{{ Auth::user()->emergency_contact_name ?? '' }}" required>
                                <div class="invalid-feedback">Please enter emergency contact name.</div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="emergency_contact_phone" class="form-label-custom">Emergency Contact Phone</label>
                                <input type="tel" class="form-control form-control-custom" id="emergency_contact_phone" name="student_details[emergency_contact_phone]" value="{{ Auth::user()->emergency_contact_phone ?? '' }}" required>
                                <div class="invalid-feedback">Please enter emergency contact phone.</div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="special_requests" class="form-label-custom">Special Requests (Optional)</label>
                            <textarea class="form-control form-control-custom" id="special_requests" name="special_requests" rows="3" placeholder="Any special requirements or requests..."></textarea>
                        </div>

                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i>
                            <strong>Price:</strong> <span id="price-display">LKR 0.00</span> per month
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary-custom">
                            <span id="booking-spinner" class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                            Book Now
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @stack('scripts')
</body>
</html>