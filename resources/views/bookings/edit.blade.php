{{-- Update Booking Form - resources/views/bookings/edit.blade.php --}}

@extends('layouts.app')

@section('title', 'Update Booking - ' . $booking->hostel->name)

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <!-- Header -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h2>Update Booking</h2>
                    <p class="text-muted">Booking ID: #{{ $booking->id }}</p>
                </div>
                <div>
                    <span class="badge bg-{{ $booking->status === 'confirmed' ? 'success' : ($booking->status === 'pending' ? 'warning' : 'secondary') }} fs-6">
                        {{ ucfirst($booking->status) }}
                    </span>
                </div>
            </div>

            <div class="row">
                <!-- Update Form -->
                <div class="col-lg-8">
                    <form id="updateBookingForm" method="POST" action="{{ route('bookings.update', $booking->id) }}">
                        @csrf
                        @method('PUT')
                        
                        <!-- Current Booking Info -->
                        <div class="card mb-4">
                            <div class="card-header">
                                <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i>Current Booking Details</h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <p><strong>Hostel:</strong> {{ $booking->hostel->name }}</p>
                                        <p><strong>Location:</strong> {{ $booking->hostel->location }}</p>
                                        <p><strong>Package:</strong> {{ $booking->package->name ?? 'Standard Room' }}</p>
                                    </div>
                                    <div class="col-md-6">
                                        <p><strong>Check-in:</strong> {{ $booking->check_in_date->format('M d, Y') }}</p>
                                        <p><strong>Check-out:</strong> {{ $booking->check_out_date->format('M d, Y') }}</p>
                                        <p><strong>Total Amount:</strong> LKR {{ number_format($booking->total_amount) }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Update Form -->
                        <div class="card mb-4">
                            <div class="card-header">
                                <h5 class="mb-0"><i class="fas fa-edit me-2"></i>Update Information</h5>
                            </div>
                            <div class="card-body">
                                <!-- Dates -->
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label for="check_in_date" class="form-label">New Check-in Date</label>
                                        <input type="date" class="form-control" id="check_in_date" name="check_in_date" 
                                               value="{{ $booking->check_in_date->format('Y-m-d') }}" required>
                                        <small class="text-muted">Current: {{ $booking->check_in_date->format('M d, Y') }}</small>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="check_out_date" class="form-label">New Check-out Date</label>
                                        <input type="date" class="form-control" id="check_out_date" name="check_out_date" 
                                               value="{{ $booking->check_out_date->format('Y-m-d') }}" required>
                                        <small class="text-muted">Current: {{ $booking->check_out_date->format('M d, Y') }}</small>
                                    </div>
                                </div>

                                <!-- Package Selection -->
                                <div class="mb-3">
                                    <label class="form-label">Room Package</label>
                                    <div class="row">
                                        @php
                                            $packages = $packages ?? collect([
                                                (object)['id' => 1, 'name' => 'Standard Room', 'monthly_price' => 18000],
                                                (object)['id' => 2, 'name' => 'Premium Room', 'monthly_price' => 25000]
                                            ]);
                                        @endphp
                                        @foreach($packages as $package)
                                        <div class="col-md-6 mb-2">
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="package_id" 
                                                       id="package_{{ $package->id }}" value="{{ $package->id }}"
                                                       data-price="{{ $package->monthly_price }}"
                                                       {{ ($booking->package_id == $package->id) ? 'checked' : '' }} required>
                                                <label class="form-check-label w-100" for="package_{{ $package->id }}">
                                                    <div class="d-flex justify-content-between">
                                                        <span><strong>{{ $package->name }}</strong></span>
                                                        <span class="text-primary">LKR {{ number_format($package->monthly_price) }}/month</span>
                                                    </div>
                                                </label>
                                            </div>
                                        </div>
                                        @endforeach
                                    </div>
                                </div>

                                <!-- Emergency Contact -->
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label for="emergency_contact_name" class="form-label">Emergency Contact Name</label>
                                        <input type="text" class="form-control" id="emergency_contact_name" 
                                               name="emergency_contact_name" value="{{ $booking->emergency_contact_name }}" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="emergency_contact_phone" class="form-label">Emergency Contact Phone</label>
                                        <input type="tel" class="form-control" id="emergency_contact_phone" 
                                               name="emergency_contact_phone" value="{{ $booking->emergency_contact_phone }}" required>
                                    </div>
                                </div>

                                <!-- Special Requests -->
                                <div class="mb-3">
                                    <label for="special_requests" class="form-label">Special Requests</label>
                                    <textarea class="form-control" id="special_requests" name="special_requests" rows="3">{{ $booking->special_requests }}</textarea>
                                </div>

                                <!-- Update Reason -->
                                <div class="mb-3">
                                    <label for="update_reason" class="form-label">Reason for Update *</label>
                                    <select class="form-select" id="update_reason" name="update_reason" required>
                                        <option value="">Select reason</option>
                                        <option value="date_change">Date Change Required</option>
                                        <option value="room_upgrade">Room Upgrade/Downgrade</option>
                                        <option value="contact_update">Contact Information Update</option>
                                        <option value="special_requirements">Special Requirements Change</option>
                                        <option value="other">Other</option>
                                    </select>
                                </div>

                                <div class="mb-3" id="other_reason_div" style="display: none;">
                                    <label for="other_reason" class="form-label">Please specify</label>
                                    <textarea class="form-control" id="other_reason" name="other_reason" rows="2"></textarea>
                                </div>

                                <!-- Price Difference Alert -->
                                <div id="price_difference_alert" class="alert alert-info d-none">
                                    <i class="fas fa-info-circle me-2"></i>
                                    <span id="price_difference_text"></span>
                                </div>

                                <!-- Submit Buttons -->
                                <div class="d-flex gap-2">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save me-2"></i>Update Booking
                                    </button>
                                    <a href="{{ route('student.bookings') }}" class="btn btn-outline-secondary">
                                        <i class="fas fa-arrow-left me-2"></i>Back to Bookings
                                    </a>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>

                <!-- Actions Sidebar -->
                <div class="col-lg-4">
                    <!-- Price Summary -->
                    <div class="card mb-4">
                        <div class="card-header">
                            <h6 class="mb-0"><i class="fas fa-calculator me-2"></i>Updated Price Summary</h6>
                        </div>
                        <div class="card-body">
                            <div class="d-flex justify-content-between mb-2">
                                <span>Duration:</span>
                                <span id="new_duration">-</span>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span>Monthly Rate:</span>
                                <span id="new_monthly_rate">-</span>
                            </div>
                            <hr>
                            <div class="d-flex justify-content-between mb-2">
                                <span>Subtotal:</span>
                                <span id="new_subtotal">-</span>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span>Service Fee:</span>
                                <span>LKR 1,000</span>
                            </div>
                            <hr>
                            <div class="d-flex justify-content-between">
                                <strong>New Total:</strong>
                                <strong id="new_total" class="text-primary">-</strong>
                            </div>
                            <div class="mt-2">
                                <small class="text-muted">
                                    Previous Total: LKR {{ number_format($booking->total_amount) }}
                                </small>
                            </div>
                        </div>
                    </div>

                    <!-- Delete Request -->
                    <div class="card border-danger">
                        <div class="card-header bg-danger text-white">
                            <h6 class="mb-0"><i class="fas fa-trash me-2"></i>Cancel Booking</h6>
                        </div>
                        <div class="card-body">
                            <p class="text-muted small mb-3">
                                Need to cancel your booking? Submit a cancellation request.
                            </p>
                            
                            <!-- Cancellation Policy -->
                            <div class="alert alert-warning">
                                <strong>Cancellation Policy:</strong>
                                <ul class="mb-0 mt-2">
                                    <li>Free cancellation: 7+ days before check-in</li>
                                    <li>50% refund: 3-6 days before check-in</li>
                                    <li>No refund: Less than 3 days</li>
                                </ul>
                            </div>

                            <button type="button" class="btn btn-danger w-100" data-bs-toggle="modal" data-bs-target="#cancelBookingModal">
                                <i class="fas fa-times me-2"></i>Request Cancellation
                            </button>
                        </div>
                    </div>

                    <!-- Support -->
                    <div class="card mt-4">
                        <div class="card-body text-center">
                            <h6>Need Help?</h6>
                            <p class="text-muted small">Contact our support team for assistance</p>
                            <a href="#" class="btn btn-outline-primary btn-sm">
                                <i class="fas fa-phone me-1"></i>Contact Support
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Cancel Booking Modal -->
<div class="modal fade" id="cancelBookingModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="{{ route('bookings.cancel-request', $booking->id) }}">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Request Booking Cancellation</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        <strong>Warning:</strong> This action will submit a cancellation request. 
                        Refund amount depends on cancellation timing.
                    </div>

                    <div class="mb-3">
                        <label for="cancellation_reason" class="form-label">Reason for Cancellation *</label>
                        <select class="form-select" id="cancellation_reason" name="cancellation_reason" required>
                            <option value="">Select reason</option>
                            <option value="emergency">Emergency/Urgent</option>
                            <option value="travel_plans">Change in Travel Plans</option>
                            <option value="financial">Financial Reasons</option>
                            <option value="accommodation_issues">Accommodation Issues</option>
                            <option value="health_reasons">Health Reasons</option>
                            <option value="other">Other</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="cancellation_details" class="form-label">Additional Details</label>
                        <textarea class="form-control" id="cancellation_details" name="cancellation_details" 
                                  rows="3" placeholder="Please provide more details about your cancellation request..."></textarea>
                    </div>

                    <!-- Refund Calculation -->
                    <div class="card bg-light">
                        <div class="card-body">
                            <h6>Estimated Refund</h6>
                            <div class="d-flex justify-content-between">
                                <span>Original Amount:</span>
                                <span>LKR {{ number_format($booking->total_amount) }}</span>
                            </div>
                            <div class="d-flex justify-content-between">
                                <span>Estimated Refund:</span>
                                <span id="estimated_refund" class="text-success">-</span>
                            </div>
                            <small class="text-muted">
                                Actual refund amount will be calculated based on our cancellation policy.
                            </small>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-paper-plane me-2"></i>Submit Cancellation Request
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const checkInInput = document.getElementById('check_in_date');
    const checkOutInput = document.getElementById('check_out_date');
    const packageInputs = document.querySelectorAll('input[name="package_id"]');
    const updateReasonSelect = document.getElementById('update_reason');
    const otherReasonDiv = document.getElementById('other_reason_div');
    
    // Set minimum dates
    const today = new Date().toISOString().split('T')[0];
    checkInInput.min = today;
    
    // Update minimum checkout date when checkin changes
    checkInInput.addEventListener('change', function() {
        checkOutInput.min = this.value;
        calculateNewPrice();
    });
    
    checkOutInput.addEventListener('change', calculateNewPrice);
    
    // Package selection
    packageInputs.forEach(function(input) {
        input.addEventListener('change', calculateNewPrice);
    });
    
    // Show/hide other reason field
    updateReasonSelect.addEventListener('change', function() {
        if (this.value === 'other') {
            otherReasonDiv.style.display = 'block';
        } else {
            otherReasonDiv.style.display = 'none';
        }
    });
    
    // Calculate new price
    function calculateNewPrice() {
        const checkIn = checkInInput.value;
        const checkOut = checkOutInput.value;
        const selectedPackage = document.querySelector('input[name="package_id"]:checked');
        
        if (checkIn && checkOut && selectedPackage) {
            const checkInDate = new Date(checkIn);
            const checkOutDate = new Date(checkOut);
            const timeDiff = checkOutDate.getTime() - checkInDate.getTime();
            const daysDiff = Math.ceil(timeDiff / (1000 * 3600 * 24));
            const months = Math.max(1, Math.ceil(daysDiff / 30));
            
            const monthlyRate = parseInt(selectedPackage.dataset.price);
            const subtotal = monthlyRate * months;
            const serviceFee = 1000;
            const newTotal = subtotal + serviceFee;
            const originalTotal = {{ $booking->total_amount }};
            const difference = newTotal - originalTotal;
            
            // Update display
            document.getElementById('new_duration').textContent = months + ' month' + (months > 1 ? 's' : '');
            document.getElementById('new_monthly_rate').textContent = 'LKR ' + monthlyRate.toLocaleString();
            document.getElementById('new_subtotal').textContent = 'LKR ' + subtotal.toLocaleString();
            document.getElementById('new_total').textContent = 'LKR ' + newTotal.toLocaleString();
            
            // Show price difference alert
            const alert = document.getElementById('price_difference_alert');
            const alertText = document.getElementById('price_difference_text');
            
            if (difference > 0) {
                alert.className = 'alert alert-warning';
                alertText.textContent = `You will pay an additional LKR ${difference.toLocaleString()}`;
                alert.classList.remove('d-none');
            } else if (difference < 0) {
                alert.className = 'alert alert-success';
                alertText.textContent = `You will receive a refund of LKR ${Math.abs(difference).toLocaleString()}`;
                alert.classList.remove('d-none');
            } else {
                alert.classList.add('d-none');
            }
        }
    }
    
    // Calculate estimated refund for cancellation
    function calculateEstimatedRefund() {
        const checkInDate = new Date('{{ $booking->check_in_date->format("Y-m-d") }}');
        const today = new Date();
        const daysUntilCheckIn = Math.ceil((checkInDate.getTime() - today.getTime()) / (1000 * 3600 * 24));
        const originalAmount = {{ $booking->total_amount }};
        let refundPercentage = 0;
        
        if (daysUntilCheckIn >= 7) {
            refundPercentage = 100;
        } else if (daysUntilCheckIn >= 3) {
            refundPercentage = 50;
        } else {
            refundPercentage = 0;
        }
        
        const estimatedRefund = Math.floor(originalAmount * refundPercentage / 100);
        document.getElementById('estimated_refund').textContent = 'LKR ' + estimatedRefund.toLocaleString();
    }
    
    // Calculate initial price and refund
    calculateNewPrice();
    calculateEstimatedRefund();
    
    // Form submission confirmation
    document.getElementById('updateBookingForm').addEventListener('submit', function(e) {
        if (!confirm('Are you sure you want to update this booking? Changes may affect the total amount.')) {
            e.preventDefault();
        }
    });
});
</script>
@endpush