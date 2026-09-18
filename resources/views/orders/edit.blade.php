@extends('layouts.app')

@section('title', 'Edit Order')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-3">
    <h3 class="fw-bold">
        <i class="bi bi-pencil-square text-warning"></i> Edit Order #{{ $order->id }}
    </h3>
    <a href="{{ route('orders.index') }}" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left"></i> Back
    </a>
</div>

<div class="card">
    <div class="card-body">
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('orders.update', $order->id) }}" method="POST">
            @csrf @method('PUT')
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Order Name</label>
                    <input type="text" name="name" value="{{ old('name', $order->name) }}"
                           class="form-control @error('name') is-invalid @enderror">
                    @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label">Coupon</label>
                    <input type="text" value="{{ $order->coupon?->code ?? '—' }}"
                           class="form-control" disabled>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-select">
                        @foreach(['pending', 'success', 'failed'] as $s)
                            <option value="{{ $s }}" @selected(old('status', $order->status) === $s)>
                                {{ ucfirst($s) }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Payment Status</label>
                    <select name="payment_status" class="form-select">
                        @foreach(['pending', 'success', 'failed', 'refunded'] as $s)
                            <option value="{{ $s }}" @selected(old('payment_status', $order->payment_status) === $s)>
                                {{ ucfirst($s) }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Total</label>
                    <input type="number" step="0.01" name="total"
                           value="{{ old('total', $order->total) }}" class="form-control">
                </div>

                <div class="col-md-4">
                    <label class="form-label">Shipping Cost</label>
                    <input type="number" step="0.01" name="shipping_cost"
                           value="{{ old('shipping_cost', $order->shipping_cost) }}"
                           class="form-control">
                </div>

                <div class="col-md-4">
                    <label class="form-label">Tax</label>
                    <input type="number" step="0.01" name="tax"
                           value="{{ old('tax', $order->tax) }}" class="form-control">
                </div>

                <div class="col-md-4">
                    <label class="form-label">Grand Total</label>
                    <input type="number" step="0.01" name="grand_total"
                           value="{{ old('grand_total', $order->grand_total) }}"
                           class="form-control">
                </div>
            </div>

            <div class="mt-4 d-flex gap-2">
                <button type="submit" class="btn btn-warning">
                    <i class="bi bi-save"></i> Update
                </button>
                <a href="{{ route('orders.index') }}" class="btn btn-outline-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>

@endsection