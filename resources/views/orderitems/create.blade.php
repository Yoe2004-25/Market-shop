@extends('layouts.app')

@section('title', 'Create Orders Item')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-3">
    <h3 class="fw-bold"><i class="bi bi-plus-circle text-primary"></i> New Orders Item</h3>
    <a href="{{ route('orderitems.index') }}" class="btn btn-outline-secondary">
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

        <form action="{{ route('orderitems.store') }}" method="POST">
            @csrf
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Order ID <span class="text-danger">*</span></label>
                    <input type="number" name="order_id" class="form-control"
                           value="{{ old('order_id', request('order_id')) }}" required>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Product ID <span class="text-danger">*</span></label>
                    <input type="number" name="product_id" class="form-control"
                           value="{{ old('product_id', request('product_id')) }}" required>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Quantity <span class="text-danger">*</span></label>
                    <input type="number" name="quantity" id="quantity" class="form-control"
                           value="{{ old('quantity', 1) }}" min="1" required>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Price <span class="text-danger">*</span></label>
                    <input type="number" step="0.01" name="price" id="price" class="form-control"
                           value="{{ old('price') }}" min="0" required>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Subtotal</label>
                    <input type="number" step="0.01" name="subtotal" id="subtotal"
                           class="form-control" value="{{ old('subtotal') }}" readonly>
                </div>

                <div class="col-12">
                    <label class="form-label">Details <span class="text-danger">*</span></label>
                    <textarea name="details" rows="4" class="form-control"
                              required>{{ old('details') }}</textarea>
                </div>
            </div>

            <div class="mt-4 d-flex gap-2">
                <button class="btn btn-primary"><i class="bi bi-save"></i> Save</button>
                <a href="{{ route('orderitems.index') }}" class="btn btn-outline-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>

@endsection

@push('scripts')
<script>
    const qty = document.getElementById('quantity');
    const price = document.getElementById('price');
    const subtotal = document.getElementById('subtotal');

    function calc() {
        const q = parseFloat(qty.value) || 0;
        const p = parseFloat(price.value) || 0;
        subtotal.value = (q * p).toFixed(2);
    }

    qty.addEventListener('input', calc);
    price.addEventListener('input', calc);
    calc();
</script>
@endpush