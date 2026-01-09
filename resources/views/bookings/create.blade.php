@extends('layouts.app')

@section('title', 'Book ' . ($hostel->name ?? 'Hostel') . ' - University Hostel Management')

@section('content')
<div class="container py-5">
    <!-- Debug Info (remove in production) -->
    @if(config('app.debug'))
    <div class="alert alert-info mb-4">
        <h6>Debug Information:</h6>
        <p><strong>Hostel ID:</strong> {{ $hostel->id ?? 'Not provided' }}</p>
        <p><strong>Hostel Name:</strong> {{ $hostel->name ?? 'Not provided' }}</p>
        <p><strong>User:</strong> {{ Auth::user()->name ?? 'Not logged in' }}</p>
        <p><strong>Current Route:</strong> {{ request()->route()->getName() }}</p>
    </div>
    @endif

    <!-- Progress Steps -->
    <div class="row mb-5">
        <div class="col-12">
            <div class="progress-steps">
                <div class="step active" data-step="1">
                    <span class="step-number">1</span>
                    <span class="step-label">Booking Details</span>
                </div>
                <div class="step" data-step="2">
                    <span class="step-number">2</span>
                    <span class="step-label">Personal Info</span>
                </div>
                <div class="step" data-step="3">
                    <span class="step-number">3</span>
                    <span class="step-label">Payment</span>
                </div>
                <div class="step" data-step="4">
                    <span class="step-number">4</span>
                    <span class="step-label">Confirmation</span>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Main Form -->
        <div class="col-lg-8">
            <form id="bookingForm" method="POST" action="{{ url('/bookings') }}">

                @csrf
                
                <input type="hidden" name="hostel_id" value="{{ $hostel->id ?? 1 }}">
                
                <!-- Step 1: Booking Details -->
                <div class="card mb-4 step-content" id="step-1">
                    <div class="card-header">
                        <h4 class="mb-0"><i class="fas fa-calendar-alt me-2"></i>Booking Details</h4>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="check_in_date" class="form-label">Check-in Date *</label>
                                <input type="date" class="form-control" id="check_in_date" name="check_in_date" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="check_out_date" class="form-label">Check-out Date *</label>
                                <input type="date" class="form-control" id="check_out_date" name="check_out_date" required>
                            </div>
                        </div>
                        
                        <!-- Package Selection -->
                        <div class="mb-3">
                            <label class="form-label">Room Package *</label>
                            <div class="row">
                                @php 
                                    // Fallback packages if not provided
                                    $defaultPackages = collect([
                                        (object)['id' => 1, 'name' => 'Standard Room', 'monthly_price' => 18000, 'description' => 'Basic amenities with shared facilities'],
                                        (object)['id' => 2, 'name' => 'Premium Room', 'monthly_price' => 25000, 'description' => 'Enhanced comfort with private bathroom']
                                    ]);
                                    $packages = $packages ?? $defaultPackages;
                                @endphp
                                @foreach($packages as $package)
                                <div class="col-md-6 mb-3">
                                    <div class="package-option">
                                        <input type="radio" class="btn-check" name="package_id" id="package_{{ $package->id }}" 
                                               value="{{ $package->id }}" data-price="{{ $package->monthly_price }}" required>
                                        <label class="btn btn-outline-primary w-100 text-start" for="package_{{ $package->id }}">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <div>
                                                    <strong>{{ $package->name }}</strong>
                                                    <br><small class="text-muted">{{ $package->description }}</small>
                                                </div>
                                                <div class="text-end">
                                                    <strong>LKR {{ number_format($package->monthly_price) }}</strong>
                                                    <br><small class="text-muted">per month</small>
                                                </div>
                                            </div>
                                        </label>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="special_requests" class="form-label">Special Requests</label>
                            <textarea class="form-control" id="special_requests" name="special_requests" rows="3" 
                                      placeholder="Any special requirements or preferences..."></textarea>
                        </div>
                        
                        <div class="text-end">
                            <button type="button" class="btn btn-primary" onclick="nextStep(2)">
                                Next: Personal Info <i class="fas fa-arrow-right ms-2"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Step 2: Personal Information -->
                <div class="card mb-4 step-content d-none" id="step-2">
                    <div class="card-header">
                        <h4 class="mb-0"><i class="fas fa-user me-2"></i>Personal Information</h4>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="emergency_contact_name" class="form-label">Emergency Contact Name *</label>
                                <input type="text" class="form-control" id="emergency_contact_name" 
                                       name="emergency_contact_name" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="emergency_contact_phone" class="form-label">Emergency Contact Phone *</label>
                                <input type="tel" class="form-control" id="emergency_contact_phone" 
                                       name="emergency_contact_phone" required>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="student_id" class="form-label">Student ID</label>
                                <input type="text" class="form-control" id="student_id" name="student_id">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="university" class="form-label">University/Institute</label>
                                <input type="text" class="form-control" id="university" name="university">
                            </div>
                        </div>
                        
                        <div class="d-flex justify-content-between">
                            <button type="button" class="btn btn-outline-secondary" onclick="prevStep(1)">
                                <i class="fas fa-arrow-left me-2"></i>Back
                            </button>
                            <button type="button" class="btn btn-primary" onclick="nextStep(3)">
                                Next: Payment <i class="fas fa-arrow-right ms-2"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Step 3: Payment -->
                <div class="card mb-4 step-content d-none" id="step-3">
                    <div class="card-header">
                        <h4 class="mb-0"><i class="fas fa-credit-card me-2"></i>Payment Information</h4>
                    </div>
                    <div class="card-body">
                        <!-- Payment Method Selection -->
                        <div class="mb-4">
                            <label class="form-label">Payment Method *</label>
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <input type="radio" class="btn-check" name="payment_method" id="visa" value="visa" required>
                                    <label class="btn btn-outline-primary w-100" for="visa">
                                        <i class="fab fa-cc-visa fa-2x mb-2"></i>
                                        <br>Visa Card
                                    </label>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <input type="radio" class="btn-check" name="payment_method" id="mastercard" value="mastercard" required>
                                    <label class="btn btn-outline-primary w-100" for="mastercard">
                                        <i class="fab fa-cc-mastercard fa-2x mb-2"></i>
                                        <br>Mastercard
                                    </label>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <input type="radio" class="btn-check" name="payment_method" id="amex" value="amex" required>
                                    <label class="btn btn-outline-primary w-100" for="amex">
                                        <i class="fab fa-cc-amex fa-2x mb-2"></i>
                                        <br>Amex
                                    </label>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Card Details -->
                        <div class="payment-details">
                            <div class="row">
                                <div class="col-12 mb-3">
                                    <label for="card_number" class="form-label">Card Number *</label>
                                    <input type="text" class="form-control" id="card_number" name="card_number" 
                                           placeholder="1234 5678 9012 3456" maxlength="19" required>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-8 mb-3">
                                    <label for="card_name" class="form-label">Cardholder Name *</label>
                                    <input type="text" class="form-control" id="card_name" name="card_name" 
                                           placeholder="John Doe" required>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="card_cvv" class="form-label">CVV *</label>
                                    <input type="text" class="form-control" id="card_cvv" name="card_cvv" 
                                           placeholder="123" maxlength="4" required>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="card_expiry_month" class="form-label">Expiry Month *</label>
                                    <select class="form-select" id="card_expiry_month" name="card_expiry_month" required>
                                        <option value="">Select Month</option>
                                        @for($i = 1; $i <= 12; $i++)
                                            <option value="{{ sprintf('%02d', $i) }}">{{ sprintf('%02d', $i) }} - {{ date('F', mktime(0, 0, 0, $i, 1)) }}</option>
                                        @endfor
                                    </select>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="card_expiry_year" class="form-label">Expiry Year *</label>
                                    <select class="form-select" id="card_expiry_year" name="card_expiry_year" required>
                                        <option value="">Select Year</option>
                                        @for($i = date('Y'); $i <= date('Y') + 10; $i++)
                                            <option value="{{ $i }}">{{ $i }}</option>
                                        @endfor
                                    </select>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Demo Payment Notice -->
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i>
                            <strong>Demo Mode:</strong> This is a demonstration. No real payment will be processed. 
                            Use test card numbers like 4242 4242 4242 4242 for testing.
                        </div>
                        
                        <div class="d-flex justify-content-between">
                            <button type="button" class="btn btn-outline-secondary" onclick="prevStep(2)">
                                <i class="fas fa-arrow-left me-2"></i>Back
                            </button>
                            <button type="button" class="btn btn-primary" onclick="nextStep(4)">
                                Review Booking <i class="fas fa-arrow-right ms-2"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Step 4: Confirmation -->
                <div class="card mb-4 step-content d-none" id="step-4">
                    <div class="card-header">
                        <h4 class="mb-0"><i class="fas fa-check-circle me-2"></i>Confirm Booking</h4>
                    </div>
                    <div class="card-body">
                        <div id="booking-summary">
                            <!-- Summary will be populated by JavaScript -->
                        </div>
                        
                        <div class="form-check mb-3">
                            <input class="form-check-input" type="checkbox" id="terms_accepted" name="terms_accepted" required>
                            <label class="form-check-label" for="terms_accepted">
                                I agree to the <a href="#" target="_blank">Terms & Conditions</a> and <a href="#" target="_blank">Privacy Policy</a>
                            </label>
                        </div>
                        
                        <!-- Hidden fields for final submission -->
                        <input type="hidden" name="total_amount" id="final_total_amount" value="">
                        <input type="hidden" name="duration_days" id="final_duration_days" value="">
                        <input type="hidden" name="booking_reference" value="BK-{{ strtoupper(uniqid()) }}">
                        
                        <div class="d-flex justify-content-between">
                            <button type="button" class="btn btn-outline-secondary" onclick="prevStep(3)">
                                <i class="fas fa-arrow-left me-2"></i>Back
                            </button>
                            <button type="submit" class="btn btn-success btn-lg" id="confirm-booking-btn">
                                <i class="fas fa-lock me-2"></i>Confirm & Pay Now
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>

        <!-- Booking Summary Sidebar -->
        <div class="col-lg-4">
            <div class="card sticky-top" style="top: 2rem;">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-receipt me-2"></i>Booking Summary</h5>
                </div>
                <div class="card-body">
                    <!-- Hostel Info -->
                    <div class="hostel-info mb-4">
                        @if(isset($hostel))
                        <img src="{{ $hostel->image_url ?? asset('images/default-hostel.jpg') }}" 
                             alt="{{ $hostel->name }}" class="img-fluid rounded mb-3">
                        <h6 class="fw-bold">{{ $hostel->name }}</h6>
                        <p class="text-muted mb-2">
                            <i class="fas fa-map-marker-alt me-1"></i>{{ $hostel->location ?? 'Colombo' }}
                        </p>
                        <span class="badge bg-primary">{{ ucfirst($hostel->type ?? 'university') }} Hostel</span>
                        @else
                        <div class="alert alert-warning">
                            <small>Hostel information not available</small>
                        </div>
                        @endif
                    </div>
                    
                    <!-- Price Breakdown -->
                    <div class="price-breakdown">
                        <div class="d-flex justify-content-between mb-2">
                            <span>Package:</span>
                            <span id="selected-package">Not selected</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Duration:</span>
                            <span id="booking-duration">0 months</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Monthly Rate:</span>
                            <span id="monthly-rate">LKR 0</span>
                        </div>
                        <hr>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Subtotal:</span>
                            <span id="subtotal">LKR 0</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Service Fee:</span>
                            <span id="service-fee">LKR 1,000</span>
                        </div>
                        <hr>
                        <div class="d-flex justify-content-between">
                            <strong>Total Amount:</strong>
                            <strong id="total-amount" class="text-primary">LKR 0</strong>
                        </div>
                    </div>
                    
                    <!-- Contact Support -->
                    <div class="mt-4 text-center">
                        <p class="small text-muted mb-2">Need help with booking?</p>
                        <a href="#" class="btn btn-outline-primary btn-sm">
                            <i class="fas fa-phone me-1"></i>Contact Support
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Loading Modal -->
<div class="modal fade" id="loadingModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body text-center py-5">
                <div class="spinner-border text-primary mb-3" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
                <h5>Processing Your Payment...</h5>
                <p class="text-muted">Please wait while we process your booking.</p>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.progress-steps {
    display: flex;
    justify-content: space-between;
    align-items: center;
    position: relative;
    margin-bottom: 2rem;
}

