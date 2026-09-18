@extends('layouts.app')

@section('title', 'Upload Product Image')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-3">
    <h3 class="fw-bold"><i class="bi bi-plus-circle text-primary"></i> Upload Image</h3>
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

        <form action="{{ route('product-images.store') }}"
              method="POST"
              enctype="multipart/form-data">
            @csrf

            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Product <span class="text-danger">*</span></label>
                    <select name="product_id" class="form-select" required>
                        <option value="">-- Choose product --</option>
                        @foreach($products as $product)
                            <option value="{{ $product->id }}"
                                @selected(old('product_id') == $product->id)>
                                #{{ $product->id }} — {{ $product->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Image <span class="text-danger">*</span></label>
                    <input type="file" name="image" id="image"
                           class="form-control" accept="image/*" required>
                </div>

                <div class="col-12">
                    <div class="form-check">
                        <input type="checkbox" name="primary" id="primary"
                               value="1" class="form-check-input"
                               @checked(old('primary'))>
                        <label for="primary" class="form-check-label">
                            Set as primary image
                        </label>
                    </div>
                </div>

                {{-- Live Preview --}}
                <div class="col-12 d-none" id="preview-box">
                    <label class="form-label">Preview:</label>
                    <div>
                        <img id="preview-img" src="" class="img-thumbnail" style="max-height:250px;">
                    </div>
                </div>
            </div>

            <div class="mt-4 d-flex gap-2">
                <button class="btn btn-primary"><i class="bi bi-save"></i> Save</button>
                <a href="{{ route('product-images.index') }}" class="btn btn-outline-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>

@endsection

@push('scripts')
<script>
    document.getElementById('image').addEventListener('change', function (e) {
        const file = e.target.files[0];
        const box = document.getElementById('preview-box');
        const img = document.getElementById('preview-img');

        if (!file) { box.classList.add('d-none'); return; }

        const reader = new FileReader();
        reader.onload = ev => { img.src = ev.target.result; box.classList.remove('d-none'); };
        reader.readAsDataURL(file);
    });
</script>
@endpush