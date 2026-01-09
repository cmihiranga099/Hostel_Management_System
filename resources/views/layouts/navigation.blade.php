<nav x-data="{ open: false }" class="navbar navbar-expand-lg navbar-custom sticky-top">
    <div class="container">
        <!-- Primary Navigation Menu -->
        <div class="navbar-header d-flex justify-content-between align-items-center w-100">
            <!-- Logo -->
            <div class="navbar-brand-container">
                <a class="navbar-brand d-flex align-items-center" href="{{ route('dashboard') }}">
                    <div class="shrink-0 flex items-center">
                        <img src="{{ asset('university-hostel-logo.png') }}" alt="University Logo" class="navbar-logo me-2">
                        <span class="navbar-title">University Hostel Management</span>
                    </div>
                </a>
            </div>

            <!-- Mobile menu button -->
            <button class="navbar-toggler d-lg-none" type="button" 
                    @click="open = !open" 
                    :class="{'collapsed': !open}"
                    aria-controls="navbarNav" 
                    :aria-expanded="open" 
                    aria-label="Toggle navigation">
                <span class="navbar-toggler-icon">
                    <i class="fas fa-bars text-white"></i>
                </span>
            </button>
        </div>

        <!-- Navigation Links -->
        <div class="navbar-collapse" :class="{'show': open}" id="navbarNav">
            <!-- Left Side Navigation -->
            <ul class="navbar-nav me-auto">
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}" href="{{ route('home') }}">
                        <i class="fas fa-home me-1"></i>Home
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('hostels*') ? 'active' : '' }}" href="{{ route('hostels') }}">
                        <i class="fas fa-building me-1"></i>Find Hostels
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('about') ? 'active' : '' }}" href="{{ route('about') }}">
                        <i class="fas fa-info-circle me-1"></i>About Us
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('reviews') ? 'active' : '' }}" href="{{ route('reviews') }}">
                        <i class="fas fa-star me-1"></i>Reviews
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('contact') ? 'active' : '' }}" href="{{ route('contact') }}">
                        <i class="fas fa-envelope me-1"></i>Contact
                    </a>
                </li>
            </ul>

            <!-- Right Side Navigation -->
            <ul class="navbar-nav">
                @auth
                    <!-- Student Quick Actions Dropdown -->
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="quickActionsDropdown" role="button" 
                           data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fas fa-bolt me-1"></i>Quick Actions
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="quickActionsDropdown">
                            <li>
                                <a class="dropdown-item" href="{{ route('hostels') }}">
                                    <i class="fas fa-search me-2 text-primary"></i>Find Available Hostels
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="{{ route('student.bookings') }}">
                                    <i class="fas fa-calendar-check me-2 text-success"></i>My Bookings
                                    @if(Auth::user()->bookings && Auth::user()->bookings()->where('status', 'confirmed')->count() > 0)
                                        <span class="badge bg-success ms-2">{{ Auth::user()->bookings()->where('status', 'confirmed')->count() }}</span>
                                    @endif
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="{{ route('student.payments') }}">
                                    <i class="fas fa-credit-card me-2 text-info"></i>Payment History
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="{{ route('reviews') }}">
                                    <i class="fas fa-star me-2 text-warning"></i>Write Review
                                </a>
                            </li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <a class="dropdown-item" href="{{ route('student.notifications') }}">
                                    <i class="fas fa-bell me-2 text-secondary"></i>
                                    Notifications
                                    @if(Auth::user()->unreadNotifications && Auth::user()->unreadNotifications->count() > 0)
                                        <span class="badge bg-danger ms-1">{{ Auth::user()->unreadNotifications->count() }}</span>
                                    @endif
                                </a>
                            </li>
                        </ul>
                    </li>

                    <!-- User Profile Dropdown -->
                    <li class="nav-item dropdown user-dropdown">
                        <a class="nav-link dropdown-toggle d-flex align-items-center user-profile-link" 
                           href="#" id="navbarDropdown" role="button" 
                           data-bs-toggle="dropdown" aria-expanded="false">
                            <img src="{{ Auth::user()->profile_image_url ?? asset('images/default-avatar.png') }}" 
                                 alt="Profile Photo" class="user-avatar me-2">
                            <span class="user-name d-none d-md-inline">{{ Str::limit(Auth::user()->name, 12) }}</span>
                            <i class="fas fa-chevron-down ms-1"></i>
                        </a>

                        <ul class="dropdown-menu dropdown-menu-end user-dropdown-menu" aria-labelledby="navbarDropdown">
                            <!-- User Info Header -->
                            <li class="dropdown-header user-info-header">
                                <div class="text-center py-2">
                                    <img src="{{ Auth::user()->profile_image_url ?? asset('images/default-avatar.png') }}" 
                                         alt="Profile Photo" class="user-avatar-large mb-2">
                                    <div class="user-details">
                                        <div class="fw-bold text-white">{{ Auth::user()->name }}</div>
                                        <small class="text-light">{{ Auth::user()->university ?? 'Student' }}</small>
                                        @if(Auth::user()->student_id)
                                            <small class="d-block text-light">ID: {{ Auth::user()->student_id }}</small>
                                        @endif
                                    </div>
                                </div>
                            </li>
                            <li><hr class="dropdown-divider"></li>
                            
                            <!-- Dashboard Link -->
                            <li>
                                <a class="dropdown-item {{ request()->routeIs('student.dashboard') ? 'active' : '' }}" 
                                   href="{{ route('student.dashboard') }}">
                                    <i class="fas fa-tachometer-alt me-2"></i>Dashboard
                                </a>
                            </li>
                            
                            <!-- Profile Link -->
                            <li>
                                <a class="dropdown-item {{ request()->routeIs('student.profile*') ? 'active' : '' }}" 
                                   href="{{ route('student.profile') }}">
                                    <i class="fas fa-user me-2"></i>My Profile
                                    @php
                                        $completionScore = 0;
                                        $totalFields = 8;
                                        if (Auth::user()->name) $completionScore++;
                                        if (Auth::user()->email) $completionScore++;
                                        if (Auth::user()->phone) $completionScore++;
                                        if (Auth::user()->university) $completionScore++;
                                        if (Auth::user()->faculty) $completionScore++;
                                        if (Auth::user()->student_id) $completionScore++;
                                        if (Auth::user()->year_of_study) $completionScore++;
                                        if (Auth::user()->emergency_contact_name) $completionScore++;
                                        $completionPercentage = round(($completionScore / $totalFields) * 100);
                                    @endphp
                                    @if($completionPercentage < 100)
                                        <span class="badge bg-warning ms-2">{{ $completionPercentage }}%</span>
                                    @else
                                        <span class="badge bg-success ms-2">Complete</span>
                                    @endif
                                </a>
                            </li>
                            
                            <!-- Bookings Link -->
                            <li>
                                <a class="dropdown-item {{ request()->routeIs('student.bookings') ? 'active' : '' }}" 
                                   href="{{ route('student.bookings') }}">
                                    <i class="fas fa-calendar-check me-2"></i>My Bookings
                                    @if(Auth::user()->bookings && Auth::user()->bookings()->where('status', 'confirmed')->count() > 0)
                                        <span class="badge bg-success ms-2">{{ Auth::user()->bookings()->where('status', 'confirmed')->count() }} Active</span>
                                    @endif
                                </a>
                            </li>
                            
                            <!-- Payments Link -->
                            <li>
                                <a class="dropdown-item {{ request()->routeIs('student.payments') ? 'active' : '' }}" 
                                   href="{{ route('student.payments') }}">
                                    <i class="fas fa-credit-card me-2"></i>Payment History
                                </a>
                            </li>
                            
                            <!-- Reviews Link -->
                            <li>
                                <a class="dropdown-item {{ request()->routeIs('student.reviews') ? 'active' : '' }}" 
                                   href="{{ route('student.reviews') }}">
                                    <i class="fas fa-star me-2"></i>My Reviews
                                </a>
                            </li>
                            
                            <li><hr class="dropdown-divider"></li>
                            
                            <!-- Settings Link -->
                            <li>
                                <a class="dropdown-item {{ request()->routeIs('student.settings') ? 'active' : '' }}" 
                                   href="{{ route('student.settings') }}">
                                    <i class="fas fa-cog me-2"></i>Account Settings
                                </a>
                            </li>
                            
                            <!-- Help & Support -->
                            <li>
                                <a class="dropdown-item" href="{{ route('contact') }}">
                                    <i class="fas fa-question-circle me-2"></i>Help & Support
                                </a>
                            </li>
                            
                            <li><hr class="dropdown-divider"></li>
                            
                            <!-- Authentication -->
                            <li>
                                <form method="POST" action="{{ route('logout') }}" class="d-inline">
                                    @csrf
                                    <button type="submit" class="dropdown-item text-danger logout-btn"
                                            onclick="return confirm('Are you sure you want to logout?')">
                                        <i class="fas fa-sign-out-alt me-2"></i>Logout
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </li>
                @else
                    <!-- Guest Navigation -->
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('login') }}">
                            <i class="fas fa-sign-in-alt me-1"></i>Login
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="btn btn-outline-light ms-2 register-btn" href="{{ route('register') }}">
                            <i class="fas fa-user-plus me-1"></i>Register as Student
                        </a>
                    </li>
                @endauth
            </ul>
        </div>
    </div>
</nav>

<style>
:root {
    --primary-blue: #4a90e2;
    --primary-dark: #357abd;
    --secondary-gray: #6c757d;
    --success-green: #28a745;
    --warning-orange: #ffc107;
    --danger-red: #dc3545;
}

/* Navigation Styles */
.navbar-custom {
    background: linear-gradient(135deg, var(--primary-blue) 0%, var(--primary-dark) 100%);
    box-shadow: 0 2px 15px rgba(0, 0, 0, 0.1);
    padding: 1rem 0;
    transition: all 0.3s ease;
    position: relative;
    z-index: 1030;
}

.navbar-custom::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(255, 255, 255, 0.05);
    backdrop-filter: blur(10px);
    pointer-events: none;
}

.navbar-custom.scrolled {
    padding: 0.5rem 0;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2);
}

.navbar-brand {
    font-weight: 700;
    font-size: 1.4rem;
    color: white !important;
    text-decoration: none;
    transition: all 0.3s ease;
}

.navbar-brand:hover {
    transform: scale(1.02);
    color: rgba(255, 255, 255, 0.9) !important;
}

.navbar-logo {
    width: 45px;
    height: 45px;
    object-fit: contain;
    border-radius: 50%;
    border: 2px solid rgba(255, 255, 255, 0.2);
    padding: 2px;
}

.navbar-title {
    font-weight: 600;
    text-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

/* Navigation Links */
.navbar-nav .nav-link {
    color: rgba(255, 255, 255, 0.9) !important;
    font-weight: 500;
    margin: 0 8px;
    padding: 10px 15px !important;
    border-radius: 25px;
    transition: all 0.3s ease;
    position: relative;
    text-decoration: none;
}

.navbar-nav .nav-link:hover {
    color: white !important;
    background-color: rgba(255, 255, 255, 0.15);
    transform: translateY(-2px);
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
}

.navbar-nav .nav-link.active {
    color: white !important;
    background-color: rgba(255, 255, 255, 0.2);
    font-weight: 700;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
}

.navbar-nav .nav-link.active::after {
    content: '';
    position: absolute;
    bottom: -5px;
    left: 50%;
    width: 30px;
    height: 3px;
    background-color: white;
    border-radius: 2px;
    transform: translateX(-50%);
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
}

/* User Profile Styles */
.user-profile-link {
    background: rgba(255, 255, 255, 0.1) !important;
    border-radius: 25px !important;
    padding: 8px 15px !important;
    border: 1px solid rgba(255, 255, 255, 0.2);
}

.user-profile-link:hover {
    background: rgba(255, 255, 255, 0.2) !important;
}

.user-avatar {
    width: 35px;
    height: 35px;
    border-radius: 50%;
    object-fit: cover;
    border: 2px solid rgba(255, 255, 255, 0.8);
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
}

.user-avatar-large {
    width: 60px;
    height: 60px;
    border-radius: 50%;
    object-fit: cover;
    border: 3px solid rgba(255, 255, 255, 0.8);
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
}

.user-name {
    font-weight: 600;
    text-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
}

/* Dropdown Styles */
.dropdown-menu {
    border: none;
    box-shadow: 0 15px 35px rgba(0, 0, 0, 0.15);
    border-radius: 15px;
    padding: 0;
    margin-top: 15px;
    overflow: hidden;
    animation: dropdownSlideIn 0.3s ease;
    backdrop-filter: blur(10px);
}

@keyframes dropdownSlideIn {
    from {
        opacity: 0;
        transform: translateY(-15px) scale(0.95);
    }
    to {
        opacity: 1;
        transform: translateY(0) scale(1);
    }
}

.user-dropdown-menu {
    min-width: 280px;
}

.user-info-header {
    background: linear-gradient(135deg, var(--primary-blue) 0%, var(--primary-dark) 100%);
    color: white;
    margin: 0;
    padding: 1.5rem 1rem;
    border-radius: 0;
}

.dropdown-item {
    padding: 12px 20px;
    transition: all 0.3s ease;
    color: var(--secondary-gray);
    font-weight: 500;
    border-radius: 0;
}

.dropdown-item:hover {
    background: linear-gradient(135deg, var(--primary-blue) 0%, var(--primary-dark) 100%);
    color: white;
    transform: translateX(8px);
    padding-left: 28px;
}

.dropdown-item.active {
    background: linear-gradient(135deg, rgba(74, 144, 226, 0.1) 0%, rgba(53, 122, 189, 0.1) 100%);
    color: var(--primary-blue);
    border-left: 4px solid var(--primary-blue);
}

.dropdown-item i {
    width: 20px;
    text-align: center;
    margin-right: 10px;
}

/* Register Button */
.register-btn {
    border: 2px solid rgba(255, 255, 255, 0.8);
    border-radius: 25px;
    padding: 8px 20px;
    font-weight: 600;
    transition: all 0.3s ease;
    background: rgba(255, 255, 255, 0.1);
}

.register-btn:hover {
    background: white;
    color: var(--primary-blue) !important;
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(255, 255, 255, 0.3);
}

/* Logout Button */
.logout-btn:hover {
    background: var(--danger-red) !important;
    color: white !important;
    transform: translateX(8px);
}

/* Badge Styles */
.badge {
    font-size: 0.7em;
    padding: 0.4em 0.6em;
    border-radius: 12px;
    animation: pulse 2s infinite;
}

@keyframes pulse {
    0%, 100% { transform: scale(1); }
    50% { transform: scale(1.05); }
}

/* Mobile Responsive */
@media (max-width: 991.98px) {
    .navbar-collapse {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        border-radius: 15px;
        margin-top: 1rem;
        padding: 1.5rem;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
    }

    .navbar-nav .nav-link {
        color: var(--primary-blue) !important;
        text-align: center;
        margin: 8px 0;
        background: rgba(74, 144, 226, 0.1);
        border-radius: 10px;
    }

    .navbar-nav .nav-link:hover {
        background: var(--primary-blue) !important;
        color: white !important;
    }

    .user-profile-link {
        background: var(--primary-blue) !important;
        color: white !important;
    }

    .register-btn {
        margin-top: 15px;
        width: 100%;
        color: var(--primary-blue) !important;
        background: white;
        border-color: var(--primary-blue);
    }

    .navbar-title {
        font-size: 1.1rem;
    }
}

@media (max-width: 576px) {
    .navbar-logo {
        width: 35px;
        height: 35px;
    }

    .navbar-title {
        font-size: 0.9rem;
    }

    .user-name {
        display: none !important;
    }

    .dropdown-menu {
        margin-top: 10px;
        border-radius: 10px;
    }

    .user-dropdown-menu {
        min-width: 250px;
        margin-left: -100px;
    }
}

/* Loading and Animation States */
.navbar-toggler {
    border: none;
    padding: 8px 12px;
    border-radius: 8px;
    background: rgba(255, 255, 255, 0.1);
    transition: all 0.3s ease;
}

.navbar-toggler:hover {
    background: rgba(255, 255, 255, 0.2);
    transform: scale(1.05);
}

.navbar-toggler:focus {
    box-shadow: 0 0 0 0.2rem rgba(255, 255, 255, 0.3);
}

/* Accessibility Improvements */
.dropdown-item:focus,
.nav-link:focus {
    outline: 2px solid rgba(255, 255, 255, 0.5);
    outline-offset: 2px;
}

/* Print Styles */
@media print {
    .navbar-custom {
        display: none !important;
    }
}
</style>

<script>
// Enhanced Navigation JavaScript
document.addEventListener('DOMContentLoaded', function() {
    
    // Navbar scroll effect
    let lastScrollTop = 0;
    const navbar = document.querySelector('.navbar-custom');
    
    window.addEventListener('scroll', function() {
        const scrollTop = window.pageYOffset || document.documentElement.scrollTop;
        
        if (scrollTop > 50) {
            navbar.classList.add('scrolled');
        } else {
            navbar.classList.remove('scrolled');
        }
        
        // Hide navbar on scroll down, show on scroll up
        if (scrollTop > lastScrollTop && scrollTop > 100) {
            navbar.style.transform = 'translateY(-100%)';
        } else {
            navbar.style.transform = 'translateY(0)';
        }
        
        lastScrollTop = scrollTop;
    });

    // Dropdown auto-close on mobile
    const dropdowns = document.querySelectorAll('.dropdown-menu');
    document.addEventListener('click', function(event) {
        dropdowns.forEach(dropdown => {
            if (!dropdown.contains(event.target) && 
                !dropdown.previousElementSibling.contains(event.target)) {
                dropdown.classList.remove('show');
            }
        });
    });

    // Enhanced user profile dropdown
    const userDropdown = document.getElementById('navbarDropdown');
    if (userDropdown) {
        userDropdown.addEventListener('show.bs.dropdown', function() {
            // Add any pre-show logic here
        });
    }

    // Notification badge animation
    const badges = document.querySelectorAll('.badge');
    badges.forEach(badge => {
        if (badge.textContent.trim() !== '0' && badge.textContent.trim() !== '') {
            badge.classList.add('animate-pulse');
        }
    });

    // Active link highlighting
    const currentPath = window.location.pathname;
    const navLinks = document.querySelectorAll('.navbar-nav .nav-link');
    
    navLinks.forEach(link => {
        if (link.getAttribute('href') === currentPath) {
            link.classList.add('active');
        }
    });

    // Mobile menu handling
    const mobileToggle = document.querySelector('.navbar-toggler');
    const navbarCollapse = document.querySelector('.navbar-collapse');
    
    if (mobileToggle && navbarCollapse) {
        mobileToggle.addEventListener('click', function() {
            navbarCollapse.classList.toggle('show');
        });

        // Close mobile menu when clicking outside
        document.addEventListener('click', function(event) {
            if (!navbar.contains(event.target) && navbarCollapse.classList.contains('show')) {
                navbarCollapse.classList.remove('show');
            }
        });
    }

    // Logout confirmation
    const logoutBtn = document.querySelector('.logout-btn');
    if (logoutBtn) {
        logoutBtn.addEventListener('click', function(e) {
            if (!confirm('Are you sure you want to logout?')) {
                e.preventDefault();
            }
        });
    }
});

// Utility function for navbar interactions
window.navbarUtils = {
    showNotification: function(message, type = 'info') {
        const notification = document.createElement('div');
        notification.className = `alert alert-${type} alert-dismissible fade show position-fixed`;
        notification.style.cssText = 'top: 80px; right: 20px; z-index: 9999; min-width: 300px;';
        notification.innerHTML = `
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        `;
        
        document.body.appendChild(notification);
        
        setTimeout(() => {
            if (notification.parentElement) {
                notification.remove();
            }
        }, 5000);
    },
    
    updateNotificationBadge: function(selector, count) {
        const badge = document.querySelector(selector);
        if (badge) {
            badge.textContent = count;
            badge.style.display = count > 0 ? 'inline-block' : 'none';
        }
    }
};
</script>