.progress-steps::before {
    content: '';
    position: absolute;
    top: 50%;
    left: 0;
    right: 0;
    height: 2px;
    background: #e9ecef;
    z-index: 1;
}

.step {
    display: flex;
    flex-direction: column;
    align-items: center;
    background: white;
    z-index: 2;
    padding: 0 15px;
}

.step-number {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: bold;
    margin-bottom: 8px;
    background: #e9ecef;
    color: #6c757d;
    transition: all 0.3s ease;
}

.step.active .step-number {
    background: #007bff;
    color: white;
}

.step.completed .step-number {
    background: #28a745;
    color: white;
}

.step-label {
    font-size: 0.875rem;
    color: #6c757d;
    font-weight: 500;
}

.step.active .step-label {
    color: #007bff;
}

.package-option {
    margin-bottom: 1rem;
}

.package-option .btn-check:checked + .btn {
    background-color: #007bff;
    border-color: #007bff;
    color: white;
}

.payment-details {
    background: #f8f9fa;
    padding: 1.5rem;
    border-radius: 10px;
}

.card {
    border: none;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    border-radius: 15px;
}

.card-header {
    background: linear-gradient(135deg, #f8f9fa, #e9ecef);
    border-bottom: 1px solid #dee2e6;
    border-radius: 15px 15px 0 0;
}

.is-invalid {
    border-color: #dc3545;
}

.border-danger {
    border-color: #dc3545 !important;
}

@media (max-width: 768px) {
    .progress-steps {
        flex-direction: column;
        gap: 1rem;
    }
    
    .progress-steps::before {
        display: none;
    }
    
    .step {
        flex-direction: row;
        width: 100%;
        justify-content: flex-start;
        gap: 1rem;
        padding: 0;
    }
    
    .step-number {
        margin-bottom: 0;
    }
}
</style>
@endpush

@push('scripts')
<script>
// Global variables
let isFormSubmitting = false;

document.addEventListener('DOMContentLoaded', function() {
    console.log('🚀 Booking form initialized');
    
    // Set minimum dates
    const today = new Date().toISOString().split('T')[0];
    const checkInInput = document.getElementById('check_in_date');
    const checkOutInput = document.getElementById('check_out_date');
    
    if (checkInInput) {
        checkInInput.min = today;
        checkInInput.addEventListener('change', function() {
            if (checkOutInput) {
                checkOutInput.min = this.value;
            }
            updatePricing();
        });
    }
    
    if (checkOutInput) {
        checkOutInput.addEventListener('change', updatePricing);
    }
    
    // Package selection
    document.querySelectorAll('input[name="package_id"]').forEach(function(radio) {
        radio.addEventListener('change', updatePricing);
    });
    
    // Card number formatting
    const cardNumberInput = document.getElementById('card_number');
    if (cardNumberInput) {
        cardNumberInput.addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            value = value.replace(/(\d{4})(?=\d)/g, '$1 ');
            e.target.value = value;
        });
    }
    
    // CVV validation
    const cardCvvInput = document.getElementById('card_cvv');
    if (cardCvvInput) {
        cardCvvInput.addEventListener('input', function(e) {
            e.target.value = e.target.value.replace(/\D/g, '');
        });
    }
    
    // Form submission handling
    const bookingForm = document.getElementById('bookingForm');
    const confirmBtn = document.getElementById('confirm-booking-btn');
    
    if (bookingForm && confirmBtn) {
        console.log('✅ Form and button found');
        
        // Handle form submission
        bookingForm.addEventListener('submit', function(e) {
            e.preventDefault();
            if (!isFormSubmitting) {
                handleFormSubmission(e);
            }
        });
        
        // Handle button click
        confirmBtn.addEventListener('click', function(e) {
            e.preventDefault();
            if (!isFormSubmitting) {
                handleConfirmClick(e);
            }
        });
    } else {
        console.error('❌ Form or button not found');
        console.log('Form found:', !!bookingForm);
        console.log('Button found:', !!confirmBtn);
    }
});

function handleConfirmClick(e) {
    console.log('🔘 Confirm button clicked');
    
    // Check if we're on step 4
    const step4 = document.getElementById('step-4');
    if (!step4 || step4.classList.contains('d-none')) {
        console.log('Not on step 4, need to navigate there first');
        return;
    }
    
    // Validate terms and conditions
    const termsCheckbox = document.getElementById('terms_accepted');
    if (!termsCheckbox || !termsCheckbox.checked) {
        alert('Please accept the Terms & Conditions to proceed.');
        return;
    }
    
    // Trigger form submission
    handleFormSubmission(e);
}

function handleFormSubmission(e) {
    if (isFormSubmitting) {
        console.log('⚠️ Form already submitting, ignoring duplicate request');
        return;
    }
    
    isFormSubmitting = true;
    console.log('🔍 ENHANCED DEBUGGING - Form submission started');
    
    const form = document.getElementById('bookingForm');
    const confirmBtn = document.getElementById('confirm-booking-btn');
    
    if (!form || !confirmBtn) {
        console.error('❌ Form or button not found during submission');
        isFormSubmitting = false;
        return;
    }
    
    // === VALIDATION PHASE ===
    console.log('=== VALIDATING FORM DATA ===');
    
    // Check all required fields
    const requiredFields = [
        { name: 'hostel_id', element: document.querySelector('input[name="hostel_id"]') },
        { name: 'check_in_date', element: document.getElementById('check_in_date') },
        { name: 'check_out_date', element: document.getElementById('check_out_date') },
        { name: 'package_id', element: document.querySelector('input[name="package_id"]:checked') },
        { name: 'emergency_contact_name', element: document.getElementById('emergency_contact_name') },
        { name: 'emergency_contact_phone', element: document.getElementById('emergency_contact_phone') },
        { name: 'payment_method', element: document.querySelector('input[name="payment_method"]:checked') },
        { name: 'card_number', element: document.getElementById('card_number') },
        { name: 'card_name', element: document.getElementById('card_name') },
        { name: 'card_cvv', element: document.getElementById('card_cvv') },
        { name: 'card_expiry_month', element: document.getElementById('card_expiry_month') },
        { name: 'card_expiry_year', element: document.getElementById('card_expiry_year') },
        { name: 'terms_accepted', element: document.getElementById('terms_accepted') }
    ];
    
    let missingFields = [];
    let hasValidationErrors = false;
    
    requiredFields.forEach(field => {
        if (!field.element) {
            console.error(`❌ Element not found: ${field.name}`);
            missingFields.push(`${field.name} (element missing)`);
            hasValidationErrors = true;
            return;
        }
        
        let value = '';
        if (field.element.type === 'checkbox') {
            value = field.element.checked;
            if (!value) {
                console.error(`❌ ${field.name}: not checked`);
                missingFields.push(field.name);
                hasValidationErrors = true;
            } else {
                console.log(`✅ ${field.name}: checked`);
            }
        } else {
            value = field.element.value?.trim() || '';
            if (!value) {
                console.error(`❌ ${field.name}: empty or missing`);
                missingFields.push(field.name);
                hasValidationErrors = true;
            } else {
                // Don't log sensitive card data
                const logValue = field.name.includes('card') && field.name !== 'card_name' ? '***HIDDEN***' : value;
                console.log(`✅ ${field.name}: ${logValue}`);
            }
        }
    });
    
    // Validate dates
    const checkIn = document.getElementById('check_in_date')?.value;
    const checkOut = document.getElementById('check_out_date')?.value;
    const today = new Date().toISOString().split('T')[0];
    
    if (checkIn && checkIn < today) {
        console.error('❌ Check-in date is in the past');
        missingFields.push('check_in_date (past date)');
        hasValidationErrors = true;
    }
    
    if (checkIn && checkOut && checkOut <= checkIn) {
        console.error('❌ Check-out date must be after check-in');
        missingFields.push('check_out_date (must be after check-in)');
        hasValidationErrors = true;
    }
    
    // Validate card details
    const cardNumber = document.getElementById('card_number')?.value?.replace(/\s/g, '') || '';
    const cardCvv = document.getElementById('card_cvv')?.value || '';
    const expiryMonth = document.getElementById('card_expiry_month')?.value || '';
    const expiryYear = document.getElementById('card_expiry_year')?.value || '';
    
    if (cardNumber.length < 13 || cardNumber.length > 19) {
        console.error('❌ Invalid card number length:', cardNumber.length);
        missingFields.push('card_number (invalid length)');
        hasValidationErrors = true;
    }
    
    if (cardCvv.length < 3 || cardCvv.length > 4) {
        console.error('❌ Invalid CVV length:', cardCvv.length);
        missingFields.push('card_cvv (invalid length)');
        hasValidationErrors = true;
    }
    
    if (expiryMonth && expiryYear) {
        const expiryDate = new Date(parseInt(expiryYear), parseInt(expiryMonth) - 1);
        if (expiryDate <= new Date()) {
            console.error('❌ Card has expired');
            missingFields.push('card_expiry (expired)');
            hasValidationErrors = true;
        }
    }
    
    // If validation fails, show detailed error
    if (hasValidationErrors) {
        console.error('💥 VALIDATION FAILED');
        console.error('Missing/Invalid fields:', missingFields);
        
        const errorMessage = `Please fix these issues:\n\n${missingFields.map(field => 
            `• ${field.replace(/_/g, ' ').replace(/\b\w/g, l => l.toUpperCase())}`
        ).join('\n')}`;
        
        alert(errorMessage);
        resetButton();
        return;
    }
    
    console.log('✅ All validation checks passed!');
    
    // Disable button immediately
    confirmBtn.disabled = true;
    confirmBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Processing...';
    
    // Update hidden fields
    updateHiddenFields();
    
    // Show loading modal
    let loadingModal;
    try {
        const loadingModalElement = document.getElementById('loadingModal');
        if (loadingModalElement && typeof bootstrap !== 'undefined') {
            loadingModal = new bootstrap.Modal(loadingModalElement);
            loadingModal.show();
            console.log('📱 Loading modal shown');
        }
    } catch (error) {
        console.warn('⚠️ Could not show loading modal:', error);
    }
    
    // Get CSRF token
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || 
                     document.querySelector('input[name="_token"]')?.value;
    
    if (!csrfToken) {
        console.error('❌ CSRF token not found');
        alert('Security token missing. Please refresh the page.');
        hideLoadingModal(loadingModal);
        resetButton();
        return;
    }
    
    console.log('🔐 CSRF token found');
    
    // Prepare form data with debugging info
    const formData = new FormData(form);
    formData.append('ajax_request', '1');
    formData.append('debug_submission', '1');
    formData.append('client_timestamp', new Date().toISOString());
    
    // Log form data (safely)
    console.log('📦 Form data being sent:');
    for (let [key, value] of formData.entries()) {
        if (key.includes('card') && key !== 'card_name') {
            console.log(`  ${key}: ***HIDDEN***`);
        } else {
            console.log(`  ${key}: ${value}`);
        }
    }
    
    // Set timeout
    const controller = new AbortController();
    const timeoutId = setTimeout(() => {
        console.warn('⏰ Request timeout after 30 seconds');
        controller.abort();
    }, 30000);
    
    console.log('📡 Sending request to:', form.action);
    console.log('📡 Request method: POST');
    console.log('📡 Content type: multipart/form-data');
    
    // Submit via fetch with enhanced error handling
    fetch(form.action, {
        method: 'POST',
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': csrfToken,
            'Accept': 'application/json'
        },
        signal: controller.signal
    })
    .then(response => {
        clearTimeout(timeoutId);
        console.log('📡 Response received!');
        console.log('📡 Status:', response.status, response.statusText);
        console.log('📡 Headers:', [...response.headers.entries()]);
        
        if (!response.ok) {
            if (response.status === 422) {
                throw new Error('VALIDATION_ERROR');
            } else if (response.status === 419) {
                throw new Error('CSRF_ERROR');
            } else if (response.status === 500) {
                throw new Error('SERVER_ERROR');
            } else {
                throw new Error(`HTTP_ERROR_${response.status}`);
            }
        }
        
        const contentType = response.headers.get('content-type');
        console.log('📄 Content type:', contentType);
        
        if (contentType && contentType.includes('application/json')) {
            return response.json();
        } else {
            // Handle non-JSON response
            return response.text().then(text => {
                console.log('📄 Non-JSON response received (first 1000 chars):');
                console.log(text.substring(0, 1000));
                
                if (response.redirected) {
                    console.log('🔄 Response was redirected to:', response.url);
                    window.location.href = response.url;
                    return null;
                }
                
                // Check for Laravel validation errors in HTML
                if (text.includes('validation-errors') || text.includes('alert-danger')) {
                    throw new Error('HTML_VALIDATION_ERROR');
                }
                
                throw new Error('UNEXPECTED_HTML_RESPONSE');
            });
        }
    })
    .then(data => {
        console.log('✅ Response data received:', data);
        hideLoadingModal(loadingModal);
        
        if (!data) {
            // Redirect was handled
            console.log('🔄 Redirect handled, stopping processing');
            return;
        }
        
        if (data.success) {
            console.log('🎉 SUCCESS! Booking created successfully!');
            console.log('📋 Booking ID:', data.data?.booking_id);
            console.log('📋 Booking Reference:', data.data?.booking_reference);
            
            showSuccessMessage(data.data?.booking_reference || data.data?.booking_id);
            
            // Redirect after delay if URL provided
            if (data.data?.redirect_url) {
                console.log('🔄 Will redirect to:', data.data.redirect_url);
                setTimeout(() => {
                    window.location.href = data.data.redirect_url;
                }, 3000);
            }
        } else {
            console.error('❌ Server rejected the booking');
            console.error('❌ Error message:', data.message);
            console.error('❌ Full response:', data);
            
            let errorMessage = data.message || 'Failed to create booking';
            
            if (data.errors) {
                console.error('❌ Validation errors:', data.errors);
                const errorsList = Object.entries(data.errors).map(([field, messages]) => {
                    const fieldMessages = Array.isArray(messages) ? messages : [messages];
                    return `${field.replace(/_/g, ' ')}: ${fieldMessages.join(', ')}`;
                });
                errorMessage = 'Server validation errors:\n\n' + errorsList.join('\n');
            }
            
            alert(errorMessage);
            resetButton();
        }
    })
    .catch(error => {
        clearTimeout(timeoutId);
        hideLoadingModal(loadingModal);
        
        console.error('💥 REQUEST FAILED:', error.message);
        console.error('💥 Error details:', error);
        
        let userMessage = 'Booking submission failed:\n\n';
        
        switch (error.message) {
            case 'VALIDATION_ERROR':
                userMessage += 'Server validation failed. Please check all fields are filled correctly and try again.';
                break;
            case 'CSRF_ERROR':
                userMessage += 'Security token expired. Please refresh the page and try again.';
                break;
            case 'SERVER_ERROR':
                userMessage += 'Server error occurred. Please try again in a few moments.';
                break;
            case 'HTML_VALIDATION_ERROR':
                userMessage += 'Form validation failed. Please check all required fields.';
                break;
            case 'UNEXPECTED_HTML_RESPONSE':
                userMessage += 'Server returned an unexpected response. Trying fallback submission...';
                // Try normal form submission as fallback
                setTimeout(() => {
                    console.log('🔄 Attempting fallback form submission...');
                    form.removeEventListener('submit', handleFormSubmission);
                    resetButton();
                    form.submit();
                }, 1000);
                return;
            default:
                if (error.name === 'AbortError') {
                    userMessage += 'Request timed out after 30 seconds. Please check your connection and try again.';
                } else {
                    userMessage += `Network error: ${error.message}\n\nPlease check your internet connection and try again.`;
                }
        }
        
        alert(userMessage);
        resetButton();
    });
}

function resetButton() {
    isFormSubmitting = false;
    const confirmBtn = document.getElementById('confirm-booking-btn');
    if (confirmBtn) {
        confirmBtn.disabled = false;
        confirmBtn.innerHTML = '<i class="fas fa-lock me-2"></i>Confirm & Pay Now';
    }
}

function hideLoadingModal(modalInstance) {
    try {
        if (modalInstance) {
            modalInstance.hide();
        } else {
            const modalElement = document.getElementById('loadingModal');
            if (modalElement && typeof bootstrap !== 'undefined') {
                const instance = bootstrap.Modal.getInstance(modalElement);
                if (instance) {
                    instance.hide();
                }
            }
        }
    } catch (error) {
        console.warn('⚠️ Could not hide loading modal:', error);
    }
}

function updateHiddenFields() {
    try {
        // Update total amount
        const totalAmountElement = document.getElementById('total-amount');
        const totalAmountField = document.getElementById('final_total_amount');
        
        if (totalAmountElement && totalAmountField) {
            const totalAmount = totalAmountElement.textContent.replace(/[^\d]/g, '') || '0';
            totalAmountField.value = totalAmount;
            console.log('💰 Total Amount set to:', totalAmount);
        }
        
        // Update duration
        const durationElement = document.getElementById('booking-duration');
        const durationField = document.getElementById('final_duration_days');
        
        if (durationElement && durationField) {
            const durationText = durationElement.textContent || '1 month';
            const months = parseInt(durationText.split(' ')[0]) || 1;
            const durationDays = months * 30;
            durationField.value = durationDays;
            console.log('📅 Duration set to:', durationDays, 'days');
        }
    } catch (error) {
        console.error('❌ Error updating hidden fields:', error);
    }
}

