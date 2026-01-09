@extends('layouts.app')

@section('title', 'Register - University Hostel Management System')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-7">
            <div class="card-custom">
                <div class="card-header-custom text-center">
                    <h4 class="mb-0">
                        <i class="fas fa-user-plus me-2"></i>Student Registration
                    </h4>
                    <p class="mb-0 mt-2 opacity-75">Create your account to book university hostels</p>
                </div>
                
                <div class="card-body-custom">
                    <form method="POST" action="{{ route('register') }}" class="needs-validation" novalidate>
                        @csrf

                        <!-- Personal Information Section -->
                        <div class="section-header mb-4">
                            <h6 class="section-title">
                                <i class="fas fa-user me-2"></i>Personal Information
                            </h6>
                        </div>

                        <div class="row">
                            <!-- Full Name -->
                            <div class="col-md-12 mb-3">
                                <label for="name" class="form-label-custom">
                                    <i class="fas fa-user me-2"></i>Full Name *
                                </label>
                                <input 
                                    id="name" 
                                    type="text" 
                                    class="form-control form-control-custom @error('name') is-invalid @enderror" 
                                    name="name" 
                                    value="{{ old('name') }}" 
                                    required 
                                    autocomplete="name" 
                                    autofocus
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
                                    id="email" 
                                    type="email" 
                                    class="form-control form-control-custom @error('email') is-invalid @enderror" 
                                    name="email" 
                                    value="{{ old('email') }}" 
                                    required 
                                    autocomplete="email"
                                    placeholder="your.email@university.lk"
                                >
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Phone -->
                            <div class="col-md-6 mb-3">
                                <label for="phone" class="form-label-custom">
                                    <i class="fas fa-phone me-2"></i>Phone Number *
                                </label>
                                <input 
                                    id="phone" 
                                    type="tel" 
                                    class="form-control form-control-custom @error('phone') is-invalid @enderror" 
                                    name="phone" 
                                    value="{{ old('phone') }}" 
                                    required
                                    placeholder="077 123 4567"
                                >
                                @error('phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- NIC -->
                            <div class="col-md-6 mb-3">
                                <label for="nic" class="form-label-custom">
                                    <i class="fas fa-id-card me-2"></i>NIC Number *
                                </label>
                                <input 
                                    id="nic" 
                                    type="text" 
                                    class="form-control form-control-custom @error('nic') is-invalid @enderror" 
                                    name="nic" 
                                    value="{{ old('nic') }}" 
                                    required
                                    placeholder="199712345678 or 971234567V"
                                >
                                @error('nic')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Gender -->
                            <div class="col-md-6 mb-3">
                                <label for="gender" class="form-label-custom">
                                    <i class="fas fa-venus-mars me-2"></i>Gender *
                                </label>
                                <select 
                                    id="gender" 
                                    class="form-control form-control-custom @error('gender') is-invalid @enderror" 
                                    name="gender" 
                                    required
                                >
                                    <option value="">Select Gender</option>
                                    <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>Male</option>
                                    <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>Female</option>
                                </select>
                                @error('gender')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Address -->
                            <div class="col-md-12 mb-3">
                                <label for="address" class="form-label-custom">
                                    <i class="fas fa-map-marker-alt me-2"></i>Home Address *
                                </label>
                                <textarea 
                                    id="address" 
                                    class="form-control form-control-custom @error('address') is-invalid @enderror" 
                                    name="address" 
                                    rows="2" 
                                    required
                                    placeholder="Enter your complete home address"
                                >{{ old('address') }}</textarea>
                                @error('address')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Academic Information Section -->
                        <div class="section-header mb-4 mt-4">
                            <h6 class="section-title">
                                <i class="fas fa-graduation-cap me-2"></i>Academic Information
                            </h6>
                        </div>

                        <div class="row">
                            <!-- University -->
                            <div class="col-md-6 mb-3">
                                <label for="university" class="form-label-custom">
                                    <i class="fas fa-university me-2"></i>University *
                                </label>
                                <select 
                                    id="university" 
                                    class="form-control form-control-custom @error('university') is-invalid @enderror" 
                                    name="university" 
                                    required
                                >
                                    <option value="">Select University</option>
                                    <option value="University of Colombo" {{ old('university') == 'University of Colombo' ? 'selected' : '' }}>University of Colombo</option>
                                    <option value="University of Peradeniya" {{ old('university') == 'University of Peradeniya' ? 'selected' : '' }}>University of Peradeniya</option>
                                    <option value="University of Sri Jayewardenepura" {{ old('university') == 'University of Sri Jayewardenepura' ? 'selected' : '' }}>University of Sri Jayewardenepura</option>
                                    <option value="University of Kelaniya" {{ old('university') == 'University of Kelaniya' ? 'selected' : '' }}>University of Kelaniya</option>
                                    <option value="University of Moratuwa" {{ old('university') == 'University of Moratuwa' ? 'selected' : '' }}>University of Moratuwa</option>
                                    <option value="University of Ruhuna" {{ old('university') == 'University of Ruhuna' ? 'selected' : '' }}>University of Ruhuna</option>
                                    <option value="Eastern University" {{ old('university') == 'Eastern University' ? 'selected' : '' }}>Eastern University</option>
                                    <option value="South Eastern University" {{ old('university') == 'South Eastern University' ? 'selected' : '' }}>South Eastern University</option>
                                    <option value="Wayamba University" {{ old('university') == 'Wayamba University' ? 'selected' : '' }}>Wayamba University</option>
                                    <option value="Rajarata University" {{ old('university') == 'Rajarata University' ? 'selected' : '' }}>Rajarata University</option>
                                    <option value="Other" {{ old('university') == 'Other' ? 'selected' : '' }}>Other</option>
                                </select>
                                @error('university')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Faculty -->
                            <div class="col-md-6 mb-3">
                                <label for="faculty" class="form-label-custom">
                                    <i class="fas fa-book me-2"></i>Faculty *
                                </label>
                                <input 
                                    id="faculty" 
                                    type="text" 
                                    class="form-control form-control-custom @error('faculty') is-invalid @enderror" 
                                    name="faculty" 
                                    value="{{ old('faculty') }}" 
                                    required
                                    placeholder="e.g., Faculty of Science"
                                >
                                @error('faculty')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Student ID -->
                            <div class="col-md-6 mb-3">
                                <label for="student_id" class="form-label-custom">
                                    <i class="fas fa-id-badge me-2"></i>Student ID *
                                </label>
                                <input 
                                    id="student_id" 
                                    type="text" 
                                    class="form-control form-control-custom @error('student_id') is-invalid @enderror" 
                                    name="student_id" 
                                    value="{{ old('student_id') }}" 
                                    required
                                    placeholder="e.g., CS/2020/001"
                                >
                                @error('student_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Year of Study -->
                            <div class="col-md-6 mb-3">
                                <label for="year_of_study" class="form-label-custom">
                                    <i class="fas fa-calendar-alt me-2"></i>Year of Study *
                                </label>
                                <select 
                                    id="year_of_study" 
                                    class="form-control form-control-custom @error('year_of_study') is-invalid @enderror" 
                                    name="year_of_study" 
                                    required
                                >
                                    <option value="">Select Year</option>
                                    <option value="1" {{ old('year_of_study') == '1' ? 'selected' : '' }}>1st Year</option>
                                    <option value="2" {{ old('year_of_study') == '2' ? 'selected' : '' }}>2nd Year</option>
                                    <option value="3" {{ old('year_of_study') == '3' ? 'selected' : '' }}>3rd Year</option>
                                    <option value="4" {{ old('year_of_study') == '4' ? 'selected' : '' }}>4th Year</option>
                                    <option value="5" {{ old('year_of_study') == '5' ? 'selected' : '' }}>5th Year</option>
                                    <option value="6" {{ old('year_of_study') == '6' ? 'selected' : '' }}>6th Year</option>
                                </select>
                                @error('year_of_study')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Emergency Contact Section -->
                        <div class="section-header mb-4 mt-4">
                            <h6 class="section-title">
                                <i class="fas fa-phone-alt me-2"></i>Emergency Contact
                            </h6>
                        </div>

                        <div class="row">
                            <!-- Emergency Contact Name -->
                            <div class="col-md-6 mb-3">
                                <label for="emergency_contact_name" class="form-label-custom">
                                    <i class="fas fa-user-friends me-2"></i>Contact Name *
                                </label>
                                <input 
                                    id="emergency_contact_name" 
                                    type="text" 
                                    class="form-control form-control-custom @error('emergency_contact_name') is-invalid @enderror" 
                                    name="emergency_contact_name" 
                                    value="{{ old('emergency_contact_name') }}" 
                                    required
                                    placeholder="Parent/Guardian name"
                                >
                                @error('emergency_contact_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Emergency Contact Phone -->
                            <div class="col-md-6 mb-3">
                                <label for="emergency_contact_phone" class="form-label-custom">
                                    <i class="fas fa-phone me-2"></i>Contact Phone *
                                </label>
                                <input 
                                    id="emergency_contact_phone" 
                                    type="tel" 
                                    class="form-control form-control-custom @error('emergency_contact_phone') is-invalid @enderror" 
                                    name="emergency_contact_phone" 
                                    value="{{ old('emergency_contact_phone') }}" 
                                    required
                                    placeholder="077 123 4567"
                                >
                                @error('emergency_contact_phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Password Section -->
                        <div class="section-header mb-4 mt-4">
                            <h6 class="section-title">
                                <i class="fas fa-lock me-2"></i>Account Security
                            </h6>
                        </div>

                        <div class="row">
                            <!-- Password -->
                            <div class="col-md-6 mb-3">
                                <label for="password" class="form-label-custom">
                                    <i class="fas fa-lock me-2"></i>Password *
                                </label>
                                <div class="input-group">
                                    <input 
                                        id="password" 
                                        type="password" 
                                        class="form-control form-control-custom @error('password') is-invalid @enderror" 
                                        name="password" 
                                        required 
                                        autocomplete="new-password"
                                        placeholder="Create a strong password"
                                    >
                                    <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                                <div class="form-text">
                                    <small>Password must be at least 8 characters with uppercase, lowercase, and number.</small>
                                </div>
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Confirm Password -->
                            <div class="col-md-6 mb-3">
                                <label for="password_confirmation" class="form-label-custom">
                                    <i class="fas fa-lock me-2"></i>Confirm Password *
                                </label>
                                <div class="input-group">
                                    <input 
                                        id="password_confirmation" 
                                        type="password" 
                                        class="form-control form-control-custom" 
                                        name="password_confirmation" 
                                        required 
                                        autocomplete="new-password"
                                        placeholder="Confirm your password"
                                    >
                                    <button class="btn btn-outline-secondary" type="button" id="toggleConfirmPassword">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Terms and Conditions -->
                        <div class="mb-4">
                            <div class="form-check">
                                <input 
                                    class="form-check-input @error('terms') is-invalid @enderror" 
                                    type="checkbox" 
                                    name="terms" 
                                    id="terms" 
                                    required
                                    {{ old('terms') ? 'checked' : '' }}
                                >
                                <label class="form-check-label" for="terms">
                                    I agree to the <a href="#" class="text-decoration-none">Terms and Conditions</a> and <a href="#" class="text-decoration-none">Privacy Policy</a> *
                                </label>
                                @error('terms')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <div class="d-grid mb-3">
                            <button type="submit" class="btn btn-primary-custom">
                                <i class="fas fa-user-plus me-2"></i>Create My Account
                            </button>
                        </div>

                        <!-- Login Link -->
                        <div class="text-center">
                            <p class="mb-2">Already have an account?</p>
                            <a href="{{ route('login') }}" class="btn btn-secondary-custom">
                                <i class="fas fa-sign-in-alt me-2"></i>Login Instead
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .section-header {
        border-bottom: 2px solid var(--light-blue);
        padding-bottom: 0.5rem;
    }
    
    .section-title {
        color: var(--primary-blue);
        font-weight: 600;
        margin: 0;
        font-size: 1rem;
    }
    
    .input-group .btn {
        border-color: var(--light-blue);
    }
    
    .input-group .btn:hover {
        background-color: var(--light-blue);
        border-color: var(--primary-blue);
    }
    
    .form-text small {
        color: #6c757d;
    }
    
    @media (max-width: 576px) {
        .container {
            padding-left: 15px;
            padding-right: 15px;
        }
        
        .card-body-custom {
            padding: 1.5rem;
        }
        
        .section-header {
            margin-bottom: 1rem !important;
        }
    }
</style>
@endpush

@push('scripts')
<script>
    // Toggle password visibility
    document.getElementById('togglePassword').addEventListener('click', function() {
        togglePasswordField('password', this);
    });
    
    document.getElementById('toggleConfirmPassword').addEventListener('click', function() {
        togglePasswordField('password_confirmation', this);
    });
    
    function togglePasswordField(fieldId, button) {
        const passwordField = document.getElementById(fieldId);
        const icon = button.querySelector('i');
        
        if (passwordField.type === 'password') {
            passwordField.type = 'text';
            icon.classList.remove('fa-eye');
            icon.classList.add('fa-eye-slash');
        } else {
            passwordField.type = 'password';
            icon.classList.remove('fa-eye-slash');
            icon.classList.add('fa-eye');
        }
    }
    
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
        
        // Phone number formatting
        const phoneInputs = document.querySelectorAll('input[type="tel"]');
        phoneInputs.forEach(input => {
            input.addEventListener('input', function() {
                formatPhoneNumber(this);
            });
        });
        
        // NIC validation
        const nicInput = document.getElementById('nic');
        nicInput.addEventListener('blur', function() {
            if (this.value && !validateNIC(this.value)) {
                this.setCustomValidity('Please enter a valid Sri Lankan NIC number');
                this.classList.add('is-invalid');
            } else {
                this.setCustomValidity('');
                this.classList.remove('is-invalid');
            }
        });
        
        nicInput.addEventListener('input', function() {
            this.classList.remove('is-invalid');
        });
        
        // Password strength validation
        const passwordInput = document.getElementById('password');
        const confirmPasswordInput = document.getElementById('password_confirmation');
        
        passwordInput.addEventListener('input', function() {
            validatePasswordStrength(this);
            if (confirmPasswordInput.value) {
                validatePasswordMatch();
            }
        });
        
        confirmPasswordInput.addEventListener('input', function() {
            validatePasswordMatch();
        });
        
        function validatePasswordStrength(input) {
            const password = input.value;
            const minLength = password.length >= 8;
            const hasUpper = /[A-Z]/.test(password);
            const hasLower = /[a-z]/.test(password);
            const hasNumber = /\d/.test(password);
            
            if (password && (!minLength || !hasUpper || !hasLower || !hasNumber)) {
                input.setCustomValidity('Password must be at least 8 characters with uppercase, lowercase and number');
                input.classList.add('is-invalid');
            } else {
                input.setCustomValidity('');
                input.classList.remove('is-invalid');
            }
        }
        
        function validatePasswordMatch() {
            const password = passwordInput.value;
            const confirmPassword = confirmPasswordInput.value;
            
            if (confirmPassword && password !== confirmPassword) {
                confirmPasswordInput.setCustomValidity('Passwords do not match');
                confirmPasswordInput.classList.add('is-invalid');
            } else {
                confirmPasswordInput.setCustomValidity('');
                confirmPasswordInput.classList.remove('is-invalid');
            }
        }
    });
</script>
@endpush