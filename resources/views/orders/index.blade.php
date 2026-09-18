@extends('layouts.app')

@section('title', 'Orders')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-3">
    <h3 class="fw-bold"><i class="bi bi-receipt text-primary"></i> Orders</h3>
    <a href="{{ route('orders.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-lg"></i> New Order
    </a>
</div>

<div class="card mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('orders.index') }}" class="row g-2">
            <div class="col-md-4">
                <input type="text" name="search" class="form-control"
                       placeholder="Search by name or id..."
                       value="{{ request('search') }}">
            </div>
            <div class="col-md-3">
                <select name="status" class="form-select">
                    <option value="">-- Status --</option>
                    <option value="pending"  @selected(request('status') === 'pending')>Pending</option>
                    <option value="success"  @selected(request('status') === 'success')>Success</option>
                    <option value="failed"   @selected(request('status') === 'failed')>Failed</option>
                </select>
            </div>
            <div class="col-md-3">
                <select name="payment_status" class="form-select">
                    <option value="">-- Payment --</option>
                    <option value="pending"  @selected(request('payment_status') === 'pending')>Pending</option>
                    <option value="success"  @selected(request('payment_status') === 'success')>Success</option>
                    <option value="failed"   @selected(request('payment_status') === 'failed')>Failed</option>
                    <option value="refunded" @selected(request('payment_status') === 'refunded')>Refunded</option>
                </select>
            </div>
            <div class="col-md-2 d-flex gap-2">
                <button class="btn btn-primary w-100"><i class="bi bi-search"></i></button>
                <a href="{{ route('orders.index') }}" class="btn btn-outline-secondary">
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
                    <th>Status</th>
                    <th>Payment</th>
                    <th>Grand Total</th>
                    <th>Date</th>
                    <th class="text-center">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($orders as $order)
                    <tr>
                        <td>#{{ $order->id }}</td>
                        <td>{{ $order->name }}</td>
                        <td>{{ $order->user?->name ?? '—' }}</td>
                        <td>
                            <span class="badge bg-{{ $order->status === 'success' ? 'success' : ($order->status === 'failed' ? 'danger' : 'warning') }}">
                                {{ ucfirst($order->status) }}
                            </span>
                        </td>
                        <td>
                            <span class="badge bg-{{ $order->payment_status === 'success' ? 'success' : 'secondary' }}">
                                {{ ucfirst($order->payment_status) }}
                            </span>
                        </td>
                        <td class="fw-bold text-success">
                            {{ number_format($order->grand_total, 2) }}
                        </td>
                        <td>{{ $order->created_at?->format('Y-m-d') }}</td>
                        <td class="text-center">
                            <a href="{{ route('orders.show', $order->id) }}"
                               class="btn btn-sm btn-outline-info">
                                <i class="bi bi-eye"></i>
                            </a>
                            <a href="{{ route('orders.edit', $order->id) }}"
                               class="btn btn-sm btn-outline-warning">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <form action="{{ route('orders.destroy', $order->id) }}"
                                  method="POST" class="d-inline"
                                  onsubmit="return confirm('Delete this order?')">
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
                            No orders found.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection