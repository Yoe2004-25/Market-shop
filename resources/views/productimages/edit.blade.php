@extends('layouts.app')

@section('title', 'Edit Product Image')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-3">
    <h3 class="fw-bold"><i class="bi bi-pencil-square text-warning"></i> Edit Image #{{ $image->id }}</h3>
    <a href="{{ route('product-images.index') }}" class="btn btn-outline-secondary">
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

        <form action="{{ route('product-images.update', $image->id) }}"
              method="POST"
              enctype="multipart/form-data">
            @csrf @method('PUT')

            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Product</label>
                    <select name="product_id" class="form-select">
                        @foreach($products as $product)
                            <option value="{{ $product->id }}"
                                @selected(old('product_id', $image->product_id) == $product->id)>
                                #{{ $product->id }} — {{ $product->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Replace Image</label>
                    <input type="file" name="image" id="image"
                           class="form-control" accept="image/*">
                    <small class="text-muted">اتركه فاضي للإبقاء على الصورة الحالية</small>
                </div>

                <div class="col-12">
                    <label class="form-label">Current Image</label>
                    <div>
                        <img src="{{ $image->image_url }}"
                             class="img-thumbnail" style="max-height:200px;">
                    </div>
                </div>

                <div class="col-12">
                    <div class="form-check">
                        <input type="checkbox" name="primary" id="primary"
                               value="1" class="form-check-input"
                               @checked(old('primary', $image->primary))>
                        <label for="primary" class="form-check-label">Primary</label>
                    </div>
                </div>
            </div>

            <div class="mt-4 d-flex gap-2">
                <button class="btn btn-warning"><i class="bi bi-save"></i> Update</button>
                <a href="{{ route('product-images.index') }}" class="btn btn-outline-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>

@endsection