@extends('layouts.app')

@section('title', 'Edit Profile - University Hostel Management')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <!-- Page Header -->
            <div class="text-center mb-5">
                <h2 class="fw-bold text-primary">Edit Profile</h2>
                <p class="text-muted">Update your personal information and settings</p>
            </div>

            <!-- Success/Error Messages -->
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    <strong>Please fix the following errors:</strong>
                    <ul class="mb-0 mt-2">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <!-- Profile Edit Card -->
            <div class="card shadow-lg border-0">
                <div class="card-header bg-primary text-white">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-user-edit me-2"></i>Personal Information
                    </h5>
                </div>
                <div class="card-body p-4">
                    <form method="POST" action="{{ route('student.profile.update') }}" enctype="multipart/form-data">
                        @csrf
                        @method('PATCH')

                        <!-- Profile Image Section -->
                        <div class="text-center mb-4">
                            <div class="profile-image-container">
                                <img src="{{ Auth::user()->profile_image_url ?? 'https://ui-avatars.com/api/?name=' . urlencode(Auth::user()->name) . '&background=667eea&color=fff&size=150' }}" 
                                     alt="Profile Image" 
                                     class="rounded-circle mb-3" 
                                     style="width: 150px; height: 150px; object-fit: cover; border: 4px solid #667eea;">
                            </div>
                            <div class="mb-3">
                                <label for="avatar" class="form-label">Change Profile Picture</label>
                                <input type="file" class="form-control" id="avatar" name="avatar" accept="image/*">
                                <small class="text-muted">Maximum file size: 2MB. Supported formats: JPG, PNG, GIF</small>
                            </div>
                        </div>

                        <!-- Basic Information -->
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="name" class="form-label">
                                    <i class="fas fa-user me-1"></i>Full Name <span class="text-danger">*</span>
                                </label>
                                <input type="text" 
                                       class="form-control @error('name') is-invalid @enderror" 
                                       id="name" 
                                       name="name" 
                                       value="{{ old('name', $user->name ?? '') }}" 
                                       required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="email" class="form-label">
                                    <i class="fas fa-envelope me-1"></i>Email Address <span class="text-danger">*</span>
                                </label>
                                <input type="email" 
                                       class="form-control @error('email') is-invalid @enderror" 
                                       id="email" 
                                       name="email" 
                                       value="{{ old('email', $user->email ?? '') }}" 
                                       required>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="phone" class="form-label">
                                    <i class="fas fa-phone me-1"></i>Phone Number
                                </label>
                                <input type="tel" 
                                       class="form-control @error('phone') is-invalid @enderror" 
                                       id="phone" 
                                       name="phone" 
                                       value="{{ old('phone', $user->phone ?? '') }}" 
                                       placeholder="+94 71 234 5678">
                                @error('phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="nic" class="form-label">
                                    <i class="fas fa-id-card me-1"></i>NIC Number
                                </label>
                                <input type="text" 
                                       class="form-control @error('nic') is-invalid @enderror" 
                                       id="nic" 
                                       name="nic" 
                                       value="{{ old('nic', $user->nic ?? '') }}" 
                                       placeholder="200012345678">
                                @error('nic')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Student Information -->
                        <h6 class="text-primary mb-3 mt-4">
                            <i class="fas fa-graduation-cap me-2"></i>Student Information
                        </h6>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="university" class="form-label">
                                    <i class="fas fa-university me-1"></i>University
                                </label>
                                <input type="text" 
                                       class="form-control @error('university') is-invalid @enderror" 
                                       id="university" 
                                       name="university" 
                                       value="{{ old('university', $user->university ?? '') }}" 
                                       placeholder="University of Colombo">
                                @error('university')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="faculty" class="form-label">
                                    <i class="fas fa-building me-1"></i>Faculty
                                </label>
                                <input type="text" 
                                       class="form-control @error('faculty') is-invalid @enderror" 
                                       id="faculty" 
                                       name="faculty" 
                                       value="{{ old('faculty', $user->faculty ?? '') }}" 
                                       placeholder="Faculty of Science">
                                @error('faculty')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="student_id" class="form-label">
                                    <i class="fas fa-id-badge me-1"></i>Student ID
                                </label>
                                <input type="text" 
                                       class="form-control @error('student_id') is-invalid @enderror" 
                                       id="student_id" 
                                       name="student_id" 
                                       value="{{ old('student_id', $user->student_id ?? '') }}" 
                                       placeholder="SC/2020/12345">
                                @error('student_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="year_of_study" class="form-label">
                                    <i class="fas fa-calendar-alt me-1"></i>Year of Study
                                </label>
                                <select class="form-control @error('year_of_study') is-invalid @enderror" 
                                        id="year_of_study" 
                                        name="year_of_study">
                                    <option value="">Select Year</option>
                                    <option value="1" {{ old('year_of_study', $user->year_of_study ?? '') == 1 ? 'selected' : '' }}>1st Year</option>
                                    <option value="2" {{ old('year_of_study', $user->year_of_study ?? '') == 2 ? 'selected' : '' }}>2nd Year</option>
                                    <option value="3" {{ old('year_of_study', $user->year_of_study ?? '') == 3 ? 'selected' : '' }}>3rd Year</option>
                                    <option value="4" {{ old('year_of_study', $user->year_of_study ?? '') == 4 ? 'selected' : '' }}>4th Year</option>
                                    <option value="5" {{ old('year_of_study', $user->year_of_study ?? '') == 5 ? 'selected' : '' }}>5th Year</option>
                                    <option value="6" {{ old('year_of_study', $user->year_of_study ?? '') == 6 ? 'selected' : '' }}>6th Year</option>
                                </select>
                                @error('year_of_study')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Address -->
                        <div class="mb-3">
                            <label for="address" class="form-label">
                                <i class="fas fa-map-marker-alt me-1"></i>Address
                            </label>
                            <textarea class="form-control @error('address') is-invalid @enderror" 
                                      id="address" 
                                      name="address" 
                                      rows="3" 
                                      placeholder="Enter your full address">{{ old('address', $user->address ?? '') }}</textarea>
                            @error('address')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Emergency Contact -->
                        <h6 class="text-primary mb-3 mt-4">
                            <i class="fas fa-phone-alt me-2"></i>Emergency Contact
                        </h6>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="emergency_contact_name" class="form-label">
                                    <i class="fas fa-user-friends me-1"></i>Emergency Contact Name
                                </label>
                                <input type="text" 
                                       class="form-control @error('emergency_contact_name') is-invalid @enderror" 
                                       id="emergency_contact_name" 
                                       name="emergency_contact_name" 
                                       value="{{ old('emergency_contact_name', $user->emergency_contact_name ?? '') }}" 
                                       placeholder="Parent/Guardian Name">
                                @error('emergency_contact_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="emergency_contact_phone" class="form-label">
                                    <i class="fas fa-phone me-1"></i>Emergency Contact Phone
                                </label>
                                <input type="tel" 
                                       class="form-control @error('emergency_contact_phone') is-invalid @enderror" 
                                       id="emergency_contact_phone" 
                                       name="emergency_contact_phone" 
                                       value="{{ old('emergency_contact_phone', $user->emergency_contact_phone ?? '') }}" 
                                       placeholder="+94 71 234 5678">
                                @error('emergency_contact_phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="d-flex justify-content-between align-items-center mt-4">
                            <a href="{{ route('student.dashboard') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-arrow-left me-2"></i>Back to Dashboard
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i>Update Profile
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Change Password Card -->
            <div class="card shadow-lg border-0 mt-4">
                <div class="card-header bg-warning text-dark">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-key me-2"></i>Change Password
                    </h5>
                </div>
                <div class="card-body p-4">
                    <form method="POST" action="{{ route('student.profile.password') }}">
                        @csrf
                        @method('PATCH')

                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <label for="current_password" class="form-label">
                                    <i class="fas fa-lock me-1"></i>Current Password <span class="text-danger">*</span>
                                </label>
                                <input type="password" 
                                       class="form-control @error('current_password') is-invalid @enderror" 
                                       id="current_password" 
                                       name="current_password" 
                                       required>
                                @error('current_password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="password" class="form-label">
                                    <i class="fas fa-key me-1"></i>New Password <span class="text-danger">*</span>
                                </label>
                                <input type="password" 
                                       class="form-control @error('password') is-invalid @enderror" 
                                       id="password" 
                                       name="password" 
                                       required>
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">Password must be at least 8 characters long</small>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="password_confirmation" class="form-label">
                                    <i class="fas fa-key me-1"></i>Confirm New Password <span class="text-danger">*</span>
                                </label>
                                <input type="password" 
                                       class="form-control" 
                                       id="password_confirmation" 
                                       name="password_confirmation" 
                                       required>
                            </div>
                        </div>

                        <div class="text-end">
                            <button type="submit" class="btn btn-warning">
                                <i class="fas fa-shield-alt me-2"></i>Update Password
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Account Actions Card -->
            <div class="card shadow-lg border-0 mt-4">
                <div class="card-header bg-danger text-white">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-exclamation-triangle me-2"></i>Danger Zone
                    </h5>
                </div>
                <div class="card-body p-4">
                    <p class="text-muted">Once you delete your account, all of your data will be permanently deleted. Please be certain.</p>
                    <button type="button" class="btn btn-outline-danger" data-bs-toggle="modal" data-bs-target="#deleteAccountModal">
                        <i class="fas fa-trash me-2"></i>Delete Account
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Delete Account Modal -->
<div class="modal fade" id="deleteAccountModal" tabindex="-1" aria-labelledby="deleteAccountModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title" id="deleteAccountModalLabel">
                    <i class="fas fa-exclamation-triangle me-2"></i>Delete Account
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p><strong>Are you sure you want to delete your account?</strong></p>
                <p class="text-muted">This action cannot be undone. All your bookings, payments, and personal data will be permanently deleted.</p>
                
                <form method="POST" action="{{ route('student.profile.destroy') }}" id="delete-account-form">
                    @csrf
                    @method('DELETE')
                    
                    <div class="mb-3">
                        <label for="delete_password" class="form-label">Enter your password to confirm:</label>
                        <input type="password" class="form-control" id="delete_password" name="password" required>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" form="delete-account-form" class="btn btn-danger">
                    <i class="fas fa-trash me-2"></i>Delete Account
                </button>
            </div>
        </div>
    </div>
</div>

<style>
.profile-image-container {
    position: relative;
    display: inline-block;
}

.card {
    border-radius: 15px;
    overflow: hidden;
}

.card-header {
    border-bottom: none;
    padding: 1.5rem;
}

.form-control {
    border-radius: 10px;
    border: 2px solid #e9ecef;
    padding: 0.75rem 1rem;
    transition: all 0.3s ease;
}

.form-control:focus {
    border-color: #667eea;
    box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
}

.btn {
    border-radius: 10px;
    padding: 0.75rem 1.5rem;
    font-weight: 500;
    transition: all 0.3s ease;
}

.btn-primary {
    background: linear-gradient(45deg, #667eea, #764ba2);
    border: none;
}

.btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
}
</style>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Profile image preview
    const avatarInput = document.getElementById('avatar');
    const profileImage = document.querySelector('.profile-image-container img');
    
    if (avatarInput && profileImage) {
        avatarInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    profileImage.src = e.target.result;
                };
                reader.readAsDataURL(file);
            }
        });
    }
    
    // Form validation
    const forms = document.querySelectorAll('.needs-validation');
    forms.forEach(form => {
        form.addEventListener('submit', function(e) {
            if (!form.checkValidity()) {
                e.preventDefault();
                e.stopPropagation();
            }
            form.classList.add('was-validated');
        });
    });
});
</script>
@endpush
@endsection