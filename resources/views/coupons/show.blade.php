@extends('layouts.app')

@section('title', 'Coupon Details')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-3">
    <h3 class="fw-bold">
        <i class="bi bi-ticket-perforated text-info"></i> Coupon #{{ $coupon->id }}
    </h3>
    <div>
        <a href="{{ route('coupons.edit', $coupon->id) }}" class="btn btn-warning">
            <i class="bi bi-pencil"></i> Edit
        </a>
        <a href="{{ route('coupons.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left"></i> Back
        </a>
    </div>
</div>

<div class="card">
    <div class="card-body">

        <div class="row g-3">
            <div class="col-md-6">
                <div class="border rounded p-3 bg-light">
                    <small class="text-muted d-block">Name</small>
                    <strong>{{ $coupon->name }}</strong>
                </div>
            </div>

            <div class="col-md-6">
                <div class="border rounded p-3 bg-light">
                    <small class="text-muted d-block">Code</small>
                    <span class="badge bg-dark fs-6">{{ $coupon->code }}</span>
                </div>
            </div>

            <div class="col-md-3">
                <div class="border rounded p-3 bg-light">
                    <small class="text-muted d-block">Type</small>
                    <strong>{{ ucfirst($coupon->type) }}</strong>
                </div>
            </div>

            <div class="col-md-3">
                <div class="border rounded p-3 bg-light">
                    <small class="text-muted d-block">Value</small>
                    <strong class="text-success">
                        {{ number_format($coupon->value, 2) }}
                        {{ $coupon->type === 'percentage' ? '%' : 'EGP' }}
                    </strong>
                </div>
            </div>

            <div class="col-md-3">
                <div class="border rounded p-3 bg-light">
                    <small class="text-muted d-block">Usage Limit</small>
                    <strong>{{ $coupon->usage_limit }}</strong>
                </div>
            </div>

            <div class="col-md-3">
                <div class="border rounded p-3 bg-light">
                    <small class="text-muted d-block">Status</small>
                    @if($coupon->isValid())
                        <span class="badge bg-success">Active</span>
                    @else
                        <span class="badge bg-danger">Inactive / Expired</span>
                    @endif
                </div>
            </div>

            <div class="col-md-6">
                <div class="border rounded p-3 bg-light">
                    <small class="text-muted d-block">Expire Date</small>
                    <strong>{{ $coupon->expire_date?->format('Y-m-d H:i') ?? '—' }}</strong>
                </div>
            </div>

            <div class="col-md-6">
                <div class="border rounded p-3 bg-light">
                    <small class="text-muted d-block">Created At</small>
                    <strong>{{ $coupon->created_at?->format('Y-m-d H:i') }}</strong>
                </div>
            </div>
        </div>

        <hr class="my-4">

        <form action="{{ route('coupons.destroy', $coupon->id) }}"
              method="POST" onsubmit="return confirm('Delete this coupon?')">
            @csrf @method('DELETE')
            <button class="btn btn-outline-danger">
                <i class="bi bi-trash"></i> Delete
            </button>
        </form>

    </div>
</div>

@endsection