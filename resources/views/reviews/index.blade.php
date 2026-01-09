@extends('layouts.app')

@section('title', 'Student Reviews')

@section('content')
<div class="container py-5">
    <div class="row mb-5">
        <div class="col-12 text-center">
            <h1 class="display-4 fw-bold text-primary mb-3">Student Reviews</h1>
            <p class="lead text-muted">Real experiences from students who have stayed at our hostels</p>
        </div>
    </div>

    <div class="row">
        @foreach($reviews as $review)
            <div class="col-lg-6 mb-4">
                <div class="card h-100 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <div class="d-flex align-items-center">
                                <img src="{{ $review->profile_image }}" alt="{{ $review->user_name }}" 
                                     class="rounded-circle me-3" style="width: 50px; height: 50px;">
                                <div>
                                    <h6 class="mb-1 fw-bold">{{ $review->user_name }}</h6>
                                    <small class="text-muted">{{ $review->formatted_date }}</small>
                                </div>
                            </div>
                            <div class="text-end">
                                {!! $review->star_rating !!}
                                <div class="small text-muted">{{ $review->rating_text }}</div>
                            </div>
                        </div>

                        <div class="d-flex align-items-center mb-3">
                            <img src="{{ $review->hostel_image }}" alt="{{ $review->hostel_name }}" 
                                 class="rounded me-2" style="width: 40px; height: 40px;">
                            <div>
                                <h6 class="mb-0">{{ $review->hostel_name }}</h6>
                                <small class="text-muted">{{ $review->type_display }}</small>
                            </div>
                        </div>

                        <p class="card-text">{{ $review->comment }}</p>

                        <div class="d-flex justify-content-between align-items-center">
                            <small class="text-muted">{{ $review->time_ago }}</small>
                            @if($review->is_recent)
                                <span class="badge bg-success">New</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Summary Stats -->
    <div class="row mt-5">
        <div class="col-12">
            <div class="card">
                <div class="card-body text-center">
                    <div class="row">
                        <div class="col-md-3">
                            <h3 class="text-primary">{{ $reviews->count() }}</h3>
                            <p class="text-muted">Total Reviews</p>
                        </div>
                        <div class="col-md-3">
                            <h3 class="text-success">{{ number_format($reviews->avg('rating'), 1) }}</h3>
                            <p class="text-muted">Average Rating</p>
                        </div>
                        <div class="col-md-3">
                            <h3 class="text-info">{{ $reviews->where('rating', 5)->count() }}</h3>
                            <p class="text-muted">5-Star Reviews</p>
                        </div>
                        <div class="col-md-3">
                            <h3 class="text-warning">{{ $reviews->where('is_recent', true)->count() }}</h3>
                            <p class="text-muted">Recent Reviews</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection