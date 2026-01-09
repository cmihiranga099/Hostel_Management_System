@extends('layouts.app')

@section('title', 'Contact Us - University Hostel Management System')

@section('content')
<!-- Hero Section -->
<section class="hero-section" style="min-height: 50vh;">
    <div class="container">
        <div class="row align-items-center text-center">
            <div class="col-12">
                <h1 class="hero-title">Get In Touch</h1>
                <p class="hero-subtitle">We're here to help you with all your hostel accommodation needs. Reach out to us anytime!</p>
            </div>
        </div>
    </div>
</section>

<section class="py-5">
    <div class="container">
        <div class="row">
            <!-- Contact Information -->
            <div class="col-lg-4 mb-5">
                <div class="card-custom h-100">
                    <div class="card-header-custom text-center">
                        <h4 class="mb-0">
                            <i class="fas fa-map-marker-alt me-2"></i>Contact Information
                        </h4>
                    </div>
                    <div class="card-body-custom">
                        <div class="contact-info-item mb-4">
                            <div class="d-flex align-items-center">
                                <div class="contact-icon me-3">
                                    <i class="fas fa-map-marker-alt text-primary"></i>
                                </div>
                                <div>
                                    <h6 class="mb-1">Our Office</h6>
                                    <p class="mb-0 text-muted">No. 123, University Road<br>Colombo 03, Sri Lanka</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="contact-info-item mb-4">
                            <div class="d-flex align-items-center">
                                <div class="contact-icon me-3">
                                    <i class="fas fa-phone text-primary"></i>
                                </div>
                                <div>
                                    <h6 class="mb-1">Phone Numbers</h6>
                                    <p class="mb-0 text-muted">
                                        <strong>Main:</strong> +94 11 234 5678<br>
                                        <strong>Emergency:</strong> +94 77 987 6543
                                    </p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="contact-info-item mb-4">
                            <div class="d-flex align-items-center">
                                <div class="contact-icon me-3">
                                    <i class="fas fa-envelope text-primary"></i>
                                </div>
                                <div>
                                    <h6 class="mb-1">Email Addresses</h6>
                                    <p class="mb-0 text-muted">
                                        <strong>General:</strong> info@universityhostel.lk<br>
                                        <strong>Support:</strong> support@universityhostel.lk<br>
                                        <strong>Bookings:</strong> bookings@universityhostel.lk
                                    </p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="contact-info-item mb-4">
                            <div class="d-flex align-items-center">
                                <div class="contact-icon me-3">
                                    <i class="fas fa-clock text-primary"></i>
                                </div>
                                <div>
                                    <h6 class="mb-1">Business Hours</h6>
                                    <p class="mb-0 text-muted">
                                        <strong>Mon - Fri:</strong> 8:00 AM - 8:00 PM<br>
                                        <strong>Sat - Sun:</strong> 9:00 AM - 6:00 PM<br>
                                        <strong>Emergency:</strong> 24/7 Available
                                    </p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="contact-info-item">
                            <h6 class="mb-3">Follow Us</h6>
                            <div class="social-links">
                                <a href="#" class="btn btn-outline-primary btn-sm me-2 mb-2">
                                    <i class="fab fa-facebook-f me-1"></i>Facebook
                                </a>
                                <a href="#" class="btn btn-outline-primary btn-sm me-2 mb-2">
                                    <i class="fab fa-twitter me-1"></i>Twitter
                                </a>
                                <a href="#" class="btn btn-outline-primary btn-sm me-2 mb-2">
                                    <i class="fab fa-instagram me-1"></i>Instagram
                                </a>
                                <a href="#" class="btn btn-outline-primary btn-sm mb-2">
                                    <i class="fab fa-linkedin-in me-1"></i>LinkedIn
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Contact Form -->
            <div class="col-lg-8">
                <div class="card-custom">
                    <div class="card-header-custom text-center">
                        <h4 class="mb-0">
                            <i class="fas fa-envelope me-2"></i>Send Us a Message
                        </h4>
                        <p class="mb-0 mt-2 opacity-75">Fill out the form below and we'll get back to you as soon as possible</p>
                    </div>
                    <div class="card-body-custom">
                        <form method="POST" action="{{ route('contact.submit') }}" class="needs-validation" novalidate>
                            @csrf
                            
                            <div class="row">
                                <!-- Name -->
                                <div class="col-md-6 mb-3">
                                    <label for="name" class="form-label-custom">
                                        <i class="fas fa-user me-2"></i>Full Name *
                                    </label>
                                    <input 
                                        type="text" 
                                        class="form-control form-control-custom @error('name') is-invalid @enderror" 
                                        id="name" 
                                        name="name" 
                                        value="{{ old('name', Auth::user()->name ?? '') }}" 
                                        required
                                        placeholder="Enter your full name"
                                    >
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <!-- Email -->
                                <div class="col-md-6 mb-3">
                                    <label for="email" class="form-label-custom">
                                        <i class="fas fa-envelope me-2"></i>Email Address *
                                    </label>
                                    <input 
                                        type="email" 
                                        class="form-control form-control-custom @error('email') is-invalid @enderror" 
                                        id="email" 
                                        name="email" 
                                        value="{{ old('email', Auth::user()->email ?? '') }}" 
                                        required
                                        placeholder="Enter your email address"
                                    >
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="row">
                                <!-- Phone -->
                                <div class="col-md-6 mb-3">
                                    <label for="phone" class="form-label-custom">
                                        <i class="fas fa-phone me-2"></i>Phone Number
                                    </label>
                                    <input 
                                        type="tel" 
                                        class="form-control form-control-custom" 
                                        id="phone" 
                                        name="phone" 
                                        value="{{ old('phone', Auth::user()->phone ?? '') }}"
                                        placeholder="077 123 4567"
                                    >
                                </div>
                                
                                <!-- Subject -->
                                <div class="col-md-6 mb-3">
                                    <label for="subject" class="form-label-custom">
                                        <i class="fas fa-tag me-2"></i>Subject *
                                    </label>
                                    <select 
                                        class="form-control form-control-custom @error('subject') is-invalid @enderror" 
                                        id="subject" 
                                        name="subject" 
                                        required
                                    >
                                        <option value="">Select a subject</option>
                                        <option value="General Inquiry" {{ old('subject') == 'General Inquiry' ? 'selected' : '' }}>General Inquiry</option>
                                        <option value="Booking Support" {{ old('subject') == 'Booking Support' ? 'selected' : '' }}>Booking Support</option>
                                        <option value="Payment Issues" {{ old('subject') == 'Payment Issues' ? 'selected' : '' }}>Payment Issues</option>
                                        <option value="Hostel Information" {{ old('subject') == 'Hostel Information' ? 'selected' : '' }}>Hostel Information</option>
                                        <option value="Technical Support" {{ old('subject') == 'Technical Support' ? 'selected' : '' }}>Technical Support</option>
                                        <option value="Complaint" {{ old('subject') == 'Complaint' ? 'selected' : '' }}>Complaint</option>
                                        <option value="Feedback" {{ old('subject') == 'Feedback' ? 'selected' : '' }}>Feedback</option>
                                        <option value="Partnership" {{ old('subject') == 'Partnership' ? 'selected' : '' }}>Partnership</option>
                                        <option value="Other" {{ old('subject') == 'Other' ? 'selected' : '' }}>Other</option>
                                    </select>
                                    @error('subject')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <!-- Message -->
                            <div class="mb-4">
                                <label for="message" class="form-label-custom">
                                    <i class="fas fa-comment me-2"></i>Message *
                                </label>
                                <textarea 
                                    class="form-control form-control-custom @error('message') is-invalid @enderror" 
                                    id="message" 
                                    name="message" 
                                    rows="6" 
                                    required
                                    placeholder="Please describe your inquiry or message in detail..."
                                >{{ old('message') }}</textarea>
                                <div class="form-text">
                                    <small>Minimum 10 characters required</small>
                                </div>
                                @error('message')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <!-- University Information (if not logged in) -->
                            @guest
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="university" class="form-label-custom">
                                        <i class="fas fa-university me-2"></i>University (Optional)
                                    </label>
                                    <select class="form-control form-control-custom" id="university" name="university">
                                        <option value="">Select your university</option>
                                        <option value="University of Colombo" {{ old('university') == 'University of Colombo' ? 'selected' : '' }}>University of Colombo</option>
                                        <option value="University of Peradeniya" {{ old('university') == 'University of Peradeniya' ? 'selected' : '' }}>University of Peradeniya</option>
                                        <option value="University of Sri Jayewardenepura" {{ old('university') == 'University of Sri Jayewardenepura' ? 'selected' : '' }}>University of Sri Jayewardenepura</option>
                                        <option value="University of Kelaniya" {{ old('university') == 'University of Kelaniya' ? 'selected' : '' }}>University of Kelaniya</option>
                                        <option value="University of Moratuwa" {{ old('university') == 'University of Moratuwa' ? 'selected' : '' }}>University of Moratuwa</option>
                                        <option value="University of Ruhuna" {{ old('university') == 'University of Ruhuna' ? 'selected' : '' }}>University of Ruhuna</option>
                                        <option value="Other" {{ old('university') == 'Other' ? 'selected' : '' }}>Other</option>
                                    </select>
                                </div>
                            </div>
                            @endguest
                            
                            <!-- Submit Button -->
                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary-custom">
                                    <i class="fas fa-paper-plane me-2"></i>Send Message
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- FAQ Section -->
<section class="py-5 bg-light">
    <div class="container">
        <div class="row mb-5">
            <div class="col-lg-8 mx-auto text-center">
                <h2 class="section-title">Frequently Asked Questions</h2>
                <p class="section-subtitle">Quick answers to common questions about our services</p>
            </div>
        </div>
        
        <div class="row">
            <div class="col-lg-8 mx-auto">
                <div class="accordion" id="faqAccordion">
                    <div class="accordion-item mb-3">
                        <h2 class="accordion-header" id="faq1">
                            <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapse1">
                                How do I book a hostel?
                            </button>
                        </h2>
                        <div id="collapse1" class="accordion-collapse collapse show" data-bs-parent="#faqAccordion">
                            <div class="accordion-body">
                                Booking a hostel is simple! Browse available hostels, select your preferred one, fill in your details, and make a secure online payment. You'll receive instant confirmation via email.
                            </div>
                        </div>
                    </div>
                    
                    <div class="accordion-item mb-3">
                        <h2 class="accordion-header" id="faq2">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse2">
                                What payment methods do you accept?
                            </button>
                        </h2>
                        <div id="collapse2" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                            <div class="accordion-body">
                                We accept major credit/debit cards. All transactions are encrypted and secure.
                            </div>
                        </div>
                    </div>
                    
                    <div class="accordion-item mb-3">
                        <h2 class="accordion-header" id="faq3">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse3">
                                Can I cancel my booking?
                            </button>
                        </h2>
                        <div id="collapse3" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                            <div class="accordion-body">
                                Yes, you can cancel your booking before the check-in date. Cancellation policies may vary by hostel. Please check the specific terms during booking.
                            </div>
                        </div>
                    </div>
                    
                    <div class="accordion-item mb-3">
                        <h2 class="accordion-header" id="faq4">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse4">
                                Are meals included in the hostel fees?
                            </button>
                        </h2>
                        <div id="collapse4" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                            <div class="accordion-body">
                                Most of our hostels include 3 meals per day. However, meal arrangements vary by hostel. Check the facility details for specific information about meals.
                            </div>
                        </div>
                    </div>
                    
                    <div class="accordion-item mb-3">
                        <h2 class="accordion-header" id="faq5">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse5">
                                Is there 24/7 security at the hostels?
                            </button>
                        </h2>
                        <div id="collapse5" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                            <div class="accordion-body">
                                Yes, all our partner hostels provide 24/7 security with trained guards and CCTV monitoring to ensure student safety and peace of mind.
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Map Section -->
<section class="py-5">
    <div class="container">
        <div class="row mb-4">
            <div class="col-12 text-center">
                <h2 class="section-title">Find Us</h2>
                <p class="section-subtitle">Visit our office for in-person assistance</p>
            </div>
        </div>
        
        <div class="row">
            <div class="col-12">
                <div class="card-custom">
                    <div class="card-body p-0">
                        <div class="map-container" style="height: 400px; background: #f8f9fa; border-radius: 15px; display: flex; align-items: center; justify-content: center;">
                            <div class="text-center">
                                <i class="fas fa-map-marker-alt text-primary" style="font-size: 3rem;"></i>
                                <h5 class="mt-3">Interactive Map</h5>
                                <p class="text-muted">No. 123, University Road, Colombo 03, Sri Lanka</p>
                                <a href="https://maps.google.com/?q=University+Road+Colombo+03+Sri+Lanka" target="_blank" class="btn btn-primary-custom">
                                    <i class="fas fa-external-link-alt me-2"></i>Open in Google Maps
                                </a>
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
    .contact-icon {
        width: 40px;
        height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: var(--light-blue);
        border-radius: 50%;
        font-size: 1.2rem;
        flex-shrink: 0;
    }
    
    .contact-info-item {
        padding: 1rem 0;
        border-bottom: 1px solid #eee;
    }
    
    .contact-info-item:last-child {
        border-bottom: none;
    }
    
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
    
    .accordion-button {
        background: var(--white);
        border: none;
        font-weight: 600;
        color: var(--dark-blue);
        border-radius: 10px !important;
    }
    
    .accordion-button:not(.collapsed) {
        background: var(--light-blue);
        color: var(--dark-blue);
        box-shadow: none;
    }
    
    .accordion-button:focus {
        box-shadow: 0 0 0 0.2rem rgba(74, 144, 226, 0.25);
    }
    
    .accordion-item {
        border: 1px solid var(--light-blue);
        border-radius: 10px !important;
        background: var(--white);
    }
    
    .accordion-body {
        background: var(--white);
        border-radius: 0 0 10px 10px;
    }
    
    .social-links .btn {
        transition: all 0.3s ease;
    }
    
    .social-links .btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(74, 144, 226, 0.3);
    }
    
    .map-container {
        position: relative;
        overflow: hidden;
    }
    
    @media (max-width: 768px) {
        .section-title {
            font-size: 2rem;
        }
        
        .contact-info-item .d-flex {
            flex-direction: column;
            text-align: center;
        }
        
        .contact-icon {
            margin: 0 auto 1rem auto;
        }
        
        .social-links .btn {
            width: 100%;
            margin-bottom: 0.5rem;
        }
    }
</style>
@endpush

@push('scripts')
<script>
    // Form validation
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.querySelector('.needs-validation');
        
        form.addEventListener('submit', function(event) {
            if (!form.checkValidity()) {
                event.preventDefault();
                event.stopPropagation();
            }
            form.classList.add('was-validated');
        });
        
        // Message character count validation
        const messageTextarea = document.getElementById('message');
        const minLength = 10;
        
        messageTextarea.addEventListener('input', function() {
            const currentLength = this.value.length;
            const formText = this.parentNode.querySelector('.form-text small');
            
            if (currentLength < minLength) {
                formText.textContent = `Minimum ${minLength} characters required (${currentLength}/${minLength})`;
                formText.style.color = '#dc3545';
            } else {
                formText.textContent = `${currentLength} characters`;
                formText.style.color = '#28a745';
            }
        });
        
        // Phone number formatting
        const phoneInput = document.getElementById('phone');
        if (phoneInput) {
            phoneInput.addEventListener('input', function() {
                formatPhoneNumber(this);
            });
        }
    });
</script>
@endpush