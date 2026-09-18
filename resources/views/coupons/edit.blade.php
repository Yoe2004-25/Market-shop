@extends('layouts.app')

@section('title', 'Edit Coupon')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-3">
    <h3 class="fw-bold">
        <i class="bi bi-pencil-square text-warning"></i> Edit Coupon #{{ $coupon->id }}
    </h3>
    <a href="{{ route('coupons.index') }}" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left"></i> Back
    </a>
</div>

<div class="card">
    <div class="card-body">

        @if ($errors->any())
            <div class="alert alert-danger">
                <strong>Please fix the errors:</strong>
                <ul class="mb-0 mt-2">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('coupons.update', $coupon->id) }}" method="POST">
            @csrf @method('PUT')

            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Name <span class="text-danger">*</span></label>
                    <input type="text" name="name"
                           class="form-control @error('name') is-invalid @enderror"
                           value="{{ old('name', $coupon->name) }}" required>
                    @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label">Code <span class="text-danger">*</span></label>
                    <input type="text" name="code"
                           class="form-control @error('code') is-invalid @enderror"
                           value="{{ old('code', $coupon->code) }}" required>
                    @error('code')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="col-md-4">
                    <label class="form-label">Type <span class="text-danger">*</span></label>
                    <select name="type" class="form-select @error('type') is-invalid @enderror">
                        <option value="fixed"      @selected(old('type', $coupon->type) === 'fixed')>Fixed Amount</option>
                        <option value="percentage" @selected(old('type', $coupon->type) === 'percentage')>Percentage</option>
                    </select>
                    @error('type')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="col-md-4">
                    <label class="form-label">Value <span class="text-danger">*</span></label>
                    <input type="number" step="0.01" name="value"
                           class="form-control @error('value') is-invalid @enderror"
                           value="{{ old('value', $coupon->value) }}" required>
                    @error('value')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="col-md-4">
                    <label class="form-label">Usage Limit</label>
                    <input type="number" name="usage_limit"
                           class="form-control @error('usage_limit') is-invalid @enderror"
                           value="{{ old('usage_limit', $coupon->usage_limit) }}" min="1">
                    @error('usage_limit')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label">Expire Date</label>
                    <input type="datetime-local" name="expire_date"
                           class="form-control @error('expire_date') is-invalid @enderror"
                           value="{{ old('expire_date', $coupon->expire_date?->format('Y-m-d\TH:i')) }}">
                    @error('expire_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-select">
                        <option value="active"    @selected(old('status', $coupon->status) === 'active')>Active</option>
                        <option value="nonactive" @selected(old('status', $coupon->status) === 'nonactive')>Inactive</option>
                    </select>
                </div>
            </div>

            <div class="mt-4 d-flex gap-2">
                <button type="submit" class="btn btn-warning">
                    <i class="bi bi-save"></i> Update
                </button>
                <a href="{{ route('coupons.index') }}" class="btn btn-outline-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>

@endsection