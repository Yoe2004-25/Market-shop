@extends('layouts.app')

@section('title', 'Orders Items')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-3">
    <h3 class="fw-bold"><i class="bi bi-bag-check text-primary"></i> Orders Items</h3>
    <a href="{{ route('orderitems.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-lg"></i> Add Item
    </a>
</div>

{{-- Filters --}}
<div class="card mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('orderitems.index') }}" class="row g-2">
            <div class="col-md-4">
                <input type="text" name="search" class="form-control"
                       placeholder="Search by details, order id, product id..."
                       value="{{ request('search') }}">
            </div>
            <div class="col-md-3">
                <input type="number" step="0.01" name="price" class="form-control"
                       placeholder="Price" value="{{ request('price') }}">
            </div>
            <div class="col-md-3">
                <input type="number" name="quantity" class="form-control"
                       placeholder="Quantity" value="{{ request('quantity') }}">
            </div>
            <div class="col-md-2 d-flex gap-2">
                <button class="btn btn-primary w-100">
                    <i class="bi bi-search"></i>
                </button>
                <a href="{{ route('orderitems.index') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-clockwise"></i>
                </a>
            </div>
        </form>
    </div>
</div>

{{-- Table --}}
<div class="card">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead>
                <tr>
                    <th>#ID</th>
                    <th>Order</th>
                    <th>Product</th>
                    <th>Qty</th>
                    <th>Price</th>
                    <th>Subtotal</th>
                    <th>Details</th>
                    <th class="text-center">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($items as $item)
                    <tr>
                        <td>#{{ $item->id }}</td>
                        <td>#{{ $item->order_id }}</td>
                        <td>{{ $item->product?->name ?? '—' }}</td>
                        <td><span class="badge bg-info">{{ $item->quantity }}</span></td>
                        <td>{{ number_format($item->price, 2) }}</td>
                        <td class="fw-bold text-success">
                            {{ number_format($item->subtotal, 2) }}
                        </td>
                        <td>{{ \Illuminate\Support\Str::limit($item->details, 30) }}</td>
                        <td class="text-center">
                            <a href="{{ route('orderitems.show', $item->id) }}"
                               class="btn btn-sm btn-outline-info">
                                <i class="bi bi-eye"></i>
                            </a>
                            <a href="{{ route('orderitems.edit', $item->id) }}"
                               class="btn btn-sm btn-outline-warning">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <form action="{{ route('orderitems.destroy', $item->id) }}"
                                  method="POST" class="d-inline"
                                  onsubmit="return confirm('Delete this item?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center text-muted py-4">
                            <i class="bi bi-inbox fs-3 d-block mb-2"></i>
                            No orders items found.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection