@extends('layouts.app')

@section('title', 'Image Details')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-3">
    <h3 class="fw-bold"><i class="bi bi-image text-info"></i> Image #{{ $image->id }}</h3>
    <div>
        <a href="{{ route('product-images.edit', $image->id) }}" class="btn btn-warning">
            <i class="bi bi-pencil"></i> Edit
        </a>
        <a href="{{ route('product-images.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left"></i> Back
        </a>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <div class="row g-3">
            <div class="col-md-5">
                <img src="{{ $image->image_url }}"
                     class="img-fluid rounded border"
                     alt="Product image">
            </div>

            <div class="col-md-7">
                <div class="border rounded p-3 bg-light mb-3">
                    <small class="text-muted d-block">Product</small>
                    <strong>{{ $image->product?->name ?? '—' }} (#{{ $image->product_id }})</strong>
                </div>

                <div class="border rounded p-3 bg-light mb-3">
                    <small class="text-muted d-block">Primary?</small>
                    @if($image->primary)
                        <span class="badge bg-warning text-dark">
                            <i class="bi bi-star-fill"></i> Primary
                        </span>
                    @else
                        <span class="badge bg-secondary">Regular</span>
                    @endif
                </div>

                <div class="border rounded p-3 bg-light mb-3">
                    <small class="text-muted d-block">Image Path</small>
                    <code>{{ $image->image }}</code>
                </div>

                <div class="border rounded p-3 bg-light">
                    <small class="text-muted d-block">Created At</small>
                    <strong>{{ $image->created_at?->format('Y-m-d H:i') }}</strong>
                </div>
            </div>
        </div>

        <hr class="my-4">

        <form action="{{ route('product-images.destroy', $image->id) }}"
              method="POST" onsubmit="return confirm('Delete this image?')">
            @csrf @method('DELETE')
            <button class="btn btn-outline-danger">
                <i class="bi bi-trash"></i> Delete
            </button>
        </form>
    </div>
</div>

@endsection