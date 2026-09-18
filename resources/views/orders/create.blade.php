@extends('layouts.app')

@section('title', 'Create Order')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-3">
    <h3 class="fw-bold"><i class="bi bi-plus-circle text-primary"></i> New Order</h3>
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

        <form action="{{ route('orders.store') }}" method="POST">
            @csrf
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Order Name <span class="text-danger">*</span></label>
                    <input type="text" name="name" value="{{ old('name') }}"
                           class="form-control @error('name') is-invalid @enderror" required>
                    @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label">Coupon ID</label>
                    <input type="number" name="coupon_id" value="{{ old('coupon_id') }}"
                           class="form-control @error('coupon_id') is-invalid @enderror">
                    @error('coupon_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="col-md-4">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-select">
                        <option value="pending" @selected(old('status') === 'pending')>Pending</option>
                        <option value="success" @selected(old('status') === 'success')>Success</option>
                        <option value="failed"  @selected(old('status') === 'failed')>Failed</option>
                    </select>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Payment Status</label>
                    <select name="payment_status" class="form-select">
                        <option value="pending"  @selected(old('payment_status') === 'pending')>Pending</option>
                        <option value="success"  @selected(old('payment_status') === 'success')>Success</option>
                        <option value="failed"   @selected(old('payment_status') === 'failed')>Failed</option>
                        <option value="refunded" @selected(old('payment_status') === 'refunded')>Refunded</option>
                    </select>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Total <span class="text-danger">*</span></label>
                    <input type="number" step="0.01" name="total" id="total"
                           value="{{ old('total', 0) }}"
                           class="form-control" required>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Shipping Cost</label>
                    <input type="number" step="0.01" name="shipping_cost" id="shipping_cost"
                           value="{{ old('shipping_cost', 0) }}" class="form-control">
                </div>

                <div class="col-md-4">
                    <label class="form-label">Tax</label>
                    <input type="number" step="0.01" name="tax" id="tax"
                           value="{{ old('tax', 0) }}" class="form-control">
                </div>

                <div class="col-md-4">
                    <label class="form-label">Grand Total</label>
                    <input type="number" step="0.01" name="grand_total" id="grand_total"
                           value="{{ old('grand_total', 0) }}" class="form-control" readonly>
                </div>
            </div>

            <div class="mt-4 d-flex gap-2">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-save"></i> Save
                </button>
                <a href="{{ route('orders.index') }}" class="btn btn-outline-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>

@endsection

@push('scripts')
<script>
    const total = document.getElementById('total');
    const ship  = document.getElementById('shipping_cost');
    const tax   = document.getElementById('tax');
    const gt    = document.getElementById('grand_total');

    function calc() {
        gt.value = (
            (parseFloat(total.value) || 0) +
            (parseFloat(ship.value) || 0) +
            (parseFloat(tax.value) || 0)
        ).toFixed(2);
    }

    [total, ship, tax].forEach(el => el.addEventListener('input', calc));
    calc();
</script>
@endpush