function showSuccessMessage(bookingId = null) {
    console.log('🎉 Showing success message for booking:', bookingId);
    
    // Create success modal
    const successModal = document.createElement('div');
    successModal.className = 'modal fade';
    successModal.setAttribute('data-bs-backdrop', 'static');
    successModal.setAttribute('data-bs-keyboard', 'false');
    successModal.innerHTML = `
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body text-center py-5">
                    <div class="text-success mb-3">
                        <i class="fas fa-check-circle" style="font-size: 4rem;"></i>
                    </div>
                    <h4 class="text-success mb-3">Booking Confirmed!</h4>
                    <p class="mb-4">Your booking has been successfully confirmed and payment processed. You will receive a confirmation email shortly.</p>
                    ${bookingId ? `<p class="text-muted mb-4">Booking Reference: <strong>${bookingId}</strong></p>` : ''}
                    <div class="d-grid gap-2">
                        <a href="/student/bookings" class="btn btn-primary btn-lg">View My Bookings</a>
                        <a href="/hostels" class="btn btn-outline-primary">Browse More Hostels</a>
                    </div>
                </div>
            </div>
        </div>
    `;
    
    document.body.appendChild(successModal);
    
    try {
        if (typeof bootstrap !== 'undefined') {
            const modal = new bootstrap.Modal(successModal);
            modal.show();
            
            // Remove modal from DOM when hidden
            successModal.addEventListener('hidden.bs.modal', function() {
                if (document.body.contains(successModal)) {
                    document.body.removeChild(successModal);
                }
            });
        }
    } catch (error) {
        console.error('Error showing success modal:', error);
        // Fallback: just redirect
        setTimeout(() => {
            window.location.href = '/student/bookings';
        }, 2000);
    }
    
    // Auto-redirect after 5 seconds
    setTimeout(() => {
        window.location.href = '/student/bookings';
    }, 5000);
}

