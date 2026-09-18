@extends('layouts.app')

@section('title', 'My Wishlist')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-3">
    <h3 class="fw-bold"><i class="bi bi-heart-fill text-danger"></i> My Wishlist</h3>
    <a href="{{ route('wishlist.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-lg"></i> Add Product
    </a>
</div>

@if($wishlists->isEmpty())
    <div class="alert alert-info text-center">
        <i class="bi bi-heart fs-1 d-block mb-2"></i>
        Your wishlist is empty.
    </div>
@else
    <div class="row g-3">
        @foreach($wishlists as $wish)
            <div class="col-md-3 col-sm-6">
                <div class="card h-100">
                    @if($wish->product?->image)
                        <img src="{{ $wish->product->image_url }}" class="card-img-top"
                             style="height:180px; object-fit:cover;">
                    @else
                        <div class="bg-light d-flex align-items-center justify-content-center"
                             style="height:180px;">
                            <i class="bi bi-image fs-1 text-muted"></i>
                        </div>
                    @endif

                    <div class="card-body">
                        <h6 class="card-title text-truncate">{{ $wish->product?->name ?? '—' }}</h6>
                        <p class="text-success fw-bold">
                            {{ number_format($wish->product?->final_price ?? 0, 2) }} EGP
                        </p>
                        <p class="small text-muted mb-0">
                            Added: {{ $wish->created_at?->format('Y-m-d') }}
                        </p>
                    </div>

                    <div class="card-footer d-flex justify-content-between">
                        <a href="{{ route('wishlist.show', $wish) }}"
                           class="btn btn-sm btn-outline-info"><i class="bi bi-eye"></i></a>

                        @can('delete', $wish)
                            <form action="{{ route('wishlist.destroy', $wish) }}" method="POST"
                                  class="d-inline" onsubmit="return confirm('Remove?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger">
                                    <i class="bi bi-heartbreak"></i>
                                </button>
                            </form>
                        @endcan
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endif

@endsection