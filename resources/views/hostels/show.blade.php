@extends('layouts.app')

@section('title', $hostel->name . ' - University Hostel Management System')

@section('content')
<div class="container py-5">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('hostels') }}">Hostels</a></li>
            <li class="breadcrumb-item active">{{ $hostel->name }}</li>
        </ol>
    </nav>

    <div class="row">
        <!-- Main Content -->
        <div class="col-lg-8 mb-4">
            <!-- Hostel Header -->
            <div class="card-custom mb-4">
                <div class="card-body-custom">
                    <div class="row">
                        <div class="col-md-6">
                            <img src="{{ $hostel->image_url ?? asset('images/default-hostel.jpg') }}" alt="{{ $hostel->name }}" 
                                 class="img-fluid rounded shadow">
                        </div>
                        <div class="col-md-6">
                            <span class="badge bg-primary mb-2">{{ ucfirst($hostel->type) }} Hostel</span>
                            <h1 class="h3 fw-bold mb-3">{{ $hostel->name }}</h1>
                            
                            <!-- Rating (Using static data from hostel properties) -->
                            <div class="mb-3">
                                @php 
                                    // Use static rating data from hostel properties instead of database queries
                                    $rating = $hostel->average_rating ?? 4.0;
                                    $reviewCount = $hostel->total_reviews ?? 0;
                                @endphp
                                @for($i = 1; $i <= 5; $i++)
                                    @if($i <= $rating)
                                        <i class="fas fa-star text-warning"></i>
                                    @else
                                        <i class="far fa-star text-muted"></i>
                                    @endif
                                @endfor
                                <span class="ms-2">{{ number_format($rating, 1) }} ({{ $reviewCount }} reviews)</span>
                            </div>
                            
                            <div class="price-info mb-3">
                                <h3 class="text-primary mb-1">LKR {{ number_format($hostel->price) }}</h3>
                                <p class="text-muted">per month</p>
                            </div>
                            
                            <div class="availability-info mb-3">
                                @php 
                                    // Use static availability from hostel properties
                                    $availableSlots = $hostel->available_slots ?? 0;
                                    $capacity = $hostel->capacity ?? 50;
                                @endphp
                                <p class="mb-1">
                                    <i class="fas fa-users text-primary me-2"></i>
                                    <strong>{{ $availableSlots }}</strong> slots available out of {{ $capacity }}
                                </p>
                                <div class="progress" style="height: 10px;">
                                    <div class="progress-bar bg-success" style="width: {{ $capacity > 0 ? ($availableSlots / $capacity) * 100 : 0 }}%"></div>
                                </div>
                            </div>
                            
                            @auth
                                @if($availableSlots > 0)
                                    <!-- Primary Book Now Button (Direct Link) -->
                                    <div class="d-grid gap-2 d-md-flex">
                                        <a href="{{ route('hostels.book', $hostel->id) }}" class="btn btn-primary-custom btn-lg me-md-2">
                                            <i class="fas fa-calendar-plus me-2"></i>📅 Book Now
                                        </a>
                                        
                                        <!-- Alternative: Quick Book Modal -->
                                        <button type="button" class="btn btn-outline-primary btn-lg book-hostel-btn" 
                                                data-hostel-id="{{ $hostel->id }}" 
                                                data-hostel-name="{{ $hostel->name }}" 
                                                data-hostel-price="{{ $hostel->price }}"
                                                data-bs-toggle="modal" 
                                                data-bs-target="#hostelBookingModal">
                                            <i class="fas fa-bolt me-2"></i>Quick Book
                                        </button>
                                    </div>
                                @else
                                    <button class="btn btn-secondary btn-lg w-100" disabled>
                                        <i class="fas fa-times me-2"></i>Fully Booked
                                    </button>
                                @endif
                            @else
                                <div class="d-grid gap-2">
                                    <a href="{{ route('login') }}" class="btn btn-primary-custom btn-lg">
                                        <i class="fas fa-sign-in-alt me-2"></i>Login to Book
                                    </a>
                                    <small class="text-muted text-center">
                                        Don't have an account? <a href="{{ route('register') }}">Sign up here</a>
                                    </small>
                                </div>
                            @endauth
                        </div>
                    </div>
                </div>
            </div>

            <!-- Description -->
            <div class="card-custom mb-4">
                <div class="card-header-custom">
                    <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i>About This Hostel</h5>
                </div>
                <div class="card-body-custom">
                    <p>{{ $hostel->description ?? 'No description available for this hostel.' }}</p>
                </div>
            </div>

            <!-- Image Gallery -->
            @if(isset($hostel->images) && is_array($hostel->images) && count($hostel->images) > 1)
            <div class="card-custom mb-4">
                <div class="card-header-custom">
                    <h5 class="mb-0"><i class="fas fa-images me-2"></i>Hostel Gallery</h5>
                </div>
                <div class="card-body-custom">
                    <div class="row">
                        @foreach($hostel->images as $index => $image)
                            <div class="col-md-4 mb-3">
                                <img src="{{ $image }}" alt="{{ $hostel->name }} - Image {{ $index + 1 }}" 
                                     class="img-fluid rounded shadow" style="height: 200px; object-fit: cover; width: 100%;">
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif

            <!-- Facilities -->
            <div class="card-custom mb-4">
                <div class="card-header-custom">
                    <h5 class="mb-0"><i class="fas fa-star me-2"></i>Facilities & Amenities</h5>
                </div>
                <div class="card-body-custom">
                    <div class="row">
                        @php 
                            $facilities = is_string($hostel->facilities) ? json_decode($hostel->facilities, true) : $hostel->facilities;
                            $facilities = $facilities ?? ['Wi-Fi', 'Laundry', 'Security', 'Mess'];
                        @endphp
                        @foreach($facilities as $facility)
                            <div class="col-md-6 mb-2">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-check-circle text-success me-2"></i>
                                    <span>{{ $facility }}</span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Rules -->
            <div class="card-custom mb-4">
                <div class="card-header-custom">
                    <h5 class="mb-0"><i class="fas fa-list-alt me-2"></i>Hostel Rules</h5>
                </div>
                <div class="card-body-custom">
                    <div class="row">
                        @php 
                            $rules = is_string($hostel->rules) ? json_decode($hostel->rules, true) : $hostel->rules;
                            $rules = $rules ?? ['No smoking', 'No loud music after 10 PM', 'Visitors allowed until 8 PM', 'Keep common areas clean'];
                        @endphp
                        @foreach($rules as $rule)
                            <div class="col-md-6 mb-2">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-exclamation-circle text-warning me-2"></i>
                                    <span>{{ $rule }}</span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Packages Section -->
            @if(isset($packages) && $packages->count() > 0)
            <div class="card-custom mb-4">
                <div class="card-header-custom">
                    <h5 class="mb-0"><i class="fas fa-box me-2"></i>Available Packages</h5>
                </div>
                <div class="card-body-custom">
                    <div class="row">
                        @foreach($packages as $package)
                            <div class="col-md-4 mb-3">
                                <div class="border rounded p-3 h-100">
                                    <h6 class="fw-bold text-primary">{{ $package->name }}</h6>
                                    <p class="text-muted small mb-2">{{ $package->description }}</p>
                                    <p class="fw-bold mb-2">LKR {{ number_format($package->monthly_price) }}/month</p>
                                    <small class="text-muted">{{ $package->facilities }}</small>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif

            <!-- Contact Information Card -->
            <div class="card-custom">
                <div class="card-header-custom">
                    <h5 class="mb-0"><i class="fas fa-phone me-2"></i>Contact & Support</h5>
                </div>
                <div class="card-body-custom">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="contact-item mb-3">
                                <i class="fas fa-phone text-primary me-2"></i>
                                <strong>Phone:</strong> {{ $hostel->phone ?? '+94 11 234 5678' }}
                            </div>
                            <div class="contact-item mb-3">
                                <i class="fas fa-envelope text-primary me-2"></i>
                                <strong>Email:</strong> {{ $hostel->email ?? 'info@hostel.com' }}
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="contact-item mb-3">
                                <i class="fas fa-map-marker-alt text-primary me-2"></i>
                                <strong>Address:</strong> {{ $hostel->address ?? $hostel->location }}
                            </div>
                            <div class="contact-item mb-3">
                                <i class="fas fa-clock text-primary me-2"></i>
                                <strong>Support:</strong> 24/7 Available
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Quick Info -->
            <div class="card-custom mb-4">
                <div class="card-header-custom">
                    <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i>Quick Info</h5>
                </div>
                <div class="card-body-custom">
                    <div class="info-item d-flex justify-content-between mb-2">
                        <span>Type:</span>
                        <strong>{{ ucfirst($hostel->type) }} Hostel</strong>
                    </div>
                    <div class="info-item d-flex justify-content-between mb-2">
                        <span>Price:</span>
                        <strong>LKR {{ number_format($hostel->price) }}</strong>
                    </div>
                    <div class="info-item d-flex justify-content-between mb-2">
                        <span>Duration:</span>
                        <strong>Monthly</strong>
                    </div>
                    <div class="info-item d-flex justify-content-between mb-2">
                        <span>Total Capacity:</span>
                        <strong>{{ $hostel->capacity }} students</strong>
                    </div>
                    <div class="info-item d-flex justify-content-between mb-2">
                        <span>Available:</span>
                        <strong class="text-success">{{ $availableSlots }} slots</strong>
                    </div>
                    <div class="info-item d-flex justify-content-between mb-3">
                        <span>Status:</span>
                        <span class="badge bg-success">{{ $hostel->availability_status ?? 'Available' }}</span>
                    </div>
                    
                    <!-- Sidebar Book Button -->
                    @auth
                        @if($availableSlots > 0)
                            <div class="d-grid">
                                <a href="{{ route('hostels.book', $hostel->id) }}" class="btn btn-primary-custom">
                                    <i class="fas fa-calendar-plus me-2"></i>Book This Hostel
                                </a>
                            </div>
                        @endif
                    @else
                        <div class="d-grid">
                            <a href="{{ route('login') }}" class="btn btn-primary-custom">
                                <i class="fas fa-sign-in-alt me-2"></i>Login to Book
                            </a>
                        </div>
                    @endauth
                </div>
            </div>

            <!-- Similar Hostels -->
            @if(isset($similarHostels) && $similarHostels->count() > 0)
            <div class="card-custom">
                <div class="card-header-custom">
                    <h5 class="mb-0"><i class="fas fa-building me-2"></i>Similar Hostels</h5>
                </div>
                <div class="card-body-custom">
                    @foreach($similarHostels as $similarHostel)
                        <div class="similar-hostel-item d-flex mb-3 pb-3 border-bottom">
                            <img src="{{ $similarHostel->image_url ?? asset('images/default-hostel.jpg') }}" alt="{{ $similarHostel->name ?? 'Hostel' }}" 
                                 class="rounded me-3" style="width: 60px; height: 60px; object-fit: cover;">
                            <div class="flex-grow-1">
                                <h6 class="mb-1">{{ Str::limit($similarHostel->name ?? 'Unknown Hostel', 25) }}</h6>
                                <p class="text-muted mb-1 small">{{ $similarHostel->formatted_price ?? 'LKR ' . number_format($similarHostel->price ?? 20000) }}</p>
                                <a href="{{ route('hostels.show', $similarHostel->id) }}" class="btn btn-sm btn-outline-primary">
                                    View Details
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

<!-- Booking Modal (Quick Book) -->
<div class="modal fade" id="hostelBookingModal" tabindex="-1" aria-labelledby="hostelBookingModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="hostelBookingModalLabel">Quick Book Hostel</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="hostelBookingForm" method="POST" action="{{ route('hostels.createBooking', $hostel->id) }}">
                @csrf
                <div class="modal-body">
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i>
                        <strong>Quick Book:</strong> For detailed booking options, use the main "Book Now" button.
                    </div>
                    
                    <input type="hidden" id="booking_hostel_id" name="hostel_id" value="{{ $hostel->id }}">
                    
                    <div class="mb-3">
                        <label class="form-label">Hostel Name</label>
                        <input type="text" class="form-control" id="booking_hostel_name" value="{{ $hostel->name }}" readonly>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Monthly Price</label>
                        <input type="text" class="form-control" id="booking_hostel_price" value="LKR {{ number_format($hostel->price) }}" readonly>
                    </div>
                    
                    <div class="mb-3">
                        <label for="booking_start_date" class="form-label">Check-in Date</label>
                        <input type="date" class="form-control" id="booking_start_date" name="check_in_date" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="booking_end_date" class="form-label">Check-out Date</label>
                        <input type="date" class="form-control" id="booking_end_date" name="check_out_date" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="guest_name" class="form-label">Guest Name</label>
                        <input type="text" class="form-control" id="guest_name" name="guest_name" value="{{ auth()->user()->name ?? '' }}" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="guest_email" class="form-label">Guest Email</label>
                        <input type="email" class="form-control" id="guest_email" name="guest_email" value="{{ auth()->user()->email ?? '' }}" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="guest_phone" class="form-label">Guest Phone</label>
                        <input type="tel" class="form-control" id="guest_phone" name="guest_phone" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="booking_notes" class="form-label">Special Requirements (Optional)</label>
                        <textarea class="form-control" id="booking_notes" name="special_requirements" rows="3"></textarea>
                    </div>
                    
                    <input type="hidden" name="hostel_package_id" value="1">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary-custom">Confirm Quick Booking</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .info-item {
        padding: 0.5rem 0;
    }
    
    .contact-item {
        display: flex;
        align-items: center;
    }
    
    .similar-hostel-item:last-child {
        border-bottom: none !important;
        margin-bottom: 0 !important;
        padding-bottom: 0 !important;
    }
    
    .progress {
        border-radius: 10px;
    }
    
    .progress-bar {
        border-radius: 10px;
    }
    
    .card-custom {
        background: white;
        border-radius: 15px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        border: none;
    }
    
    .card-header-custom {
        background: linear-gradient(135deg, #f8f9fa, #e9ecef);
        border-bottom: 1px solid #dee2e6;
        padding: 1rem 1.5rem;
        border-radius: 15px 15px 0 0;
    }
    
    .card-body-custom {
        padding: 1.5rem;
    }
    
    .btn-primary-custom {
        background: linear-gradient(135deg, #007bff, #0056b3);
        border: none;
        border-radius: 8px;
        padding: 0.75rem 1.5rem;
        font-weight: 600;
        transition: all 0.3s ease;
        color: white;
        text-decoration: none;
    }
    
    .btn-primary-custom:hover {
        background: linear-gradient(135deg, #0056b3, #004085);
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 123, 255, 0.3);
        color: white;
        text-decoration: none;
    }
    
    .btn-outline-primary:hover {
        transform: translateY(-1px);
    }
    
    @media (max-width: 768px) {
        .btn-lg {
            width: 100%;
            margin-top: 1rem;
        }
        
        .d-md-flex {
            flex-direction: column !important;
        }
        
        .me-md-2 {
            margin-right: 0 !important;
            margin-bottom: 0.5rem;
        }
    }
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Booking modal functionality
    const bookingButtons = document.querySelectorAll('.book-hostel-btn');
    
    bookingButtons.forEach(button => {
        button.addEventListener('click', function() {
            const hostelId = this.getAttribute('data-hostel-id');
            const hostelName = this.getAttribute('data-hostel-name');
            const hostelPrice = this.getAttribute('data-hostel-price');
            
            document.getElementById('booking_hostel_id').value = hostelId;
            document.getElementById('booking_hostel_name').value = hostelName;
            document.getElementById('booking_hostel_price').value = 'LKR ' + new Intl.NumberFormat().format(hostelPrice);
            
            // Set minimum date to today
            const today = new Date().toISOString().split('T')[0];
            document.getElementById('booking_start_date').min = today;
            document.getElementById('booking_end_date').min = today;
        });
    });
    
    // Update end date minimum when start date changes
    document.getElementById('booking_start_date').addEventListener('change', function() {
        const startDate = this.value;
        document.getElementById('booking_end_date').min = startDate;
    });
    
    // Form validation
    document.getElementById('hostelBookingForm').addEventListener('submit', function(e) {
        const startDate = new Date(document.getElementById('booking_start_date').value);
        const endDate = new Date(document.getElementById('booking_end_date').value);
        
        if (endDate <= startDate) {
            e.preventDefault();
            alert('Check-out date must be after check-in date');
            return false;
        }
        
        // Show loading state
        const submitBtn = this.querySelector('button[type="submit"]');
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Processing...';
    });
});
</script>
@endpush