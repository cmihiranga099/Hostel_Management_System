@extends('layouts.app')

@section('title', 'Payment - University Hostel Management System')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <!-- Payment Header -->
            <div class="card-custom mb-4">
                <div class="card-header-custom text-center">
                    <h4 class="mb-0">
                        <i class="fas fa-credit-card me-2"></i>Complete Your Payment
                    </h4>
                    <p class="mb-0 mt-2 opacity-75">Secure payment powered by our secure gateway</p>
                </div>
            </div>

            <div class="row">
                <!-- Booking Summary -->
                <div class="col-lg-5 mb-4">
                    <div class="card-custom">
                        <div class="card-header-custom">
                            <h5 class="mb-0">
                                <i class="fas fa-receipt me-2"></i>Booking Summary
                            </h5>
                        </div>
                        <div class="card-body-custom">
                            <div class="booking-summary">
                                <div class="hostel-info mb-4">
                                    <img src="{{ $booking->hostelPackage->image_url ?? '/images/hostels/default-room.jpg' }}" alt="{{ $booking->hostelPackage->name }}" class="img-fluid rounded mb-3" style="width: 100%; height: 200px; object-fit: cover;">
                                    <h6 class="fw-bold">{{ $booking->hostelPackage->name }}</h6>
                                    <p class="text-muted mb-0">{{ $booking->hostelPackage->type_display ?? 'Standard Room' }}</p>
                                </div>
                                
                                <div class="booking-details">
                                    <div class="detail-row d-flex justify-content-between mb-2">
                                        <span class="text-muted">Booking Reference:</span>
                                        <span class="fw-bold">{{ $booking->booking_reference }}</span>
                                    </div>
                                    
                                    <div class="detail-row d-flex justify-content-between mb-2">
                                        <span class="text-muted">Check-in Date:</span>
                                        <span>{{ $booking->check_in_date->format('M d, Y') }}</span>
                                    </div>
                                    
                                    <div class="detail-row d-flex justify-content-between mb-2">
                                        <span class="text-muted">Check-out Date:</span>
                                        <span>{{ $booking->check_out_date->format('M d, Y') }}</span>
                                    </div>
                                    
                                    <div class="detail-row d-flex justify-content-between mb-2">
                                        <span class="text-muted">Duration:</span>
                                        <span>{{ $booking->duration }} days</span>
                                    </div>
                                    
                                    <div class="detail-row d-flex justify-content-between mb-2">
                                        <span class="text-muted">Guest Name:</span>
                                        <span>{{ $booking->user->name }}</span>
                                    </div>
                                    
                                    <hr class="my-3">
                                    
                                    <div class="pricing-breakdown">
                                        <div class="detail-row d-flex justify-content-between mb-2">
                                            <span>Accommodation Fee:</span>
                                            <span>{{ $booking->formatted_amount ?? 'LKR ' . number_format($booking->amount, 2) }}</span>
                                        </div>
                                        
                                        <div class="detail-row d-flex justify-content-between mb-2">
                                            <span>Service Fee:</span>
                                            <span>LKR 0.00</span>
                                        </div>
                                        
                                        <div class="detail-row d-flex justify-content-between mb-2">
                                            <span>Taxes:</span>
                                            <span>Included</span>
                                        </div>
                                        
                                        <hr class="my-3">
                                        
                                        <div class="detail-row d-flex justify-content-between">
                                            <span class="fw-bold fs-5">Total Amount:</span>
                                            <span class="fw-bold fs-5 text-primary">{{ $booking->formatted_amount ?? 'LKR ' . number_format($booking->amount, 2) }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Payment Form -->
                <div class="col-lg-7">
                    <div class="payment-form">
                        <form id="payment-form" class="needs-validation" novalidate>
                            @csrf
                            <input type="hidden" name="booking_id" value="{{ $booking->id }}">
                            <input type="hidden" name="amount" value="{{ $booking->amount }}">
                            
                            <!-- Payment Summary -->
                            <div class="payment-summary mb-4">
                                <div class="d-flex justify-content-between align-items-center p-3">
                                    <span class="fw-bold">Amount to Pay:</span>
                                    <span class="fw-bold fs-4 text-primary">{{ $booking->formatted_amount ?? 'LKR ' . number_format($booking->amount, 2) }}</span>
                                </div>
                            </div>

                            <!-- Payment Method Selection -->
                            <div class="mb-4">
                                <h6 class="mb-3">
                                    <i class="fas fa-credit-card me-2"></i>Payment Method
                                </h6>
                                
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="payment_method" id="visa" value="visa" checked>
                                            <label class="form-check-label" for="visa">
                                                <i class="fab fa-cc-visa me-2"></i>Visa
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="payment_method" id="mastercard" value="mastercard">
                                            <label class="form-check-label" for="mastercard">
                                                <i class="fab fa-cc-mastercard me-2"></i>Mastercard
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="payment_method" id="amex" value="amex">
                                            <label class="form-check-label" for="amex">
                                                <i class="fab fa-cc-amex me-2"></i>American Express
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="payment_method" id="bank_transfer" value="bank_transfer">
                                            <label class="form-check-label" for="bank_transfer">
                                                <i class="fas fa-university me-2"></i>Bank Transfer
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Cardholder Information -->
                            <div class="mb-4">
                                <h6 class="mb-3">
                                    <i class="fas fa-user me-2"></i>Cardholder Information
                                </h6>
                                
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="card_name" class="form-label-custom">Cardholder Name *</label>
                                        <input 
                                            type="text" 
                                            class="form-control form-control-custom" 
                                            id="card_name" 
                                            name="card_name" 
                                            value="{{ $booking->user->name }}" 
                                            required
                                            placeholder="Name on card"
                                        >
                                    </div>
                                    
                                    <div class="col-md-6 mb-3">
                                        <label for="email" class="form-label-custom">Email Address *</label>
                                        <input 
                                            type="email" 
                                            class="form-control form-control-custom" 
                                            id="email" 
                                            name="email" 
                                            value="{{ $booking->user->email }}" 
                                            required
                                            placeholder="Email for receipt"
                                        >
                                    </div>
                                </div>
                            </div>

                            <!-- Card Information -->
                            <div class="mb-4" id="card-fields">
                                <h6 class="mb-3">
                                    <i class="fas fa-credit-card me-2"></i>Card Information
                                </h6>
                                
                                <div class="mb-3">
                                    <label for="card_number" class="form-label-custom">Card Number *</label>
                                    <input 
                                        type="text" 
                                        class="form-control form-control-custom" 
                                        id="card_number" 
                                        name="card_number" 
                                        required
                                        placeholder="1234 5678 9012 3456"
                                        maxlength="19"
                                    >
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="card_expiry_month" class="form-label-custom">Expiry Month *</label>
                                        <select class="form-control form-control-custom" id="card_expiry_month" name="card_expiry_month" required>
                                            <option value="">Month</option>
                                            @for($i = 1; $i <= 12; $i++)
                                                <option value="{{ str_pad($i, 2, '0', STR_PAD_LEFT) }}">{{ str_pad($i, 2, '0', STR_PAD_LEFT) }}</option>
                                            @endfor
                                        </select>
                                    </div>
                                    
                                    <div class="col-md-6 mb-3">
                                        <label for="card_expiry_year" class="form-label-custom">Expiry Year *</label>
                                        <select class="form-control form-control-custom" id="card_expiry_year" name="card_expiry_year" required>
                                            <option value="">Year</option>
                                            @for($i = date('Y'); $i <= date('Y') + 10; $i++)
                                                <option value="{{ $i }}">{{ $i }}</option>
                                            @endfor
                                        </select>
                                    </div>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="card_cvv" class="form-label-custom">CVV *</label>
                                    <input 
                                        type="text" 
                                        class="form-control form-control-custom" 
                                        id="card_cvv" 
                                        name="card_cvv" 
                                        required
                                        placeholder="123"
                                        maxlength="4"
                                    >
                                    <small class="form-text text-muted">3 digits for Visa/Mastercard, 4 digits for American Express</small>
                                </div>
                            </div>

                            <!-- Bank Transfer Fields (Hidden by default) -->
                            <div class="mb-4" id="bank-fields" style="display: none;">
                                <h6 class="mb-3">
                                    <i class="fas fa-university me-2"></i>Bank Transfer Details
                                </h6>
                                
                                <div class="alert alert-info">
                                    <strong>Bank Transfer Instructions:</strong><br>
                                    Please transfer the amount to:<br>
                                    <strong>Bank:</strong> Commercial Bank of Ceylon<br>
                                    <strong>Account:</strong> 1234567890<br>
                                    <strong>Reference:</strong> {{ $booking->booking_reference }}<br>
                                    <strong>Amount:</strong> {{ $booking->formatted_amount ?? 'LKR ' . number_format($booking->amount, 2) }}
                                </div>
                            </div>

                            <!-- Security Notice -->
                            <div class="security-notice mb-4">
                                <div class="alert alert-info d-flex align-items-center">
                                    <i class="fas fa-shield-alt me-3" style="font-size: 1.5rem;"></i>
                                    <div>
                                        <strong>Secure Payment</strong><br>
                                        <small>Your payment information is encrypted and secure. We use industry-leading security standards.</small>
                                    </div>
                                </div>
                            </div>

                            <!-- Terms and Conditions -->
                            <div class="mb-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="terms" required>
                                    <label class="form-check-label" for="terms">
                                        I agree to the <a href="#" class="text-decoration-none">Terms and Conditions</a> and <a href="#" class="text-decoration-none">Cancellation Policy</a> *
                                    </label>
                                </div>
                            </div>

                            <!-- Submit Button -->
                            <div class="d-grid">
                                <button type="submit" id="submit-payment" class="btn btn-primary-custom btn-lg">
                                    <span id="payment-spinner" class="spinner-border spinner-border-sm d-none me-2"></span>
                                    <i class="fas fa-lock me-2"></i>Pay Securely - {{ $booking->formatted_amount ?? 'LKR ' . number_format($booking->amount, 2) }}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Payment Methods -->
            <div class="row mt-4">
                <div class="col-12">
                    <div class="card-custom">
                        <div class="card-body-custom text-center">
                            <h6 class="mb-3">We Accept</h6>
                            <div class="payment-methods">
                                <img src="{{ asset('images/payments/visa.png') }}" alt="Visa" class="payment-method-icon me-3">
                                <img src="{{ asset('images/payments/mastercard.png') }}" alt="Mastercard" class="payment-method-icon me-3">
                                <img src="{{ asset('images/payments/amex.png') }}" alt="American Express" class="payment-method-icon me-3">
                                <img src="{{ asset('images/payments/bank.png') }}" alt="Bank Transfer" class="payment-method-icon">
                            </div>
                            <p class="text-muted mt-3 mb-0">
                                <small>
                                    <i class="fas fa-shield-alt me-1"></i>
                                    256-bit SSL encryption • PCI DSS compliant • Secure payment processing
                                </small>
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Help Section -->
            <div class="row mt-4">
                <div class="col-12">
                    <div class="card-custom">
                        <div class="card-body-custom">
                            <h6 class="mb-3">
                                <i class="fas fa-question-circle me-2"></i>Need Help?
                            </h6>
                            <p class="mb-3">If you're having trouble with your payment, our support team is here to help.</p>
                            <div class="row">
                                <div class="col-md-4 mb-2">
                                    <a href="{{ route('contact') ?? '#' }}" class="btn btn-outline-primary btn-sm w-100">
                                        <i class="fas fa-envelope me-1"></i>Contact Support
                                    </a>
                                </div>
                                <div class="col-md-4 mb-2">
                                    <a href="tel:+94112345678" class="btn btn-outline-primary btn-sm w-100">
                                        <i class="fas fa-phone me-1"></i>Call Us
                                    </a>
                                </div>
                                <div class="col-md-4 mb-2">
                                    <a href="#" class="btn btn-outline-primary btn-sm w-100">
                                        <i class="fas fa-comments me-1"></i>Live Chat
                                    </a>
                                </div>
                            </div>
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
    .payment-form {
        background: var(--white);
        border-radius: 20px;
        box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
        padding: 2rem;
    }
    
    .payment-summary {
        background: linear-gradient(135deg, var(--light-blue), var(--light-orange));
        border-radius: 15px;
        color: var(--white);
    }
    
    .payment-method-icon {
        height: 30px;
        opacity: 0.8;
        transition: opacity 0.3s ease;
    }
    
    .payment-method-icon:hover {
        opacity: 1;
    }
    
    .security-notice {
        background: #f8f9fa;
        border-radius: 10px;
    }
    
    .detail-row {
        padding: 0.25rem 0;
    }
    
    .hostel-info img {
        transition: transform 0.3s ease;
    }
    
    .hostel-info img:hover {
        transform: scale(1.02);
    }
    
    .form-check-input:checked {
        background-color: var(--primary-color);
        border-color: var(--primary-color);
    }
    
    @media (max-width: 768px) {
        .payment-form {
            padding: 1.5rem;
        }
        
        .payment-method-icon {
            height: 25px;
            margin: 0.25rem;
        }
    }
</style>
@endpush

@push('scripts')
@vite(['resources/js/payment-form.js'])
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Payment method toggle
    const paymentMethodInputs = document.querySelectorAll('input[name="payment_method"]');
    const cardFields = document.getElementById('card-fields');
    const bankFields = document.getElementById('bank-fields');
    
    function togglePaymentFields() {
        const selectedMethod = document.querySelector('input[name="payment_method"]:checked').value;
        
        if (selectedMethod === 'bank_transfer') {
            cardFields.style.display = 'none';
            bankFields.style.display = 'block';
        } else {
            cardFields.style.display = 'block';
            bankFields.style.display = 'none';
        }
    }
    
    paymentMethodInputs.forEach(input => {
        input.addEventListener('change', togglePaymentFields);
    });
    
    // Initialize on page load
    togglePaymentFields();
    
    // Card number formatting
    const cardNumberInput = document.getElementById('card_number');
    if (cardNumberInput) {
        cardNumberInput.addEventListener('input', function(e) {
            let value = e.target.value.replace(/\s/g, '').replace(/[^0-9]/gi, '');
            let formattedValue = value.match(/.{1,4}/g)?.join(' ') || value;
            e.target.value = formattedValue;
        });
    }
    
    // CVV formatting
    const cvvInput = document.getElementById('card_cvv');
    if (cvvInput) {
        cvvInput.addEventListener('input', function(e) {
            e.target.value = e.target.value.replace(/\D/g, '').substring(0, 4);
        });
    }
});
</script>
@endpush