@extends('layouts.app')

@section('title', 'Wishlist Item')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-3">
    <h3 class="fw-bold"><i class="bi bi-heart-fill text-danger"></i> Wishlist Item #{{ $wishlist->id }}</h3>
    <div>
        @can('delete', $wishlist)
            <form action="{{ route('wishlist.destroy', $wishlist) }}" method="POST"
                  class="d-inline" onsubmit="return confirm('Remove?')">
                @csrf @method('DELETE')
                <button class="btn btn-danger">
                    <i class="bi bi-heartbreak"></i> Remove
                </button>
            </form>
        @endcan
        <a href="{{ route('wishlist.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left"></i> Back
        </a>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <div class="row g-3">
            <div class="col-md-4">
                @if($wishlist->product?->image)
                    <img src="{{ $wishlist->product->image_url }}" class="img-fluid rounded">
                @endif
            </div>
            <div class="col-md-8">
                <div class="border rounded p-3 bg-light mb-3">
                    <small class="text-muted">Product</small>
                    <strong class="d-block">{{ $wishlist->product?->name ?? '—' }}</strong>
                </div>
                <div class="border rounded p-3 bg-light mb-3">
                    <small class="text-muted">Price</small>
                    <strong class="text-success d-block">
                        {{ number_format($wishlist->product?->final_price ?? 0, 2) }} EGP
                    </strong>
                </div>
                <div class="border rounded p-3 bg-light">
                    <small class="text-muted">Added at</small>
                    <strong class="d-block">{{ $wishlist->created_at?->format('Y-m-d H:i') }}</strong>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection