@extends('layouts.app')

@section('title', 'Edit Review')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-3">
    <h3 class="fw-bold"><i class="bi bi-pencil-square text-warning"></i> Edit Review #{{ $review->id }}</h3>
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

        <form action="{{ route('reviews.update', $review) }}" method="POST">
            @csrf @method('PUT')
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Product</label>
                    <input type="text" value="{{ $review->product?->name ?? '—' }}"
                           class="form-control" disabled>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Rating</label>
                    <div class="d-flex gap-2 pt-2">
                        @for($i = 1; $i <= 5; $i++)
                            <div class="form-check">
                                <input class="form-check-input" type="radio"
                                       name="rating" id="rating{{ $i }}"
                                       value="{{ $i }}" @checked(old('rating', $review->rating) == $i)>
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
                              maxlength="2000">{{ old('comment', $review->comment) }}</textarea>
                </div>
            </div>

            <div class="mt-4 d-flex gap-2">
                <button class="btn btn-warning"><i class="bi bi-save"></i> Update</button>
                <a href="{{ route('reviews.index') }}" class="btn btn-outline-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>

@endsection