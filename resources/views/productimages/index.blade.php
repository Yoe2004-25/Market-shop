@extends('layouts.app')

@section('title', 'Product Images')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-3">
    <h3 class="fw-bold"><i class="bi bi-images text-primary"></i> Product Images</h3>
    <a href="{{ route('product-images.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-lg"></i> Upload Image
    </a>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<div class="row g-3">
    @forelse($images as $image)
        <div class="col-md-3 col-sm-6">
            <div class="card h-100 position-relative">
                @if($image->primary)
                    <span class="badge bg-warning text-dark position-absolute top-0 end-0 m-2">
                        <i class="bi bi-star-fill"></i> Primary
                    </span>
                @endif

                <img src="{{ $image->image_url }}"
                     class="card-img-top"
                     alt="Product image"
                     style="height:200px; object-fit:cover;">

                <div class="card-body">
                    <small class="text-muted d-block">Product:</small>
                    <strong>{{ $image->product?->name ?? '—' }}</strong>
                </div>

                <div class="card-footer d-flex justify-content-between">
                    <a href="{{ route('product-images.show', $image->id) }}"
                       class="btn btn-sm btn-outline-info">
                        <i class="bi bi-eye"></i>
                    </a>
                    <a href="{{ route('product-images.edit', $image->id) }}"
                       class="btn btn-sm btn-outline-warning">
                        <i class="bi bi-pencil"></i>
                    </a>
                    <form action="{{ route('product-images.destroy', $image->id) }}"
                          method="POST" class="d-inline"
                          onsubmit="return confirm('Delete this image?')">
                        @csrf @method('DELETE')
                        <button class="btn btn-sm btn-outline-danger">
                            <i class="bi bi-trash"></i>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    @empty
        <div class="col-12">
            <div class="alert alert-info text-center">
                <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                No images yet. <a href="{{ route('product-images.create') }}">Upload one</a>.
            </div>
        </div>
    @endforelse
</div>

<div class="mt-3">{{ $images->links() }}</div>

@endsection