@extends('layouts.app')

@section('title', 'Payments')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-3">
    <h3 class="fw-bold"><i class="bi bi-credit-card text-primary"></i> Payments</h3>
    <a href="{{ route('payments.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-lg"></i> New Payment
    </a>
</div>

<div class="card mb-3">
    <div class="card-body">
        <form method="GET" class="row g-2">
            <div class="col-md-4">
                <select name="status" class="form-select">
                    <option value="">-- All Status --</option>
                    @foreach(['pending','completed','failed','refunded'] as $s)
                        <option value="{{ $s }}" @selected(request('status') === $s)>{{ ucfirst($s) }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <button class="btn btn-primary w-100"><i class="bi bi-search"></i> Filter</button>
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
                    <th>Order</th>
                    <th>Method</th>
                    <th>Amount</th>
                    <th>Transaction</th>
                    <th>Status</th>
                    <th>Date</th>
                    <th class="text-center">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($payments as $payment)
                    <tr>
                        <td>#{{ $payment->id }}</td>
                        <td>#{{ $payment->order_id }}</td>
                        <td>
                            <span class="badge bg-{{ $payment->payment_method === 'visa' ? 'info' : 'secondary' }}">
                                {{ strtoupper($payment->payment_method) }}
                            </span>
                        </td>
                        <td>{{ number_format($payment->amount, 2) }}</td>
                        <td><code>{{ $payment->transaction_id }}</code></td>
                        <td>
                            @php
                                $color = [
                                    'pending'   => 'warning',
                                    'completed' => 'success',
                                    'failed'    => 'danger',
                                    'refunded'  => 'secondary',
                                ][$payment->status] ?? 'secondary';
                            @endphp
                            <span class="badge bg-{{ $color }}">{{ ucfirst($payment->status) }}</span>
                        </td>
                        <td>{{ $payment->created_at?->format('Y-m-d') }}</td>
                        <td class="text-center">
                            <a href="{{ route('payments.show', $payment) }}"
                               class="btn btn-sm btn-outline-info"><i class="bi bi-eye"></i></a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center text-muted py-4">No payments.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="mt-3">{{ $payments->links() }}</div>

@endsection