@extends('layouts.app')

@section('title', 'Dummy Payment Gateway')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
            <div class="card">
                <div class="card-header text-center">
                    <h4 class="mb-0">Dummy Payment Gateway</h4>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('payments.dummy.process', $booking->id) }}">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">Amount</label>
                            <input type="text" class="form-control" value="{{ $booking->price }}" readonly>
                        </div>
                        <button type="submit" class="btn btn-success w-100">Pay Now (Dummy)</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
