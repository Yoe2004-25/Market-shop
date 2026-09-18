@extends('layouts.app')

@section('title', 'Edit Orders Item')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-3">
    <h3 class="fw-bold">
        <i class="bi bi-pencil-square text-warning"></i> Edit Item #{{ $item->id }}
    </h3>
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

        <form action="{{ route('orderitems.update', $item->id) }}" method="POST">
            @csrf @method('PUT')
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Order</label>
                    <input type="text" value="#{{ $item->order_id }}" class="form-control" disabled>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Product</label>
                    <input type="text" value="{{ $item->product?->name ?? '—' }}"
                           class="form-control" disabled>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Quantity</label>
                    <input type="number" name="quantity" id="quantity" class="form-control"
                           value="{{ old('quantity', $item->quantity) }}" min="1">
                </div>

                <div class="col-md-4">
                    <label class="form-label">Price</label>
                    <input type="number" step="0.01" name="price" id="price" class="form-control"
                           value="{{ old('price', $item->price) }}" min="0">
                </div>

                <div class="col-md-4">
                    <label class="form-label">Subtotal</label>
                    <input type="number" step="0.01" name="subtotal" id="subtotal"
                           class="form-control" value="{{ old('subtotal', $item->subtotal) }}" readonly>
                </div>

                <div class="col-12">
                    <label class="form-label">Details</label>
                    <textarea name="details" rows="4" class="form-control">{{ old('details', $item->details) }}</textarea>
                </div>
            </div>

            <div class="mt-4 d-flex gap-2">
                <button class="btn btn-warning"><i class="bi bi-save"></i> Update</button>
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
</script>
@endpush