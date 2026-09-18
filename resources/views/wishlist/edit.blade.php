@extends('layouts.app')

@section('title', 'Edit Wishlist')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-3">
    <h3 class="fw-bold"><i class="bi bi-pencil-square text-warning"></i> Edit Wishlist #{{ $wishlist->id }}</h3>
    <a href="{{ route('wishlist.index') }}" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left"></i> Back
    </a>
</div>

<div class="card">
    <div class="card-body">
        @if($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach($errors->all() as $e) <li>{{ $e }}</li> @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('wishlist.update', $wishlist) }}" method="POST">
            @csrf @method('PUT')
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Product ID</label>
                    <input type="number" name="product_id" class="form-control"
                           value="{{ old('product_id', $wishlist->product_id) }}">
                </div>
            </div>

            <div class="mt-4 d-flex gap-2">
                <button class="btn btn-warning"><i class="bi bi-save"></i> Update</button>
                <a href="{{ route('wishlist.index') }}" class="btn btn-outline-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>

@endsection