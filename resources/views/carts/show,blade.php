@extends('layouts.app')

@section('title', 'Cart Details')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-3">
      <h3 class="fw-bold"><i class="bi bi-cart text-info"></i> Cart #{{ $cart->id }}</h3>
      <div>
            <a href="{{ route('carts.edit', $cart->id) }}" class="btn btn-warning">
                  <i class="bi bi-pencil"></i> Edit
            </a>
            <a href="{{ route('carts.index') }}" class="btn btn-outline-secondary">
                  <i class="bi bi-arrow-left"></i> Back
            </a>
      </div>
</div>

<div class="row g-3">
      <div class="col-md-8">
            <div class="card">
                  <div class="card-header bg-primary text-white">
                        <i class="bi bi-list-ul"></i> Items
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
                                    @forelse($cart->items as $item)
                                    <tr>
                                          <td>{{ $item->product?->name ?? '—' }}</td>
                                          <td>{{ $item->quantity }}</td>
                                          <td>{{ number_format($item->price, 2) }}</td>
                                          <td>{{ number_format($item->price * $item->quantity, 2) }}</td>