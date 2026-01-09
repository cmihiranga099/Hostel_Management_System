{{-- 
Create this file: resources/views/hostels/book.blade.php
Complete booking form that works with the HostelController
--}}

@extends('layouts.app')

@section('title', 'Book ' . $hostel->name . ' - University Hostel Management')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <!-- Hostel Information Header -->
            <div class="card mb-4 shadow-sm">
                <div class="card-header bg-primary text-white">
                    <div class="row align-items-center">
                        <div class="col">
                            <h4 class="mb-0">
                                <i class="fas fa-building me-2"></i>{{ $hostel->name }}
                            </h4>
                            <p class="mb-0 mt-1 opacity-75">
                                <i class="fas fa-map-marker-alt me-1"></i>{{ $hostel->location }}
                                @if(isset($hostel->university))
                                    <span class="ms-3">
                                        <i class="fas fa-university me-1"></i>{{ $hostel->university }}
                                    </span>
                                @endif
                            </p>
                        </div>
                        @if(isset($hostel->available_slots))
                        <div class="col-auto">
                            <span class="badge bg-light text-dark">
                                {{ $hostel->available_slots }} slots available
                            </span>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Progress Indicator -->
            <div class="card mb-4">
                <div class="card-body py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="d-flex align-items-center">
                            <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center" style="width: 30px; height: 30px;">
                                <small>1</small>
                            </div>
                            <span class="ms-2 fw-bold text-primary">Booking Details</span>
                        </div>
                        <div class="flex-grow-1 mx-3">
                            <hr class="border-2">
                        </div>
                        <div class="d-flex align-items-center">
                            <div class="rounded-circle bg-light text-muted d-flex align-items-center justify-content-center" style="width: 30px; height: 30px;">
                                <small>2</small>
                            </div>
                            <span class="ms-2 text-muted">Payment</span>
                        </div>
                        <div class="flex-grow-1 mx-3">
                            <hr class="border-2 text-muted">
                        </div>
                        <div class="d-flex align-items-center">
                            <div class="rounded-circle bg-light text-muted d-flex align-items-center justify-content-center" style="width: 30px; height: 30px;">
                                <small>3</small>
                            </div>
                            <span class="ms-2 text-muted">Confirmation</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <!-- Main Booking Form -->
                <div class="col-lg-8">
                    <div class="card shadow-sm">
                        <div class="card-header">
                            <h5 class="mb-0">
                                <i class="fas fa-calendar-plus me-2"></i>Complete Your Booking
                            </h5>
                        </div>
                        <div class="card-body">
                            <!-- Error and Success Messages -->
                            @if ($errors->any())
                                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                    <strong><i class="fas fa-exclamation-triangle me-2"></i>Please fix these errors:</strong>
                                    <ul class="mb-0 mt-2">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                                </div>
                            @endif

                            @if (session('success'))
                                <div class="alert alert-success alert-dismissible fade show" role="alert">
                                    <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                                </div>
                            @endif
                            
                            @if (session('error'))
                                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                    <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                                </div>
                            @endif

                            <!-- Booking Form -->
                            <form id="booking-form" method="POST" action="{{ route('hostels.createBooking', $hostel->id) }}" novalidate>
                                @csrf
                                
                                <!-- Package Selection -->
                                <div class="mb-4">
                                    <label class="form-label fw-bold required">
                                        <i class="fas fa-box me-2"></i>Select Room Package
                                    </label>
                                    <div class="row">
                                        @foreach($packages as $package)
                                            <div class="col-12 mb-3">
                                                <div class="form-check package-option">
                                                    <input class="form-check-input" type="radio" 
                                                           name="hostel_package_id" 
                                                           value="{{ $package->id }}" 
                                                           id="package{{ $package->id }}"
                                                           data-price="{{ $package->monthly_price }}"
                                                           data-daily="{{ $package->daily_price ?? 0 }}"
                                                           data-weekly="{{ $package->weekly_price ?? 0 }}"
                                                           {{ old('hostel_package_id') == $package->id ? 'checked' : ($loop->first ? 'checked' : '') }}
                                                           required>
                                                    <label class="form-check-label package-label w-100" for="package{{ $package->id }}">
                                                        <div class="card border">
                                                            <div class="card-body">
                                                                <div class="row align-items-center">
                                                                    <div class="col-md-8">
                                                                        <h6 class="card-title mb-2">{{ $package->name }}</h6>
                                                                        <p class="card-text text-muted mb-2">{{ $package->description }}</p>
                                                                        @if(isset($package->features) && is_array($package->features))
                                                                            <small class="text-success">
                                                                                <i class="fas fa-check me-1"></i>
                                                                                {{ implode(', ', $package->features) }}
                                                                            </small>
                                                                        @elseif(isset($package->facilities))
                                                                            <small class="text-success">
                                                                                <i class="fas fa-check me-1"></i>
                                                                                {{ $package->facilities }}
                                                                            </small>
                                                                        @endif
                                                                    </div>
                                                                    <div class="col-md-4 text-end">
                                                                        <div class="price-display">
                                                                            <h5 class="text-primary mb-1">
                                                                                LKR {{ number_format($package->monthly_price) }}
                                                                            </h5>
                                                                            <small class="text-muted">per month</small>
                                                                            @if(isset($package->daily_price) && $package->daily_price > 0)
                                                                                <br><small class="text-muted">
                                                                                    LKR {{ number_format($package->daily_price) }}/day
                                                                                </small>
                                                                            @endif
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </label>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>

                                <!-- Check-in/Check-out Dates -->
                                <div class="mb-4">
                                    <label class="form-label fw-bold required">
                                        <i class="fas fa-calendar me-2"></i>Stay Duration
                                    </label>
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="check_in_date" class="form-label">Check-in Date</label>
                                            <input type="date" class="form-control" name="check_in_date" id="check_in_date"
                                                   value="{{ old('check_in_date') }}" 
                                                   min="{{ date('Y-m-d', strtotime('+1 day')) }}" 
                                                   required>
                                            <div class="invalid-feedback">Please select a check-in date.</div>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="check_out_date" class="form-label">Check-out Date</label>
                                            <input type="date" class="form-control" name="check_out_date" id="check_out_date"
                                                   value="{{ old('check_out_date') }}" 
                                                   required>
                                            <div class="invalid-feedback">Please select a check-out date.</div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-text">
                                                <i class="fas fa-info-circle me-1"></i>
                                                Duration: <span id="duration-display" class="fw-bold">Select dates</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Guest Information -->
                                <div class="mb-4">
                                    <label class="form-label fw-bold required">
                                        <i class="fas fa-user me-2"></i>Guest Information
                                    </label>
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="guest_name" class="form-label">Full Name</label>
                                            <input type="text" class="form-control" name="guest_name" id="guest_name"
                                                   value="{{ old('guest_name', $user->name ?? '') }}" 
                                                   placeholder="Enter your full name"
                                                   required>
                                            <div class="invalid-feedback">Please enter your full name.</div>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="guest_email" class="form-label">Email Address</label>
                                            <input type="email" class="form-control" name="guest_email" id="guest_email"
                                                   value="{{ old('guest_email', $user->email ?? '') }}" 
                                                   placeholder="your.email@example.com"
                                                   required>
                                            <div class="invalid-feedback">Please enter a valid email address.</div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="guest_phone" class="form-label">Phone Number</label>
                                            <input type="tel" class="form-control" name="guest_phone" id="guest_phone"
                                                   value="{{ old('guest_phone', $user->phone ?? '') }}" 
                                                   placeholder="+94 77 123 4567"
                                                   required>
                                            <div class="invalid-feedback">Please enter your phone number.</div>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="student_id" class="form-label">Student ID (if applicable)</label>
                                            <input type="text" class="form-control" name="student_id" id="student_id"
                                                   value="{{ old('student_id') }}" 
                                                   placeholder="Your student ID number">
                                        </div>
                                    </div>
                                </div>

                                <!-- Emergency Contact -->
                                <div class="mb-4">
                                    <label class="form-label fw-bold">
                                        <i class="fas fa-phone me-2"></i>Emergency Contact (Optional)
                                    </label>
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="emergency_contact_name" class="form-label">Contact Name</label>
                                            <input type="text" class="form-control" name="emergency_contact_name" 
                                                   value="{{ old('emergency_contact_name') }}" 
                                                   placeholder="Emergency contact person">
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="emergency_contact_phone" class="form-label">Contact Phone</label>
                                            <input type="tel" class="form-control" name="emergency_contact_phone" 
                                                   value="{{ old('emergency_contact_phone') }}" 
                                                   placeholder="+94 77 123 4567">
                                        </div>
                                    </div>
                                </div>

                                <!-- Special Requirements -->
                                <div class="mb-4">
                                    <label for="special_requirements" class="form-label fw-bold">
                                        <i class="fas fa-clipboard-list me-2"></i>Special Requirements
                                    </label>
                                    <textarea class="form-control" name="special_requirements" id="special_requirements" 
                                              rows="3" placeholder="Any special requirements, medical conditions, or requests...">{{ old('special_requirements') }}</textarea>
                                    <div class="form-text">Optional: Let us know if you have any specific needs or requests.</div>
                                </div>

                                <!-- Terms and Conditions -->
                                <div class="mb-4">
                                    <div class="card bg-light">
                                        <div class="card-body">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" id="terms" name="terms_accepted" required>
                                                <label class="form-check-label" for="terms">
                                                    <strong>I agree to the following:</strong>
                                                </label>
                                            </div>
                                            <div class="mt-2 small">
                                                <ul class="list-unstyled">
                                                    <li><i class="fas fa-check text-success me-2"></i>
                                                        <a href="#" target="_blank">Terms and Conditions</a> of the hostel
                                                    </li>
                                                    <li><i class="fas fa-check text-success me-2"></i>
                                                        <a href="#" target="_blank">Hostel Rules and Regulations</a>
                                                    </li>
                                                    <li><i class="fas fa-check text-success me-2"></i>
                                                        Cancellation policy (48-hour notice required)
                                                    </li>
                                                    <li><i class="fas fa-check text-success me-2"></i>
                                                        Payment terms and conditions
                                                    </li>
                                                </ul>
                                            </div>
                                            <div class="invalid-feedback">You must agree to the terms and conditions.</div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Submit Button -->
                                <div class="d-grid gap-2">
                                    <button type="submit" class="btn btn-primary btn-lg" id="submit-btn">
                                        <i class="fas fa-credit-card me-2"></i>
                                        Create Booking & Proceed to Payment
                                    </button>
                                    <small class="text-muted text-center">
                                        <i class="fas fa-lock me-1"></i>Your information is secure and encrypted
                                    </small>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Booking Summary Sidebar -->
                <div class="col-lg-4">
                    <!-- Summary Card -->
                    <div class="card shadow-sm sticky-top">
                        <div class="card-header bg-light">
                            <h5 class="mb-0">
                                <i class="fas fa-receipt me-2"></i>Booking Summary
                            </h5>
                        </div>
                        <div class="card-body">
                            <!-- Hostel Details -->
                            <div class="hostel-info mb-3">
                                <h6 class="fw-bold">{{ $hostel->name }}</h6>
                                <div class="text-muted small">
                                    <p class="mb-1">
                                        <i class="fas fa-map-marker-alt me-1"></i>{{ $hostel->location }}
                                    </p>
                                    @if(isset($hostel->phone))
                                        <p class="mb-1">
                                            <i class="fas fa-phone me-1"></i>{{ $hostel->phone }}
                                        </p>
                                    @endif
                                    @if(isset($hostel->type_display))
                                        <p class="mb-0">
                                            <i class="fas fa-users me-1"></i>{{ $hostel->type_display }}
                                        </p>
                                    @endif
                                </div>
                            </div>
                            
                            <hr>
                            
                            <!-- Dynamic Summary Details -->
                            <div id="summary-details">
                                <div class="d-flex justify-content-between mb-2">
                                    <span class="text-muted">Package:</span>
                                    <span id="selected-package" class="fw-bold">Select package</span>
                                </div>
                                <div class="d-flex justify-content-between mb-2">
                                    <span class="text-muted">Duration:</span>
                                    <span id="summary-duration" class="fw-bold">Select dates</span>
                                </div>
                                <div class="d-flex justify-content-between mb-2">
                                    <span class="text-muted">Rate:</span>
                                    <span id="monthly-rate" class="fw-bold">-</span>
                                </div>
                                <hr>
                                <div class="d-flex justify-content-between">
                                    <span class="fw-bold">Total Amount:</span>
                                    <span class="fw-bold text-primary fs-5" id="total-amount">LKR 0</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Information Card -->
                    <div class="card mt-3 shadow-sm">
                        <div class="card-body">
                            <h6 class="card-title">
                                <i class="fas fa-info-circle me-2 text-info"></i>Important Information
                            </h6>
                            <ul class="list-unstyled small mb-0">
                                <li class="mb-2">
                                    <i class="fas fa-check text-success me-2"></i>
                                    Payment required to confirm booking
                                </li>
                                <li class="mb-2">
                                    <i class="fas fa-check text-success me-2"></i>
                                    Cancellation allowed up to 48 hours before check-in
                                </li>
                                <li class="mb-2">
                                    <i class="fas fa-check text-success me-2"></i>
                                    Valid ID required at check-in
                                </li>
                                <li class="mb-2">
                                    <i class="fas fa-check text-success me-2"></i>
                                    Student ID may be required for verification
                                </li>
                                <li class="mb-0">
                                    <i class="fas fa-phone text-info me-2"></i>
                                    Need help? <a href="tel:{{ $hostel->phone ?? '+94771234567' }}">Call us</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.package-option .package-label {
    cursor: pointer;
    transition: all 0.3s ease;
}