// Step navigation functions
function nextStep(step) {
    console.log('➡️ Moving to step:', step);
    
    // Get current step
    const currentStepElement = document.querySelector('.step-content:not(.d-none)');
    if (!currentStepElement) {
        console.error('❌ No current step found');
        return;
    }
    
    const currentStepNumber = parseInt(currentStepElement.id.split('-')[1]);
    
    // Validate current step
    if (!validateStep(currentStepNumber)) {
        showValidationError(currentStepElement);
        return;
    }
    
    // Hide all steps
    document.querySelectorAll('.step-content').forEach(function(content) {
        content.classList.add('d-none');
    });
    
    // Show target step
    const targetStep = document.getElementById('step-' + step);
    if (targetStep) {
        targetStep.classList.remove('d-none');
    }
    
    // Update progress
    updateProgress(step);
    
    // Update summary if going to confirmation step
    if (step === 4) {
        updateBookingSummary();
        updateHiddenFields();
    }
    
    // Scroll to top
    window.scrollTo({ top: 0, behavior: 'smooth' });
}

function prevStep(step) {
    console.log('⬅️ Moving back to step:', step);
    
    // Hide all steps
    document.querySelectorAll('.step-content').forEach(function(content) {
        content.classList.add('d-none');
    });
    
    // Show target step
    const targetStep = document.getElementById('step-' + step);
    if (targetStep) {
        targetStep.classList.remove('d-none');
    }
    
    // Update progress
    updateProgress(step);
    
    // Scroll to top
    window.scrollTo({ top: 0, behavior: 'smooth' });
}

function validateStep(stepNumber) {
    const stepElement = document.getElementById(`step-${stepNumber}`);
    if (!stepElement) return true;
    
    const requiredInputs = stepElement.querySelectorAll('input[required], select[required]');
    let isValid = true;
    
    requiredInputs.forEach(input => {
        input.classList.remove('is-invalid', 'is-valid');
        
        if (input.type === 'radio') {
            const radioGroup = stepElement.querySelectorAll(`input[name="${input.name}"]`);
            const isRadioSelected = Array.from(radioGroup).some(radio => radio.checked);
            if (!isRadioSelected) {
                radioGroup.forEach(radio => {
                    const label = radio.closest('.package-option, .btn');
                    if (label) label.classList.add('border-danger');
                });
                isValid = false;
            } else {
                radioGroup.forEach(radio => {
                    const label = radio.closest('.package-option, .btn');
                    if (label) label.classList.remove('border-danger');
                });
            }
        } else if (input.type === 'checkbox') {
            if (!input.checked) {
                input.classList.add('is-invalid');
                isValid = false;
            } else {
                input.classList.add('is-valid');
            }
        } else if (!input.value.trim()) {
            input.classList.add('is-invalid');
            isValid = false;
        } else {
            input.classList.add('is-valid');
        }
    });
    
    // Special validation for payment step
    if (stepNumber === 3) {
        const cardNumber = document.getElementById('card_number')?.value.replace(/\s/g, '') || '';
        const cardCvv = document.getElementById('card_cvv')?.value || '';
        const expiryMonth = document.getElementById('card_expiry_month')?.value || '';
        const expiryYear = document.getElementById('card_expiry_year')?.value || '';
        
        if (cardNumber.length < 13 || cardNumber.length > 19) {
            document.getElementById('card_number')?.classList.add('is-invalid');
            isValid = false;
        }
        
        if (cardCvv.length < 3 || cardCvv.length > 4) {
            document.getElementById('card_cvv')?.classList.add('is-invalid');
            isValid = false;
        }
        
        if (expiryMonth && expiryYear) {
            const currentDate = new Date();
            const expiryDate = new Date(parseInt(expiryYear), parseInt(expiryMonth) - 1);
            if (expiryDate <= currentDate) {
                document.getElementById('card_expiry_month')?.classList.add('is-invalid');
                document.getElementById('card_expiry_year')?.classList.add('is-invalid');
                isValid = false;
            }
        }
    }
    
    return isValid;
}

function showValidationError(stepElement) {
    const alertDiv = document.createElement('div');
    alertDiv.className = 'alert alert-danger alert-dismissible fade show mt-3';
    alertDiv.innerHTML = `
        <strong>Validation Error!</strong> Please fill in all required fields correctly.
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;
    
    // Remove existing alerts
    const existingAlerts = stepElement.querySelectorAll('.alert-danger');
    existingAlerts.forEach(alert => alert.remove());
    
    // Add new alert
    const cardBody = stepElement.querySelector('.card-body');
    if (cardBody) {
        cardBody.appendChild(alertDiv);
        
        // Auto-remove alert after 5 seconds
        setTimeout(() => {
            if (alertDiv.parentNode) {
                alertDiv.remove();
            }
        }, 5000);
    }
}

function updateProgress(activeStep) {
    document.querySelectorAll('.step').forEach(function(step, index) {
        const stepNumber = index + 1;
        step.classList.remove('active', 'completed');
        
        if (stepNumber === activeStep) {
            step.classList.add('active');
        } else if (stepNumber < activeStep) {
            step.classList.add('completed');
        }
    });
}

function updatePricing() {
    const checkInInput = document.getElementById('check_in_date');
    const checkOutInput = document.getElementById('check_out_date');
    const selectedPackage = document.querySelector('input[name="package_id"]:checked');
    
    if (!checkInInput || !checkOutInput || !selectedPackage) {
        return;
    }
    
    const checkIn = checkInInput.value;
    const checkOut = checkOutInput.value;
    
    if (checkIn && checkOut) {
        const checkInDate = new Date(checkIn);
        const checkOutDate = new Date(checkOut);
        const timeDiff = checkOutDate.getTime() - checkInDate.getTime();
        const daysDiff = Math.ceil(timeDiff / (1000 * 3600 * 24));
        const months = Math.max(1, Math.ceil(daysDiff / 30));
        
        const monthlyRate = parseInt(selectedPackage.dataset.price) || 0;
        const subtotal = monthlyRate * months;
        const serviceFee = 1000;
        const total = subtotal + serviceFee;
        
        // Update sidebar elements
        const elements = {
            'selected-package': selectedPackage.closest('.package-option')?.querySelector('strong')?.textContent || 'Unknown',
            'booking-duration': months + ' month' + (months > 1 ? 's' : ''),
            'monthly-rate': 'LKR ' + monthlyRate.toLocaleString(),
            'subtotal': 'LKR ' + subtotal.toLocaleString(),
            'total-amount': 'LKR ' + total.toLocaleString()
        };
        
        Object.entries(elements).forEach(([id, value]) => {
            const element = document.getElementById(id);
            if (element) {
                element.textContent = value;
            }
        });
        
        console.log('💵 Pricing updated:', { months, monthlyRate, subtotal, serviceFee, total });
    }
}

function updateBookingSummary() {
    const checkIn = document.getElementById('check_in_date')?.value;
    const checkOut = document.getElementById('check_out_date')?.value;
    const selectedPackage = document.querySelector('input[name="package_id"]:checked');
    const emergencyName = document.getElementById('emergency_contact_name')?.value;
    const emergencyPhone = document.getElementById('emergency_contact_phone')?.value;
    const paymentMethod = document.querySelector('input[name="payment_method"]:checked');
    const cardNumber = document.getElementById('card_number')?.value;
    const totalAmount = document.getElementById('total-amount')?.textContent;
    
    if (!checkIn || !checkOut || !selectedPackage || !emergencyName || !paymentMethod || !cardNumber) {
        console.warn('⚠️ Missing data for booking summary');
        return;
    }
    
    const summaryHTML = `
        <div class="booking-summary-details">
            <div class="row mb-4">
                <div class="col-12">
                    <div class="card bg-light">
                        <div class="card-body">
                            <h6 class="card-title mb-3"><i class="fas fa-calendar-alt me-2"></i>Booking Details</h6>
                            <div class="row mb-2">
                                <div class="col-6"><strong>Check-in:</strong></div>
                                <div class="col-6">${new Date(checkIn).toLocaleDateString('en-US', { weekday: 'short', year: 'numeric', month: 'short', day: 'numeric' })}</div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-6"><strong>Check-out:</strong></div>
                                <div class="col-6">${new Date(checkOut).toLocaleDateString('en-US', { weekday: 'short', year: 'numeric', month: 'short', day: 'numeric' })}</div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-6"><strong>Package:</strong></div>
                                <div class="col-6">${selectedPackage.closest('.package-option')?.querySelector('strong')?.textContent || 'Unknown'}</div>
                            </div>
                            <div class="row">
                                <div class="col-6"><strong>Total Amount:</strong></div>
                                <div class="col-6"><strong class="text-primary">${totalAmount || 'LKR 0'}</strong></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="row mb-4">
                <div class="col-12">
                    <div class="card bg-light">
                        <div class="card-body">
                            <h6 class="card-title mb-3"><i class="fas fa-user me-2"></i>Contact Information</h6>
                            <div class="row mb-2">
                                <div class="col-6"><strong>Emergency Contact:</strong></div>
                                <div class="col-6">${emergencyName}</div>
                            </div>
                            <div class="row">
                                <div class="col-6"><strong>Phone:</strong></div>
                                <div class="col-6">${emergencyPhone || 'Not provided'}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="row mb-4">
                <div class="col-12">
                    <div class="card bg-light">
                        <div class="card-body">
                            <h6 class="card-title mb-3"><i class="fas fa-credit-card me-2"></i>Payment Information</h6>
                            <div class="row mb-2">
                                <div class="col-6"><strong>Payment Method:</strong></div>
                                <div class="col-6">${paymentMethod.value.charAt(0).toUpperCase() + paymentMethod.value.slice(1)}</div>
                            </div>
                            <div class="row">
                                <div class="col-6"><strong>Card Number:</strong></div>
                                <div class="col-6">****${cardNumber.replace(/\s/g, '').slice(-4)}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    `;
    
    const summaryElement = document.getElementById('booking-summary');
    if (summaryElement) {
        summaryElement.innerHTML = summaryHTML;
    }
}

// Emergency fallback function - can be called from console if needed
window.forceSubmitBooking = function() {
    console.log('🚨 Force submitting booking...');
    
    const form = document.getElementById('bookingForm');
    const terms = document.getElementById('terms_accepted');
    
    if (!form) {
        alert('Form not found. Please refresh the page.');
        return;
    }
    
    if (!terms || !terms.checked) {
        alert('Please accept the Terms & Conditions first.');
        return;
    }
    
    updateHiddenFields();
    
    // Submit normally as fallback
    form.submit();
};

// Debug function to check form data
window.debugFormData = function() {
    console.log('🔍 DEBUGGING FORM DATA');
    
    const form = document.getElementById('bookingForm');
    if (!form) {
        console.error('❌ Form not found');
        return;
    }
    
    const formData = new FormData(form);
    
    console.log('=== FORM DATA ===');
    for (let [key, value] of formData.entries()) {
        if (key.includes('card') && key !== 'card_name') {
            console.log(`${key}: ***HIDDEN***`);
        } else {
            console.log(`${key}: ${value}`);
        }
    }
    
    console.log('=== ELEMENT VALUES ===');
    const elements = [
        'hostel_id', 'check_in_date', 'check_out_date', 'emergency_contact_name',
        'emergency_contact_phone', 'card_name', 'card_number', 'card_cvv',
        'card_expiry_month', 'card_expiry_year', 'terms_accepted'
    ];
    
    elements.forEach(id => {
        const el = document.getElementById(id) || document.querySelector(`input[name="${id}"]:checked`);
        if (el) {
            const value = el.type === 'checkbox' ? el.checked : el.value;
            console.log(`${id}: ${id.includes('card') && id !== 'card_name' ? '***' : value}`);
        } else {
            console.error(`❌ Element not found: ${id}`);
        }
    });
    
    // Check package selection
    const packageEl = document.querySelector('input[name="package_id"]:checked');
    console.log(`package_id: ${packageEl ? packageEl.value : 'NOT SELECTED'}`);
    
    // Check payment method
    const paymentEl = document.querySelector('input[name="payment_method"]:checked');
    console.log(`payment_method: ${paymentEl ? paymentEl.value : 'NOT SELECTED'}`);
    
    console.log('=== VALIDATION STATUS ===');
    console.log('Form action:', form.action);
    console.log('CSRF token:', document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') ? 'FOUND' : 'MISSING');
    console.log('Current step:', document.querySelector('.step-content:not(.d-none)')?.id || 'UNKNOWN');
};

// Quick test function
window.testBookingSubmission = function() {
    console.log('🧪 TESTING BOOKING SUBMISSION');
    
    // Fill in test data
    document.getElementById('check_in_date').value = '2025-08-01';
    document.getElementById('check_out_date').value = '2025-08-31';
    document.querySelector('input[name="package_id"][value="1"]').checked = true;
    document.getElementById('emergency_contact_name').value = 'Test Contact';
    document.getElementById('emergency_contact_phone').value = '0771234567';
    document.querySelector('input[name="payment_method"][value="visa"]').checked = true;
    document.getElementById('card_number').value = '4242 4242 4242 4242';
    document.getElementById('card_name').value = 'Test User';
    document.getElementById('card_cvv').value = '123';
    document.getElementById('card_expiry_month').value = '12';
    document.getElementById('card_expiry_year').value = '2028';
    document.getElementById('terms_accepted').checked = true;
    
    // Update pricing
    updatePricing();
    updateHiddenFields();
    
    console.log('✅ Test data filled. Navigate to step 4 and click submit.');
    
    // Go to step 4
    nextStep(4);
};

console.log('🎯 Booking form script loaded successfully');
console.log('💡 Debug commands available:');
console.log('  debugFormData() - Check current form data');
console.log('  testBookingSubmission() - Fill test data and go to step 4');
console.log('  forceSubmitBooking() - Force submit current form');
</script>

<!-- Quick Debug Panel (remove in production) -->
@if(config('app.debug'))
<div class="position-fixed bottom-0 end-0 p-3" style="z-index: 1050;">
    <div class="card bg-dark text-white" style="width: 300px;">
        <div class="card-header py-2">
            <small>Debug Panel</small>
        </div>
        <div class="card-body py-2">
            <div class="d-grid gap-1">
                <button class="btn btn-sm btn-outline-light" onclick="debugFormData()">
                    Check Form Data
                </button>
                <button class="btn btn-sm btn-outline-light" onclick="testBookingSubmission()">
                    Fill Test Data
                </button>
                <button class="btn btn-sm btn-outline-warning" onclick="forceSubmitBooking()">
                    Force Submit
                </button>
            </div>
            <div class="mt-2">
                <small class="text-muted">
                    Check browser console for detailed logs
                </small>
            </div>
        </div>
    </div>
</div>
@endif
</script>
@endpush