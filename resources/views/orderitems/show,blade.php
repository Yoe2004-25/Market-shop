@extends('layouts.app')

@section('title', 'Order Item Details')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-3">
      <h3 class="fw-bold"><i class="bi bi-bag-check text-info"></i> Item #{{ $item->id }}</h3>
      <div>
            <a href="{{ route('orderitems.edit', $item->id) }}" class="btn btn-warning">
                  <i class="bi bi-pencil"></i> Edit
            </a>
            <a href="{{ route('orderitems.index') }}" class="btn btn-outline-secondary">
                  <i class="bi bi-arrow-left"></i> Back
            </a>
      </div>
</div>

<div class="card">
      <div class="card-body">
            <div class="row g-3">
                  <div class="col-md-6">
                        <div class="border rounded p-3 bg-light">
                              <small class="text-muted">Order ID</small>
                              <strong class="d-block">#{{ $item->order_id }}</strong>
                        </div>
                  </div>

                  <div class="col-md-6">
                        <div class="border rounded p-3 bg-light">
                              <small class="text-muted">Product</small>
                              <strong class="d-block">{{ $item->product?->name ?? '—' }}</strong>
                        </div>
                  </div>

                  <div class="col-md-3">
                        <div class="border rounded p-3 bg-light">
                              <small class="text-muted">Quantity</small>
                              <span class="badge bg-info fs-6">{{ $item->quantity }}</span>
                        </div>
                  </div>

                  <div class="col-md-3">
                        <div class="border rounded p-3 bg-light">
                              <small class="text-muted">Price</small>
                              <strong class="d-block">{{ number_format($item->price, 2) }} EGP</strong>
                        </div>
                  </div>

                  <div class="col-md-3">
                        <div class="border rounded p-3 bg-light">
                              <small class="text-muted">Subtotal</small>
                              <strong class="text-success d-block">
                                    {{ number_format($item->subtotal, 2) }} EGP
                              </strong>
                        </div>
                  </div>

                  <div class="col-md-3">
                        <div class="border rounded p-3 bg-light">
                              <small class="text-muted">Created</small>
                              <strong class="d-block">{{ $item->created_at?->format('Y-m-d H:i') }}</strong>
                        </div>
                  </div>

                  <div class="col-12">
                        <div class="border rounded p-3 bg-light">
                              <small class="text-muted">Details</small>
                              <p class="mb-0">{{ $item->details }}</p>
                        </div>
                  </div>
            </div>

            <hr class="my-4">
            <form action="{{ route('orderitems.destroy', $item->id) }}" method="POST"
                  onsubmit="return confirm('Delete this item?')">
                  @csrf @method('DELETE')
                  <button class="btn btn-outline-danger">
                        <i class="bi bi-trash"></i> Delete
                  </button>
            </form>
      </div>
</div>

@endsection