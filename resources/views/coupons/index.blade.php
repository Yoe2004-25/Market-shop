@extends('layouts.app')

@section('title', 'Coupons')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-3">
    <h3 class="fw-bold">
        <i class="bi bi-ticket-perforated text-primary"></i> Coupons
    </h3>
    <a href="{{ route('coupons.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-lg"></i> Add New Coupon
    </a>
</div>

{{-- Filters --}}
<div class="card mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('coupons.index') }}" class="row g-2">
            <div class="col-md-5">
                <input type="text" name="search" class="form-control"
                       placeholder="Search by name or code..."
                       value="{{ request('search') }}">
            </div>
            <div class="col-md-3">
                <select name="type" class="form-select">
                    <option value="">-- Type --</option>
                    <option value="fixed"      @selected(request('type') === 'fixed')>Fixed</option>
                    <option value="percentage" @selected(request('type') === 'percentage')>Percentage</option>
                </select>
            </div>
            <div class="col-md-2">
                <select name="status" class="form-select">
                    <option value="">-- Status --</option>
                    <option value="active"    @selected(request('status') === 'active')>Active</option>
                    <option value="nonactive" @selected(request('status') === 'nonactive')>Inactive</option>
                </select>
            </div>
            <div class="col-md-2 d-flex gap-2">
                <button class="btn btn-primary w-100"><i class="bi bi-search"></i></button>
                <a href="{{ route('coupons.index') }}" class="btn btn-outline-secondary">
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
                    <th>Name</th>
                    <th>Code</th>
                    <th>Type</th>
                    <th>Value</th>
                    <th>Expire</th>
                    <th>Status</th>
                    <th class="text-center">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($coupons as $coupon)
                    <tr>
                        <td>#{{ $coupon->id }}</td>
                        <td>{{ $coupon->name }}</td>
                        <td><span class="badge bg-dark">{{ $coupon->code }}</span></td>
                        <td>
                            @if($coupon->type === 'fixed')
                                <span class="badge bg-success">Fixed</span>
                            @else
                                <span class="badge bg-info">Percentage</span>
                            @endif
                        </td>
                        <td>
                            {{ number_format($coupon->value, 2) }}
                            {{ $coupon->type === 'percentage' ? '%' : 'EGP' }}
                        </td>
                        <td>
                            {{ $coupon->expire_date?->format('Y-m-d') ?? '—' }}
                        </td>
                        <td>
                            @if($coupon->isValid())
                                <span class="badge bg-success">Active</span>
                            @else
                                <span class="badge bg-danger">Inactive</span>
                            @endif
                        </td>
                        <td class="text-center">
                            <a href="{{ route('coupons.show', $coupon->id) }}"
                               class="btn btn-sm btn-outline-info btn-action">
                                <i class="bi bi-eye"></i>
                            </a>
                            <a href="{{ route('coupons.edit', $coupon->id) }}"
                               class="btn btn-sm btn-outline-warning btn-action">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <form action="{{ route('coupons.destroy', $coupon->id) }}"
                                  method="POST" class="d-inline"
                                  onsubmit="return confirm('Delete this coupon?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger btn-action">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center text-muted py-4">
                            <i class="bi bi-inbox fs-3 d-block mb-2"></i>
                            No coupons found.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection