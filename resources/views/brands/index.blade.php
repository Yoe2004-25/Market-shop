@extends('layouts.app')

@section('title', 'Brands')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-3">
    <h3 class="fw-bold"><i class="bi bi-tags text-primary"></i> Brands</h3>
    <a href="{{ route('brands.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-lg"></i> Add Brand
    </a>
</div>

<div class="card mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('brands.index') }}" class="row g-2">
            <div class="col-md-10">
                <input type="text" name="search" class="form-control"
                       placeholder="Search by brand name..."
                       value="{{ request('search') }}">
            </div>
            <div class="col-md-2 d-flex gap-2">
                <button class="btn btn-primary w-100"><i class="bi bi-search"></i></button>
                <a href="{{ route('brands.index') }}" class="btn btn-outline-secondary">
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
                    <th>Logo</th>
                    <th>Name</th>
                    <th>Created At</th>
                    <th class="text-center">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($brands as $brand)
                    <tr>
                        <td>#{{ $brand->id }}</td>
                        <td>
                            @if($brand->logo)
                                <img src="{{ asset('storage/' . $brand->logo) }}"
                                     alt="{{ $brand->name }}"
                                     style="height:40px; width:40px; object-fit:contain;">
                            @else
                                <span class="text-muted">—</span>
                            @endif
                        </td>
                        <td>{{ $brand->name }}</td>
                        <td>{{ $brand->created_at?->format('Y-m-d') }}</td>
                        <td class="text-center">
                            <a href="{{ route('brands.show', $brand->id) }}"
                               class="btn btn-sm btn-outline-info btn-action">
                                <i class="bi bi-eye"></i>
                            </a>
                            <a href="{{ route('brands.edit', $brand->id) }}"
                               class="btn btn-sm btn-outline-warning btn-action">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <form action="{{ route('brands.destroy', $brand->id) }}"
                                  method="POST" class="d-inline"
                                  onsubmit="return confirm('Delete this brand?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger btn-action">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center text-muted py-4">
                            <i class="bi bi-inbox fs-3 d-block mb-2"></i>
                            No brands found.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection