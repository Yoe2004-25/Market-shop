@extends('layouts.app')

@section('title', 'Reviews')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-3">
    <h3 class="fw-bold"><i class="bi bi-star-fill text-warning"></i> Reviews</h3>
    @can('create', App\Models\Reviews::class)
        <a href="{{ route('reviews.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-lg"></i> Add Review
        </a>
    @endcan
</div>

<div class="card mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('reviews.index') }}" class="row g-2">
            <div class="col-md-5">
                <input type="text" name="search" class="form-control"
                       placeholder="Search comments..."
                       value="{{ request('search') }}">
            </div>
            <div class="col-md-3">
                <select name="rating" class="form-select">
                    <option value="">-- Rating --</option>
                    @for($i = 1; $i <= 5; $i++)
                        <option value="{{ $i }}" @selected(request('rating') == $i)>
                            {{ $i }} Star{{ $i > 1 ? 's' : '' }}
                        </option>
                    @endfor
                </select>
            </div>
            <div class="col-md-2">
                <input type="number" name="product_id" class="form-control"
                       placeholder="Product ID" value="{{ request('product_id') }}">
            </div>
            <div class="col-md-2 d-flex gap-2">
                <button class="btn btn-primary w-100"><i class="bi bi-search"></i></button>
                <a href="{{ route('reviews.index') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-clockwise"></i>
                </a>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead>
                <tr>
                    <th>#ID</th>
                    <th>Product</th>
                    <th>User</th>
                    <th>Rating</th>
                    <th>Comment</th>
                    <th>Date</th>
                    <th class="text-center">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($reviews as $review)
                    <tr>
                        <td>#{{ $review->id }}</td>
                        <td>{{ $review->product?->name ?? '—' }}</td>
                        <td>{{ $review->user?->name ?? '—' }}</td>
                        <td>
                            @for($i = 1; $i <= 5; $i++)
                                <i class="bi bi-star{{ $i <= $review->rating ? '-fill text-warning' : ' text-muted' }}"></i>
                            @endfor
                            <small>({{ $review->rating }})</small>
                        </td>
                        <td>{{ \Illuminate\Support\Str::limit($review->comment, 40) }}</td>
                        <td>{{ $review->created_at?->format('Y-m-d') }}</td>
                        <td class="text-center">
                            <a href="{{ route('reviews.show', $review) }}"
                               class="btn btn-sm btn-outline-info"><i class="bi bi-eye"></i></a>

                            @can('update', $review)
                                <a href="{{ route('reviews.edit', $review) }}"
                                   class="btn btn-sm btn-outline-warning"><i class="bi bi-pencil"></i></a>
                            @endcan

                            @can('delete', $review)
                                <form action="{{ route('reviews.destroy', $review) }}"
                                      method="POST" class="d-inline"
                                      onsubmit="return confirm('Delete?')">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            @endcan
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center text-muted py-4">
                            <i class="bi bi-inbox fs-3 d-block mb-2"></i>
                            No reviews found.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection