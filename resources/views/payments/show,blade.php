@extends('layouts.app')

@section('title', 'Payment Details')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-3">
      <h3 class="fw-bold"><i class="bi bi-credit-card text-info"></i> Payment #{{ $payment->id }}</h3>
      <a href="{{ route('payments.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left"></i> Back
      </a>
</div>

<div class="card">
      <div class="card-body">
            <div class="row g-3">
                  <div class="col-md-6">
                        <div class="border rounded p-3 bg-light">
                              <small class="text-muted">Order</small>
                              <strong class="d-block">#{{ $payment->order_id }} — {{ $payment->order?->name }}</strong>
                        </div>
                  </div>

                  <div class="col-md-6">
                        <div class="border rounded p-3 bg-light">
                              <small class="text-muted">Customer</small>
                              <strong class="d-block">{{ $payment->order?->user?->name ?? '—' }}</strong>
                        </div>
                  </div>

                  <div class="col-md-6">
                        <div class="border rounded p-3 bg-light">
                              <small class="text-muted">Method</small>
                              <strong class="d-block">{{ strtoupper($payment->payment_method) }}</strong>
                        </div>
                  </div>

                  <div class="col-md-6">
                        <div class="border rounded p-3 bg-light">
                              <small class="text-muted">Status</small>
                              @php
                              $color = [
                              'pending' => 'warning',
                              'completed' => 'success',
                              'failed' => 'danger',
                              'refunded' => 'secondary',
                              ][$payment->status] ?? 'secondary';
                              @endphp
                              <span class="badge bg-{{ $color }}">{{ ucfirst($payment->status) }}</span>
                        </div>
                  </div>

                  <div class="col-md-6">
                        <div class="border rounded p-3 bg-light">
                              <small class="text-muted">Amount</small>
                              <strong class="text-success d-block">{{ number_format($payment->amount, 2) }} EGP</strong>
                        </div>
                  </div>

                  <div class="col-md-6">
                        <div class="border rounded p-3 bg-light">
                              <small class="text-muted">Transaction ID</small>
                              <code>{{ $payment->transaction_id }}</code>
                        </div>
                  </div>
            </div>

            <hr class="my-4">

            <div class="d-flex flex-wrap gap-2">
                  @if($payment->payment_method === 'cash' && $payment->status === 'pending')
                  <form action="{{ route('payments.confirm-cash', $payment) }}" method="POST" class="d-inline">
                        @csrf
                        <button class="btn btn-success"><i class="bi bi-check2-circle"></i> Confirm Cash
                              Received</button>
                  </form>
                  @endif


                  @if($payment->status === 'completed')
                  <form action="{{ route('payments.refund', $payment) }}" method="POST" class="d-inline"
                        onsubmit="return confirm('Refund this payment?')">
                        @csrf
                        <button class="btn btn-warning"><i class="bi bi-arrow-return-left"></i> Refund</button>
                  </form>
                  @endif

                  {{-- Delete --}}
                  <form action="{{ route('payments.destroy', $payment) }}" method="POST" class="d-inline"
                        onsubmit="return confirm('Delete this payment?')">
                        @csrf @method('DELETE')
                        <button class="btn btn-outline-danger"><i class="bi bi-trash"></i> Delete</button>
                  </form>
            </div>
      </div>
</div>

@endsection