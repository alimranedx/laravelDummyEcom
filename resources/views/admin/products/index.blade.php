@extends('admin.layout')

@section('content')
    <div class="container-fluid py-4 animate-fade-in">
        <div class="d-flex justify-content-between align-items-center mb-5">
            <div>
                <h1 class="h3 fw-bold text-dark mb-1">Product Catalog</h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 small">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}" class="text-decoration-none text-muted">Dashboard</a></li>
                        <li class="breadcrumb-item active text-primary fw-medium" aria-current="page">Products</li>
                    </ol>
                </nav>
            </div>
            <a href="{{ route('admin.products.create') }}" class="btn btn-primary d-inline-flex align-items-center px-4 py-2 rounded-pill shadow-sm">
                <i class="bi bi-plus-lg me-2"></i><span class="fw-semibold">Add Product</span>
            </a>
        </div>

        <!-- Quick Stats -->
        <div class="row g-4 mb-5">
            <div class="col-md-4">
                <div class="card border-0 shadow-sm rounded-4 p-3 bg-white">
                    <div class="d-flex align-items-center">
                        <div class="bg-warning bg-opacity-10 p-3 rounded-4 me-3">
                            <i class="bi bi-box-seam fs-3 text-warning"></i>
                        </div>
                        <div>
                            <span class="text-muted small d-block">Total Products</span>
                            <span class="h4 fw-bold mb-0 text-dark">{{ $products->total() }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card border-0 shadow-sm rounded-4 overflow-hidden bg-white">
            <div class="card-header bg-white border-0 py-4 px-4 d-flex justify-content-between align-items-center">
                <h5 class="mb-0 fw-bold text-dark">All Products</h5>
                <div class="position-relative" style="max-width:300px">
                    <i class="bi bi-search position-absolute top-50 start-0 translate-middle-y ms-3 text-muted"></i>
                    <input type="text" class="form-control rounded-pill ps-5 border-light bg-light" placeholder="Search products...">
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light text-muted small text-uppercase">
                        <tr>
                            <th class="px-4 py-3 border-0">#ID</th>
                            <th class="px-4 py-3 border-0">Product</th>
                            <th class="px-4 py-3 border-0">Category</th>
                            <th class="px-4 py-3 border-0">Price</th>
                            <th class="px-4 py-3 border-0">Stock</th>
                            <th class="px-4 py-3 border-0">Added</th>
                            <th class="px-4 py-3 border-0 text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="border-top-0">
                        @forelse($products as $product)
                            <tr class="transition-row">
                                <td class="px-4 py-4 text-muted small">#{{ $product->id }}</td>
                                <td class="px-4 py-4">
                                    <div class="d-flex align-items-center">
                                        <div class="rounded-3 overflow-hidden shadow-sm border me-3" style="width:48px;height:48px;flex-shrink:0">
                                            <img src="{{ $product->image_url }}" alt="{{ $product->name }}" style="width:100%;height:100%;object-fit:cover">
                                        </div>
                                        <span class="fw-bold text-dark">{{ $product->name }}</span>
                                    </div>
                                </td>
                                <td class="px-4 py-4">
                                    <span class="badge bg-info bg-opacity-10 text-info rounded-pill px-3 py-2 fw-normal small">{{ $product->category->name ?? 'N/A' }}</span>
                                </td>
                                <td class="px-4 py-4 fw-bold text-dark">${{ number_format($product->price, 2) }}</td>
                                <td class="px-4 py-4">
                                    <span class="badge rounded-pill {{ $product->stock > 10 ? 'bg-success' : ($product->stock > 0 ? 'bg-warning text-dark' : 'bg-danger') }} px-3 py-2 fw-normal small">
                                        {{ $product->stock }} units
                                    </span>
                                </td>
                                <td class="px-4 py-4 text-muted small">{{ $product->created_at->format('M d, Y') }}</td>
                                <td class="px-4 py-4 text-end">
                                    <div class="btn-group gap-2">
                                        <a href="{{ route('admin.products.edit', $product) }}" class="btn btn-sm btn-light p-2 rounded-3 border-0 shadow-sm" title="Edit">
                                            <i class="bi bi-pencil-square text-primary fs-5"></i>
                                        </a>
                                        <form action="{{ route('admin.products.destroy', $product) }}" method="POST" class="d-inline"
                                            onsubmit="return confirm('Delete this product?')">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-light p-2 rounded-3 border-0 shadow-sm">
                                                <i class="bi bi-trash text-danger fs-5"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="7" class="text-center py-5 text-muted">
                                <i class="bi bi-box display-1 opacity-25 d-block mb-3"></i>
                                <strong>No products found</strong>
                            </td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($products->hasPages())
                <div class="card-footer bg-white py-4 px-4 border-top">{{ $products->links() }}</div>
            @endif
        </div>
    </div>
    @push('styles')
    <style>
        .transition-row { transition: background-color 0.2s; }
        .transition-row:hover { background-color: rgba(248,249,250,0.5); }
        .page-link { border:none; padding:.5rem .85rem; margin:0 2px; border-radius:8px !important; }
        .page-item.active .page-link { background-color:#4f46e5; }
    </style>
    @endpush
@endsection