@extends('layouts.app')

@section('title', 'Create Review')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-3">
    <h3 class="fw-bold"><i class="bi bi-plus-circle text-primary"></i> New Review</h3>
    <a href="{{ route('reviews.index') }}" class="btn btn-outline-secondary">
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

        <form action="{{ route('reviews.store') }}" method="POST">
            @csrf
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Product ID <span class="text-danger">*</span></label>
                    <input type="number" name="product_id" class="form-control"
                           value="{{ old('product_id', request('product_id')) }}" required>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Rating <span class="text-danger">*</span></label>
                    <div class="d-flex gap-2 pt-2">
                        @for($i = 1; $i <= 5; $i++)
                            <div class="form-check">
                                <input class="form-check-input" type="radio"
                                       name="rating" id="rating{{ $i }}"
                                       value="{{ $i }}" @checked(old('rating') == $i) required>
                                <label class="form-check-label" for="rating{{ $i }}">
                                    {{ $i }} <i class="bi bi-star-fill text-warning"></i>
                                </label>
                            </div>
                        @endfor
                    </div>
                </div>

                <div class="col-12">
                    <label class="form-label">Comment</label>
                    <textarea name="comment" rows="4" class="form-control"
                              maxlength="2000">{{ old('comment') }}</textarea>
                </div>
            </div>

            <div class="mt-4 d-flex gap-2">
                <button class="btn btn-primary"><i class="bi bi-save"></i> Save</button>
                <a href="{{ route('reviews.index') }}" class="btn btn-outline-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>

@endsection