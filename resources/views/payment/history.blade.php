@extends('layouts.app')

@section('title', 'Payment History - University Hostel Management')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <!-- Payment History Header -->
            <div class="text-center mb-5">
                <h1 class="h2 mb-3">Payment History</h1>
                <p class="lead text-muted">View all your payment transactions</p>
            </div>

            <!-- Payment History Table -->
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-history me-2"></i>Payment Transactions
                    </h5>
                </div>
                <div class="card-body">
                    @if($payments && $payments->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>Payment ID</th>
                                        <th>Booking Reference</th>
                                        <th>Hostel</th>
                                        <th>Amount</th>
                                        <th>Method</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($payments as $payment)
                                        <tr>
                                            <td>
                                                <small class="text-muted">
                                                    {{ $payment->created_at->format('M d, Y') }}<br>
                                                    <span class="text-muted">{{ $payment->created_at->format('h:i A') }}</span>
                                                </small>
                                            </td>
                                            <td>
                                                <strong>{{ $payment->payment_reference ?? 'N/A' }}</strong>
                                            </td>
                                            <td>
                                                @if(isset($payment->booking))
                                                    {{ $payment->booking->booking_reference ?? 'N/A' }}
                                                @else
                                                    N/A
                                                @endif
                                            </td>
                                            <td>
                                                @if(isset($payment->booking) && isset($payment->booking->hostel))
                                                    {{ $payment->booking->hostel->name ?? 'N/A' }}
                                                @else
                                                    N/A
                                                @endif
                                            </td>
                                            <td>
                                                <strong class="text-success">
                                                    {{ $payment->formatted_amount ?? 'LKR ' . number_format($payment->amount ?? 0, 2) }}
                                                </strong>
                                            </td>
                                            <td>
                                                <span class="badge bg-info">
                                                    {{ ucfirst($payment->payment_method ?? 'N/A') }}
                                                </span>
                                            </td>
                                            <td>
                                                @if($payment->status === 'completed' || $payment->status === 'paid')
                                                    <span class="badge bg-success">Completed</span>
                                                @elseif($payment->status === 'pending')
                                                    <span class="badge bg-warning">Pending</span>
                                                @elseif($payment->status === 'failed')
                                                    <span class="badge bg-danger">Failed</span>
                                                @else
                                                    <span class="badge bg-secondary">{{ ucfirst($payment->status ?? 'Unknown') }}</span>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <a href="{{ route('payments.details', $payment->id) }}" 
                                                       class="btn btn-sm btn-outline-primary">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    @if($payment->status === 'completed' || $payment->status === 'paid')
                                                        <a href="{{ route('payments.refund', $payment->id) }}" 
                                                           class="btn btn-sm btn-outline-warning"
                                                           onclick="return confirm('Are you sure you want to request a refund?')">
                                                            <i class="fas fa-undo"></i>
                                                        </a>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        @if($payments->hasPages())
                            <div class="d-flex justify-content-center mt-4">
                                {{ $payments->links() }}
                            </div>
                        @endif
                    @else
                        <div class="text-center py-5">
                            <div class="mb-4">
                                <i class="fas fa-credit-card text-muted" style="font-size: 4rem;"></i>
                            </div>
                            <h5 class="text-muted">No Payment History</h5>
                            <p class="text-muted">You haven't made any payments yet.</p>
                            <a href="{{ route('hostels') }}" class="btn btn-primary">
                                <i class="fas fa-search me-2"></i>Browse Hostels
                            </a>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Help Section -->
            <div class="card mt-4">
                <div class="card-body">
                    <h6 class="mb-3">
                        <i class="fas fa-question-circle me-2"></i>Need Help?
                    </h6>
                    <p class="mb-3">If you have any questions about your payments, our support team is here to help.</p>
                    <div class="row">
                        <div class="col-md-4 mb-2">
                            <a href="tel:+94112345678" class="btn btn-outline-primary btn-sm w-100">
                                <i class="fas fa-phone me-1"></i>Call Us
                            </a>
                        </div>
                        <div class="col-md-4 mb-2">
                            <a href="mailto:support@hostel.com" class="btn btn-outline-primary btn-sm w-100">
                                <i class="fas fa-envelope me-1"></i>Email Support
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
@endsection









