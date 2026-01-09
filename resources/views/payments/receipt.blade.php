@extends('layouts.app')

@section('title', 'Payment Receipt')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header text-center">
                    <h4 class="mb-0">Payment Receipt</h4>
                </div>
                <div class="card-body">
                    <p><strong>Payment Reference:</strong> {{ $payment->payment_reference }}</p>
                    <p><strong>Transaction ID:</strong> {{ $payment->transaction_id }}</p>
                    <p><strong>Amount:</strong> LKR {{ number_format($payment->amount, 2) }}</p>
                    <p><strong>Status:</strong> {{ ucfirst($payment->payment_status) }}</p>
                    <p><strong>Date:</strong> {{ $payment->paid_at->format('Y-m-d H:i') }}</p>
                    <hr>
                    <p>Thank you for your payment!</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
