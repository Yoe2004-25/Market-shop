@extends('layouts.app')

@section('title', 'Review Details')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-3">
    <h3 class="fw-bold"><i class="bi bi-star-fill text-warning"></i> Review #{{ $review->id }}</h3>
    <div>
        @can('update', $review)
            <a href="{{ route('reviews.edit', $review) }}" class="btn btn-warning">
                <i class="bi bi-pencil"></i> Edit
            </a>
        @endcan
        <a href="{{ route('reviews.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left"></i> Back
        </a>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <div class="row g-3">
            <div class="col-md-6">
                <div class="border rounded p-3 bg-light">
                    <small class="text-muted">Product</small>
                    <strong class="d-block">{{ $review->product?->name ?? '—' }}</strong>
                </div>
            </div>

            <div class="col-md-6">
                <div class="border rounded p-3 bg-light">
                    <small class="text-muted">User</small>
                    <strong class="d-block">{{ $review->user?->name ?? '—' }}</strong>
                </div>
            </div>

            <div class="col-12">
                <div class="border rounded p-3 bg-light">
                    <small class="text-muted">Rating</small>
                    @for($i = 1; $i <= 5; $i++)
                        <i class="bi bi-star{{ $i <= $review->rating ? '-fill text-warning' : ' text-muted' }} fs-4"></i>
                    @endfor
                    <span class="ms-2 fs-5">({{ $review->rating }}/5)</span>
                </div>
            </div>

            <div class="col-12">
                <div class="border rounded p-3 bg-light">
                    <small class="text-muted">Comment</small>
                    <p class="mb-0">{{ $review->comment ?? '—' }}</p>
                </div>
            </div>

            <div class="col-md-6">
                <div class="border rounded p-3 bg-light">
                    <small class="text-muted">Created</small>
                    <strong class="d-block">{{ $review->created_at?->format('Y-m-d H:i') }}</strong>
                </div>
            </div>

            <div class="col-md-6">
                <div class="border rounded p-3 bg-light">
                    <small class="text-muted">Updated</small>
                    <strong class="d-block">{{ $review->updated_at?->format('Y-m-d H:i') }}</strong>
                </div>
            </div>
        </div>

        @can('delete', $review)
            <hr class="my-4">
            <form action="{{ route('reviews.destroy', $review) }}" method="POST"
                  onsubmit="return confirm('Delete this review?')">
                @csrf @method('DELETE')
                <button class="btn btn-outline-danger">
                    <i class="bi bi-trash"></i> Delete
                </button>
            </form>
        @endcan
    </div>
</div>

@endsection