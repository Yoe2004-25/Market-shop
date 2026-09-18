@extends('layouts.app')

@section('title', $product->name)

@section('content')

<div class="d-flex justify-content-between align-items-center mb-3">
    <h3 class="fw-bold"><i class="bi bi-box-seam text-info"></i> {{ $product->name }}</h3>
    <div>
        @can('update', $product)
            <a href="{{ route('products.edit', $product) }}" class="btn btn-warning">
                <i class="bi bi-pencil"></i> Edit
            </a>
        @endcan
        <a href="{{ route('products.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left"></i> Back
        </a>
    </div>
</div>

<div class="row g-3">
    <div class="col-md-5">
        @if($product->image)
            <img src="{{ $product->image_url }}" class="img-fluid rounded border">
        @else
            <div class="border rounded p-5 text-center text-muted">
                <i class="bi bi-image fs-1"></i>
                <p class="mb-0">No image</p>
            </div>
        @endif
    </div>
    <div class="col-md-7">
        <div class="border rounded p-3 bg-light mb-3">
            <small class="text-muted">SKU</small>
            <strong class="d-block">{{ $product->sku }}</strong>
        </div>
        <div class="border rounded p-3 bg-light mb-3">
            <small class="text-muted">Description</small>
            <p class="mb-0">{{ $product->description }}</p>
        </div>
        <div class="row g-2">
            <div class="col-md-6">
                <div class="border rounded p-3 bg-light">
                    <small class="text-muted d-block">Price</small>
                    <strong>{{ number_format($product->price, 2) }}</strong>
                </div>
            </div>
            <div class="col-md-6">
                <div class="border rounded p-3 bg-light">
                    <small class="text-muted d-block">Final Price</small>
                    <strong class="text-success">{{ number_format($product->final_price, 2) }}</strong>
                </div>
            </div>
            <div class="col-md-6">
                <div class="border rounded p-3 bg-light">
                    <small class="text-muted d-block">Stock</small>
                    <strong>{{ $product->stock }}</strong>
                </div>
            </div>
            <div class="col-md-6">
                <div class="border rounded p-3 bg-light">
                    <small class="text-muted d-block">Status</small>
                    <span class="badge bg-{{ $product->status === 'active' ? 'success' : 'secondary' }}">
                        {{ ucfirst($product->status) }}
                    </span>
                </div>
            </div>
        </div>
    </div>
</div>

@can('delete', $product)
    <div class="card mt-3">
        <div class="card-body">
            <form action="{{ route('products.destroy', $product) }}" method="POST"
                  onsubmit="return confirm('Delete this product?')">
                @csrf @method('DELETE')
                <button class="btn btn-outline-danger"><i class="bi bi-trash"></i> Delete</button>
            </form>
        </div>
    </div>
@endcan

@endsection