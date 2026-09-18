@extends('layouts.app')

@section('title', 'Order Details')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-3">
    <h3 class="fw-bold"><i class="bi bi-receipt text-info"></i> Order #{{ $order->id }}</h3>
    <div>
        <a href="{{ route('orders.edit', $order->id) }}" class="btn btn-warning">
            <i class="bi bi-pencil"></i> Edit
        </a>
        <a href="{{ route('orders.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left"></i> Back
        </a>
    </div>
</div>

<div class="row g-3">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <i class="bi bi-list-ul"></i> Order Items
            </div>
            <div class="table-responsive">
                <table class="table mb-0">
                    <thead>
                        <tr>
                            <th>Product</th>
                            <th>Qty</th>
                            <th>Price</th>
                            <th>Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($order->items as $item)
                            <tr>
                                <td>{{ $item->product?->name ?? '—' }}</td>
                                <td>{{ $item->quantity }}</td>
                                <td>{{ number_format($item->price, 2) }}</td>
                                <td>{{ number_format($item->subtotal, 2) }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="4" class="text-center text-muted">No items.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card">
            <div class="card-header bg-dark text-white">
                <i class="bi bi-info-circle"></i> Summary
            </div>
            <div class="card-body">
                <p><strong>Name:</strong> {{ $order->name }}</p>
                <p><strong>User:</strong> {{ $order->user?->name ?? '—' }}</p>
                <p><strong>Coupon:</strong> {{ $order->coupon?->code ?? '—' }}</p>
                <p>
                    <strong>Status:</strong>
                    <span class="badge bg-info">{{ ucfirst($order->status) }}</span>
                </p>
                <p>
                    <strong>Payment:</strong>
                    <span class="badge bg-secondary">{{ ucfirst($order->payment_status) }}</span>
                </p>

                <hr>

                <p><strong>Total:</strong> {{ number_format($order->total, 2) }}</p>
                <p><strong>Shipping:</strong> {{ number_format($order->shipping_cost, 2) }}</p>
                <p><strong>Tax:</strong> {{ number_format($order->tax, 2) }}</p>
                <p class="text-success fs-5">
                    <strong>Grand Total:</strong> {{ number_format($order->grand_total, 2) }} EGP
                </p>

                @if($order->payment)
                    <hr>
                    <p><strong>Payment Method:</strong> {{ $order->payment->payment_method }}</p>
                    <p><strong>Payment Status:</strong> {{ $order->payment->status }}</p>
                @endif
            </div>
        </div>
    </div>
</div>

<div class="card mt-3">
    <div class="card-body">
        <form action="{{ route('orders.destroy', $order->id) }}"
              method="POST" onsubmit="return confirm('Delete this order?')">
            @csrf @method('DELETE')
            <button class="btn btn-outline-danger">
                <i class="bi bi-trash"></i> Delete Order
            </button>
        </form>
    </div>
</div>

@endsection