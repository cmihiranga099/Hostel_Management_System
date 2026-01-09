@extends('layouts.app')

@section('title', 'Login - University Hostel Management System')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
            <div class="card-custom">
                <div class="card-header-custom text-center">
                    <h4 class="mb-0">
                        <i class="fas fa-sign-in-alt me-2"></i>Student Login
                    </h4>
                </div>
                
                <div class="card-body-custom">
                    <!-- Session Status -->
                    @if (session('status'))
                        <div class="alert alert-success mb-4" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('login') }}" class="needs-validation" novalidate>
                        @csrf

                        <!-- Email Address -->
                        <div class="mb-3">
                            <label for="email" class="form-label-custom">
                                <i class="fas fa-envelope me-2"></i>Email Address
                            </label>
                            <input 
                                id="email" 
                                type="email" 
                                class="form-control form-control-custom @error('email') is-invalid @enderror" 
                                name="email" 
                                value="{{ old('email') }}" 
                                required 
                                autocomplete="email" 
                                autofocus
                                placeholder="Enter your email address"
                            >
                            @error('email')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <!-- Password -->
                        <div class="mb-3">
                            <label for="password" class="form-label-custom">
                                <i class="fas fa-lock me-2"></i>Password
                            </label>
                            <div class="input-group">
                                <input 
                                    id="password" 
                                    type="password" 
                                    class="form-control form-control-custom @error('password') is-invalid @enderror" 
                                    name="password" 
                                    required 
                                    autocomplete="current-password"
                                    placeholder="Enter your password"
                                >
                                <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                                    <i class="fas fa-eye"></i>
                                </button>
                                @error('password')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>

                        <!-- Remember Me -->
                        <div class="mb-3 form-check">
                            <input 
                                class="form-check-input" 
                                type="checkbox" 
                                name="remember" 
                                id="remember" 
                                {{ old('remember') ? 'checked' : '' }}
                            >
                            <label class="form-check-label" for="remember">
                                Remember me for 30 days
                            </label>
                        </div>

                        <!-- Submit Button -->
                        <div class="d-grid mb-3">
                            <button type="submit" class="btn btn-primary-custom">
                                <i class="fas fa-sign-in-alt me-2"></i>Login to Dashboard
                            </button>
                        </div>

                        <!-- Forgot Password Link -->
                        <div class="text-center mb-3">
                            @if (Route::has('password.request'))
                                <a href="{{ route('password.request') }}" class="text-decoration-none">
                                    <i class="fas fa-question-circle me-1"></i>Forgot your password?
                                </a>
                            @endif
                        </div>

                        <!-- Divider -->
                        <hr class="my-4">

                        <!-- Register Link -->
                        <div class="text-center">
                            <p class="mb-2">Don't have an account?</p>
                            <a href="{{ route('register') }}" class="btn btn-secondary-custom">
                                <i class="fas fa-user-plus me-2"></i>Create New Account
                            </a>
                        </div>
                    </form>
                </div>
            </div>

        
@endsection

@push('styles')
<style>
    .demo-credentials {
        background: #f8f9fa;
        padding: 10px;
        border-radius: 8px;
        font-family: 'Courier New', monospace;
        font-size: 0.9rem;
        border-left: 4px solid var(--primary-blue);
    }
    
    .input-group .btn {
        border-color: var(--light-blue);
    }
    
    .input-group .btn:hover {
        background-color: var(--light-blue);
        border-color: var(--primary-blue);
    }
    
    @media (max-width: 576px) {
        .container {
            padding-left: 15px;
            padding-right: 15px;
        }
        
        .card-body-custom {
            padding: 1.5rem;
        }
    }
</style>
@endpush

@push('scripts')
<script>
    // Toggle password visibility
    document.getElementById('togglePassword').addEventListener('click', function() {
        const passwordField = document.getElementById('password');
        const icon = this.querySelector('i');
        
        if (passwordField.type === 'password') {
            passwordField.type = 'text';
            icon.classList.remove('fa-eye');
            icon.classList.add('fa-eye-slash');
        } else {
            passwordField.type = 'password';
            icon.classList.remove('fa-eye-slash');
            icon.classList.add('fa-eye');
        }
    });
    
    // Fill demo credentials
    function fillDemoCredentials() {
        document.getElementById('email').value = 'student@universityhostel.lk';
        document.getElementById('password').value = 'student123';
        document.getElementById('remember').checked = true;
        
        // Show a brief success message
        showToast('Demo credentials filled! Click login to proceed.', 'info');
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
        
        // Real-time email validation
        const emailInput = document.getElementById('email');
        emailInput.addEventListener('blur', function() {
            const email = this.value.trim();
            if (email && !isValidEmail(email)) {
                this.setCustomValidity('Please enter a valid email address');
                this.classList.add('is-invalid');
            } else {
                this.setCustomValidity('');
                this.classList.remove('is-invalid');
            }
        });
        
        emailInput.addEventListener('input', function() {
            this.classList.remove('is-invalid');
        });
    });
    
    function isValidEmail(email) {
        const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return emailPattern.test(email);
    }
</script>
@endpush