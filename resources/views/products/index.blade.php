@extends('layouts.app')

@section('title', 'Products')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-3">
    <h3 class="fw-bold"><i class="bi bi-box-seam text-primary"></i> Products</h3>
    @can('create', App\Models\Products::class)
        <a href="{{ route('products.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-lg"></i> Add Product
        </a>
    @endcan
</div>

{{-- Filters --}}
<div class="card mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('products.index') }}" class="row g-2">
            <div class="col-md-4">
                <input type="text" name="search" class="form-control"
                       placeholder="Search name, SKU, description..."
                       value="{{ request('search') }}">
            </div>
            <div class="col-md-2">
                <input type="number" name="category_id" class="form-control"
                       placeholder="Category ID" value="{{ request('category_id') }}">
            </div>
            <div class="col-md-2">
                <input type="number" name="brand_id" class="form-control"
                       placeholder="Brand ID" value="{{ request('brand_id') }}">
            </div>
            <div class="col-md-2">
                <select name="status" class="form-select">
                    <option value="">-- Status --</option>
                    <option value="active"   @selected(request('status') === 'active')>Active</option>
                    <option value="inactive" @selected(request('status') === 'inactive')>Inactive</option>
                </select>
            </div>
            <div class="col-md-2 d-flex gap-2">
                <button class="btn btn-primary w-100"><i class="bi bi-search"></i></button>
                <a href="{{ route('products.index') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-clockwise"></i>
                </a>
            </div>
        </form>
    </div>
</div>

{{-- Grid --}}
<div class="row g-3">
    @forelse($products as $product)
        <div class="col-md-3 col-sm-6">
            <div class="card h-100">
                @if($product->image)
                    <img src="{{ $product->image_url }}" class="card-img-top"
                         style="height:180px; object-fit:cover;">
                @else
                    <div class="bg-light d-flex align-items-center justify-content-center"
                         style="height:180px;">
                        <i class="bi bi-image fs-1 text-muted"></i>
                    </div>
                @endif

                <div class="card-body">
                    <h6 class="card-title text-truncate">{{ $product->name }}</h6>
                    <p class="mb-1 small text-muted">SKU: {{ $product->sku }}</p>
                    <p class="mb-1">
                        <span class="text-success fw-bold">
                            {{ number_format($product->final_price, 2) }}
                        </span>
                        @if($product->discount > 0)
                            <small class="text-muted text-decoration-line-through">
                                {{ number_format($product->price, 2) }}
                            </small>
                        @endif
                    </p>
                    <span class="badge bg-{{ $product->status === 'active' ? 'success' : 'secondary' }}">
                        {{ ucfirst($product->status) }}
                    </span>
                </div>

                <div class="card-footer d-flex justify-content-between">
                    <a href="{{ route('products.show', $product) }}"
                       class="btn btn-sm btn-outline-info"><i class="bi bi-eye"></i></a>

                    @can('update', $product)
                        <a href="{{ route('products.edit', $product) }}"
                           class="btn btn-sm btn-outline-warning"><i class="bi bi-pencil"></i></a>
                    @endcan

                    @can('delete', $product)
                        <form action="{{ route('products.destroy', $product) }}"
                              method="POST" onsubmit="return confirm('Delete?')" class="d-inline">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-outline-danger">
                                <i class="bi bi-trash"></i>
                            </button>
                        </form>
                    @endcan
                </div>
            </div>
        </div>
    @empty
        <div class="col-12">
            <div class="alert alert-info text-center">
                <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                No products found.
            </div>
        </div>
    @endforelse
</div>

<div class="mt-3">{{ $products->links() }}</div>

@endsection