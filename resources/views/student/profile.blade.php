@extends('layouts.app')

@section('title', 'My Profile - Student Dashboard')

@section('content')
<div class="container py-4">
    <div class="row">
        <div class="col-md-8 mx-auto">
            <div class="card-custom">
                <div class="card-header-custom">
                    <h5 class="mb-0">
                        <i class="fas fa-user me-2"></i>My Profile
                    </h5>
                </div>
                <div class="card-body-custom">
                    <div class="text-center mb-4">
                        <img src="{{ Auth::user()->profile_image_url ?? asset('images/default-avatar.png') }}" 
                             alt="Profile" class="rounded-circle" style="width: 100px; height: 100px; object-fit: cover;">
                        <h4 class="mt-3">{{ Auth::user()->name }}</h4>
                        <p class="text-muted">{{ Auth::user()->university ?? 'University Student' }}</p>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>Email:</strong> {{ Auth::user()->email }}</p>
                            <p><strong>Phone:</strong> {{ Auth::user()->phone ?? 'Not set' }}</p>
                            <p><strong>Student ID:</strong> {{ Auth::user()->student_id ?? 'Not set' }}</p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>University:</strong> {{ Auth::user()->university ?? 'Not set' }}</p>
                            <p><strong>Faculty:</strong> {{ Auth::user()->faculty ?? 'Not set' }}</p>
                            <p><strong>Year:</strong> {{ Auth::user()->year_of_study ?? 'Not set' }}</p>
                        </div>
                    </div>
                    
                    <div class="text-center mt-4">
                        <a href="{{ route('student.dashboard') }}" class="btn btn-outline-secondary me-3">
                            <i class="fas fa-arrow-left me-2"></i>Back to Dashboard
                        </a>
                        <button class="btn btn-primary-custom">
                            <i class="fas fa-edit me-2"></i>Edit Profile
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection