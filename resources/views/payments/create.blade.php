@extends('layouts.app')

@section('title', 'New Payment')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-3">
    <h3 class="fw-bold"><i class="bi bi-credit-card text-primary"></i> New Payment</h3>
    <a href="{{ route('payments.index') }}" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left"></i> Back
    </a>
</div>

<div class="card">
    <div class="card-body">
        @if($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">@foreach($errors->all() as $e) <li>{{ $e }}</li> @endforeach</ul>
            </div>
        @endif

        <form action="{{ route('payments.store') }}" method="POST">
            @csrf
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Order <span class="text-danger">*</span></label>
                    <select name="order_id" class="form-select" required>
                        <option value="">-- Select Order --</option>
                        @foreach($orders as $order)
                            <option value="{{ $order->id }}" @selected(old('order_id') == $order->id)>
                                #{{ $order->id }} — {{ $order->name }} — {{ number_format($order->grand_total, 2) }} EGP
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Payment Method <span class="text-danger">*</span></label>
                    <div class="d-flex gap-3 pt-2">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="payment_method"
                                   id="cash" value="cash" @checked(old('payment_method') === 'cash') required>
                            <label class="form-check-label" for="cash">
                                <i class="bi bi-cash"></i> Cash
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="payment_method"
                                   id="visa" value="visa" @checked(old('payment_method') === 'visa')>
                            <label class="form-check-label" for="visa">
                                <i class="bi bi-credit-card"></i> Visa
                            </label>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-4 d-flex gap-2">
                <button class="btn btn-primary"><i class="bi bi-save"></i> Create Payment</button>
                <a href="{{ route('payments.index') }}" class="btn btn-outline-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>

@endsection