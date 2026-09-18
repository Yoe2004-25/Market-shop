@extends('layouts.app')

@section('title', 'Create Coupon')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-3">
    <h3 class="fw-bold"><i class="bi bi-plus-circle text-primary"></i> Create Coupon</h3>
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

        <form action="{{ route('coupons.store') }}" method="POST">
            @csrf

            <div class="row g-3">
                <div class="col-md-6">
                    <label for="name" class="form-label">Name <span class="text-danger">*</span></label>
                    <input type="text" name="name" id="name"
                           class="form-control @error('name') is-invalid @enderror"
                           value="{{ old('name') }}" required>
                    @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="col-md-6">
                    <label for="code" class="form-label">Code <span class="text-danger">*</span></label>
                    <input type="text" name="code" id="code"
                           class="form-control @error('code') is-invalid @enderror"
                           value="{{ old('code') }}"
                           placeholder="SUMMER2025" required>
                    @error('code')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="col-md-4">
                    <label for="type" class="form-label">Type <span class="text-danger">*</span></label>
                    <select name="type" id="type"
                            class="form-select @error('type') is-invalid @enderror" required>
                        <option value="">-- Choose --</option>
                        <option value="fixed"      @selected(old('type') === 'fixed')>Fixed Amount</option>
                        <option value="percentage" @selected(old('type') === 'percentage')>Percentage</option>
                    </select>
                    @error('type')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="col-md-4">
                    <label for="value" class="form-label">Value <span class="text-danger">*</span></label>
                    <input type="number" step="0.01" name="value" id="value"
                           class="form-control @error('value') is-invalid @enderror"
                           value="{{ old('value') }}" min="0.01" required>
                    @error('value')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="col-md-4">
                    <label for="usage_limit" class="form-label">Usage Limit</label>
                    <input type="number" name="usage_limit" id="usage_limit"
                           class="form-control @error('usage_limit') is-invalid @enderror"
                           value="{{ old('usage_limit', 1) }}" min="1">
                    @error('usage_limit')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="col-md-6">
                    <label for="expire_date" class="form-label">Expire Date</label>
                    <input type="datetime-local" name="expire_date" id="expire_date"
                           class="form-control @error('expire_date') is-invalid @enderror"
                           value="{{ old('expire_date') }}">
                    @error('expire_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="col-md-6">
                    <label for="status" class="form-label">Status</label>
                    <select name="status" id="status" class="form-select">
                        <option value="active"    @selected(old('status', 'active') === 'active')>Active</option>
                        <option value="nonactive" @selected(old('status') === 'nonactive')>Inactive</option>
                    </select>
                </div>
            </div>

            <div class="mt-4 d-flex gap-2">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-save"></i> Save
                </button>
                <a href="{{ route('coupons.index') }}" class="btn btn-outline-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>

@endsection