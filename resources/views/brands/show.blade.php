@extends('layouts.app')

@section('title', 'Brand Details')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-3">
    <h3 class="fw-bold"><i class="bi bi-tag text-info"></i> Brand #{{ $brand->id }}</h3>
    <div>
        <a href="{{ route('brands.edit', $brand->id) }}" class="btn btn-warning">
            <i class="bi bi-pencil"></i> Edit
        </a>
        <a href="{{ route('brands.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left"></i> Back
        </a>
    </div>
</div>

<div class="card">
    <div class="card-body">

        <div class="row g-3">
            <div class="col-md-4 text-center">
                @if($brand->logo)
                    <img src="{{ asset('storage/' . $brand->logo) }}"
                         alt="{{ $brand->name }}"
                         class="img-fluid rounded border p-2"
                         style="max-height:180px; object-fit:contain;">
                @else
                    <div class="border rounded p-5 text-muted">
                        <i class="bi bi-image fs-1"></i>
                        <p class="mb-0">No logo</p>
                    </div>
                @endif
            </div>

            <div class="col-md-8">
                <div class="border rounded p-3 bg-light mb-3">
                    <small class="text-muted d-block">Name</small>
                    <strong class="fs-5">{{ $brand->name }}</strong>
                </div>

                <div class="border rounded p-3 bg-light mb-3">
                    <small class="text-muted d-block">Products Count</small>
                    <strong>{{ $brand->products->count() }}</strong>
                </div>

                <div class="border rounded p-3 bg-light mb-3">
                    <small class="text-muted d-block">Created At</small>
                    <strong>{{ $brand->created_at?->format('Y-m-d H:i') }}</strong>
                </div>

                <div class="border rounded p-3 bg-light">
                    <small class="text-muted d-block">Updated At</small>
                    <strong>{{ $brand->updated_at?->format('Y-m-d H:i') }}</strong>
                </div>
            </div>
        </div>

        <hr class="my-4">

        <form action="{{ route('brands.destroy', $brand->id) }}"
              method="POST" onsubmit="return confirm('Delete this brand?')">
            @csrf @method('DELETE')
            <button class="btn btn-outline-danger">
                <i class="bi bi-trash"></i> Delete
            </button>
        </form>

    </div>
</div>

@endsection