.package-option input[type="radio"]:checked + .package-label .card {
    border-color: #0d6efd;
    box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.25);
}

.package-option:hover .package-label .card {
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
}

.required::after {
    content: " *";
    color: #dc3545;
}

.sticky-top {
    top: 1rem !important;
}

.form-control:focus {
    border-color: #0d6efd;
    box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.25);
}

.btn-primary {
    background: linear-gradient(45deg, #0d6efd, #6610f2);
    border: none;
}

.btn-primary:hover {
    background: linear-gradient(45deg, #0b5ed7, #5a0ddb);
    transform: translateY(-1px);
}
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('booking-form');
    const checkInInput = document.getElementById('check_in_date');
    const checkOutInput = document.getElementById('check_out_date');
    const packageInputs = document.querySelectorAll('input[name="hostel_package_id"]');
    const submitBtn = document.getElementById('submit-btn');
    
    // Form validation
    form.addEventListener('submit', function(e) {
        if (!form.checkValidity()) {
            e.preventDefault();
            e.stopPropagation();
        }
        form.classList.add('was-validated');
    });

    // Update package selection
    packageInputs.forEach(input => {
        input.addEventListener('change', updateSummary);
    });
    
    // Update dates and validate
    checkInInput.addEventListener('change', function() {
        const checkInDate = new Date(this.value);
        const minCheckOut = new Date(checkInDate);
        minCheckOut.setDate(minCheckOut.getDate() + 1);
        checkOutInput.min = minCheckOut.toISOString().split('T')[0];
        
        // Clear checkout date if it's before new minimum
        if (checkOutInput.value && new Date(checkOutInput.value) <= checkInDate) {
            checkOutInput.value = '';
        }
        
        updateSummary();
    });
    
    checkOutInput.addEventListener('change', updateSummary);
    
    // Update booking summary
    function updateSummary() {
        const selectedPackage = document.querySelector('input[name="hostel_package_id"]:checked');
        const checkInDate = checkInInput.value;
        const checkOutDate = checkOutInput.value;
        
        if (selectedPackage) {
            const packageName = selectedPackage.closest('.form-check').querySelector('.card-title').textContent;
            const price = parseInt(selectedPackage.dataset.price) || 0;
            
            document.getElementById('selected-package').textContent = packageName;
            document.getElementById('monthly-rate').textContent = 'LKR ' + price.toLocaleString() + '/month';
            
            if (checkInDate && checkOutDate) {
                const startDate = new Date(checkInDate);
                const endDate = new Date(checkOutDate);
                const timeDiff = endDate.getTime() - startDate.getTime();
                const daysDiff = Math.ceil(timeDiff / (1000 * 3600 * 24));
                
                if (daysDiff > 0) {
                    const months = Math.max(1, Math.ceil(daysDiff / 30));
                    const totalAmount = price * months;
                    
                    // Update duration displays
                    const durationText = daysDiff + ' day' + (daysDiff !== 1 ? 's' : '') + 
                                       ' (' + months + ' month' + (months !== 1 ? 's' : '') + ')';
                    
                    document.getElementById('duration-display').textContent = durationText;
                    document.getElementById('summary-duration').textContent = daysDiff + ' day' + (daysDiff !== 1 ? 's' : '');
                    document.getElementById('total-amount').textContent = 'LKR ' + totalAmount.toLocaleString();
                    
                    // Enable submit button
                    submitBtn.disabled = false;
                } else {
                    document.getElementById('duration-display').textContent = 'Invalid dates';
                    document.getElementById('summary-duration').textContent = 'Invalid';
                    document.getElementById('total-amount').textContent = 'LKR 0';
                    submitBtn.disabled = true;
                }
            } else {
                document.getElementById('duration-display').textContent = 'Select dates';
                document.getElementById('summary-duration').textContent = 'Select dates';
                document.getElementById('total-amount').textContent = 'LKR ' + price.toLocaleString();
            }
        } else {
            document.getElementById('selected-package').textContent = 'Select package';
            document.getElementById('monthly-rate').textContent = '-';
            document.getElementById('total-amount').textContent = 'LKR 0';
        }
    }
    
    // Initial update
    updateSummary();
    
    // Real-time form validation feedback
    const inputs = form.querySelectorAll('input, select, textarea');
    inputs.forEach(input => {
        input.addEventListener('blur', function() {
            if (this.checkValidity()) {
                this.classList.remove('is-invalid');
                this.classList.add('is-valid');
            } else {
                this.classList.remove('is-valid');
                this.classList.add('is-invalid');
            }
        });
    });
});
</script>
@endpush