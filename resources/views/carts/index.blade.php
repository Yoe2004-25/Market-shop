@extends('layouts.app')

@section('title', 'Carts')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-3">
    <h3 class="fw-bold"><i class="bi bi-cart text-primary"></i> Carts</h3>
    <a href="{{ route('carts.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-lg"></i> New Cart
    </a>
</div>

<div class="card mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('carts.index') }}" class="row g-2">
            <div class="col-md-8">
                <input type="text" name="search" class="form-control"
                       placeholder="Search..."
                       value="{{ request('search') }}">
            </div>
            <div class="col-md-4 d-flex gap-2">
                <button class="btn btn-primary w-100"><i class="bi bi-search"></i></button>
                <a href="{{ route('carts.index') }}" class="btn btn-outline-secondary">
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
                    <th>Name</th>
                    <th>User</th>
                    <th>Items</th>
                    <th>Total</th>
                    <th>Created</th>
                    <th class="text-center">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($carts as $cart)
                    <tr>
                        <td>#{{ $cart->id }}</td>
                        <td>{{ $cart->name }}</td>
                        <td>{{ $cart->user?->name ?? '—' }}</td>
                        <td>
                            <span class="badge bg-info">
                                {{ $cart->items->count() }}
                            </span>
                        </td>
                        <td class="fw-bold text-success">
                            {{ number_format($cart->total, 2) }} EGP
                        </td>
                        <td>{{ $cart->created_at?->format('Y-m-d') }}</td>
                        <td class="text-center">
                            <a href="{{ route('carts.show', $cart->id) }}"
                               class="btn btn-sm btn-outline-info">
                                <i class="bi bi-eye"></i>
                            </a>
                            <a href="{{ route('carts.edit', $cart->id) }}"
                               class="btn btn-sm btn-outline-warning">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <form action="{{ route('carts.destroy', $cart->id) }}"
                                  method="POST" class="d-inline"
                                  onsubmit="return confirm('Delete this cart?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center text-muted py-4">
                            <i class="bi bi-inbox fs-3 d-block mb-2"></i>
                            No carts found.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection