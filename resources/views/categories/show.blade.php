@extends('layouts.app')

@section('title', 'Category Details')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-3">
    <h3 class="fw-bold">
        <i class="bi bi-grid-3x3-gap text-info"></i>
        Category #{{ $category->id }}
    </h3>
    <div>
        <a href="{{ route('categories.edit', $category->id) }}" class="btn btn-warning">
            <i class="bi bi-pencil"></i> Edit
        </a>
        <a href="{{ route('categories.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left"></i> Back
        </a>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <div class="row g-3">
            <div class="col-md-4 text-center">
                @if($category->image)
                    <img src="{{ $category->image_url }}"
                         alt="{{ $category->name }}"
                         class="img-fluid rounded border p-2"
                         style="max-height:200px; object-fit:cover;">
                @else
                    <div class="border rounded p-5 text-muted">
                        <i class="bi bi-image fs-1"></i>
                        <p class="mb-0">No image</p>
                    </div>
                @endif
            </div>

            <div class="col-md-8">
                <div class="border rounded p-3 bg-light mb-3">
                    <small class="text-muted d-block">Name</small>
                    <strong class="fs-5">{{ $category->name }}</strong>
                </div>

                <div class="border rounded p-3 bg-light mb-3">
                    <small class="text-muted d-block">Slug</small>
                    <code>{{ $category->slug }}</code>
                </div>

                <div class="border rounded p-3 bg-light mb-3">
                    <small class="text-muted d-block">Description</small>
                    <p class="mb-0">{{ $category->description }}</p>
                </div>

                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="border rounded p-3 bg-light">
                            <small class="text-muted d-block">Products Count</small>
                            <strong>{{ $category->products->count() }}</strong>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="border rounded p-3 bg-light">
                            <small class="text-muted d-block">Status</small>
                            @if($category->status)
                                <span class="badge bg-success">Active</span>
                            @else
                                <span class="badge bg-secondary">Inactive</span>
                            @endif
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="border rounded p-3 bg-light">
                            <small class="text-muted d-block">Created At</small>
                            <strong>{{ $category->created_at?->format('Y-m-d H:i') }}</strong>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="border rounded p-3 bg-light">
                            <small class="text-muted d-block">Updated At</small>
                            <strong>{{ $category->updated_at?->format('Y-m-d H:i') }}</strong>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @if($category->products->count() > 0)
            <hr class="my-4">
            <h5 class="fw-bold">
                <i class="bi bi-box-seam"></i> Products in this category
            </h5>
            <div class="row g-3 mt-2">
                @foreach($category->products->take(6) as $product)
                    <div class="col-md-4">
                        <div class="border rounded p-3 bg-light">
                            <strong>{{ $product->name }}</strong>
                            <p class="mb-0 text-success">
                                {{ number_format($product->price, 2) }} EGP
                            </p>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif

        <hr class="my-4">
        <form action="{{ route('categories.destroy', $category->id) }}"
              method="POST"
              onsubmit="return confirm('Delete this category?')">
            @csrf @method('DELETE')
            <button class="btn btn-outline-danger">
                <i class="bi bi-trash"></i> Delete Category
            </button>
        </form>
    </div>
</div>

@endsection