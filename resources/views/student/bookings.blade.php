@extends('layouts.app')

@section('title', 'My Bookings - Student Dashboard')

@section('content')
<div class="container py-4">
    <div class="row">
        <div class="col-12">
            <div class="card-custom">
                <div class="card-header-custom">
                    <h5 class="mb-0">
                        <i class="fas fa-calendar-check me-2"></i>My Bookings
                    </h5>
                </div>
                <div class="card-body-custom">
                    @if(isset($bookings) && $bookings->count() > 0)
                        @foreach($bookings as $booking)
                            <div class="booking-item p-3 border-bottom">
                                <div class="row align-items-center">
                                    <div class="col-md-8">
                                        <h6>{{ $booking->hostelPackage->name }}</h6>
                                        <p class="text-muted mb-1">
                                            <i class="fas fa-calendar me-1"></i>
                                            {{ $booking->check_in_date->format('M d, Y') }} - {{ $booking->check_out_date->format('M d, Y') }}
                                        </p>
                                        <p class="mb-0">
                                            <small>Booking ID: {{ $booking->booking_reference }}</small>
                                        </p>
                                    </div>
                                    <div class="col-md-4 text-end">
                                        <p class="fw-bold mb-1">{{ $booking->formatted_amount }}</p>
                                        {!! $booking->status_badge !!}
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-calendar-times text-muted" style="font-size: 3rem;"></i>
                            <h5 class="mt-3 text-muted">No Bookings Found</h5>
                            <p class="text-muted">You haven't made any bookings yet.</p>
                            <a href="{{ route('hostels') }}" class="btn btn-primary-custom">
                                <i class="fas fa-search me-2"></i>Find Hostels
                            </a>
                        </div>
                    @endif
                    
                    <div class="text-center mt-4">
                        <a href="{{ route('student.dashboard') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-arrow-left me-2"></i>Back to Dashboard
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection