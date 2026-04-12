@extends('admin.layout')

@section('content')
    <div class="container-fluid py-4 animate-fade-in">
        <!-- Page Header -->
        <div class="d-flex justify-content-between align-items-center mb-5">
            <div>
                <h1 class="h3 fw-bold text-dark mb-1">Brand Management</h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 small">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}" class="text-decoration-none text-muted">Dashboard</a></li>
                        <li class="breadcrumb-item active text-primary fw-medium" aria-current="page">Brands</li>
                    </ol>
                </nav>
            </div>
            <a href="{{ route('admin.brands.create') }}" class="btn btn-primary d-inline-flex align-items-center px-4 py-2 rounded-pill shadow-sm transition">
                <i class="bi bi-plus-lg me-2"></i>
                <span class="fw-semibold">Add New Brand</span>
            </a>
        </div>

        <!-- Quick Stats -->
        <div class="row g-4 mb-5">
            <div class="col-md-4">
                <div class="card border-0 shadow-sm rounded-4 p-3 bg-white">
                    <div class="d-flex align-items-center">
                        <div class="bg-primary bg-opacity-10 p-3 rounded-4 me-3">
                            <i class="bi bi-tags fs-3 text-primary"></i>
                        </div>
                        <div>
                            <span class="text-muted small d-block">Identified Brands</span>
                            <span class="h4 fw-bold mb-0 text-dark">{{ $brands->total() }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content Card -->
        <div class="card border-0 shadow-sm rounded-4 overflow-hidden bg-white">
            <div class="card-header bg-white border-0 py-4 px-4 d-flex flex-column flex-md-row justify-content-between align-items-center gap-3">
                <h5 class="mb-0 fw-bold text-dark">Brand Portfolio</h5>
                <form method="GET" action="{{ route('admin.brands.index') }}" class="d-flex gap-2 w-100" style="max-width: 400px;">
                    <div class="position-relative flex-grow-1">
                        <i class="bi bi-search position-absolute top-50 start-0 translate-middle-y ms-3 text-muted"></i>
                        <input type="text" name="q" value="{{ request('q') }}" class="form-control rounded-pill ps-5 border-light bg-light w-100" placeholder="Search brands...">
                    </div>
                    <button type="submit" class="btn btn-primary rounded-pill px-4 fw-medium shadow-sm">Search</button>
                    @if(request()->filled('q'))
                        <a href="{{ route('admin.brands.index') }}" class="btn btn-light rounded-pill border px-3">Clear</a>
                    @endif
                </form>
            </div>
            
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light text-muted small text-uppercase">
                        <tr>
                            <th class="px-4 py-3 border-0">#ID</th>
                            <th class="px-4 py-3 border-0">Brand Profile</th>
                            <th class="px-4 py-3 border-0">Slug Path</th>
                            <th class="px-4 py-3 border-0">Stats</th>
                            <th class="px-4 py-3 border-0 text-end">Management</th>
                        </tr>
                    </thead>
                    <tbody class="border-top-0">
                        @forelse($brands as $brand)
                            <tr class="transition-row">
                                <td class="px-4 py-4 text-muted small">#{{ $brand->id }}</td>
                                <td class="px-4 py-4">
                                    <div class="d-flex align-items-center">
                                        <div class="brand-logo-wrapper me-3 p-1 bg-white border rounded shadow-sm">
                                            <img src="{{ $brand->logo_url }}" alt="{{ $brand->name }}"
                                                style="width: 45px; height: 45px; object-fit: contain;">
                                        </div>
                                        <div>
                                            <div class="fw-bold text-dark mb-0 fs-6">{{ $brand->name }}</div>
                                            <div class="text-muted smaller">Authorized Partner</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-4 py-4">
                                    <code class="bg-light text-primary px-2 py-1 rounded small">/{{ $brand->slug }}</code>
                                </td>
                                <td class="px-4 py-4">
                                    <div class="d-flex flex-column">
                                        <span class="text-dark small"><i class="bi bi-grid-3x3-gap me-1 text-muted"></i> {{ $brand->categories_count }} Categories</span>
                                        <span class="text-dark small"><i class="bi bi-box-seam me-1 text-muted"></i> {{ $brand->products_count }} Products</span>
                                    </div>
                                </td>
                                <td class="px-4 py-4 text-end">
                                    <div class="btn-group gap-2">
                                        <a href="{{ route('admin.brands.edit', $brand) }}"
                                            class="btn btn-sm btn-light p-2 rounded-3 border-0 shadow-sm" title="Edit Brand">
                                            <i class="bi bi-pencil-square text-primary fs-5"></i>
                                        </a>
                                        <form action="{{ route('admin.brands.destroy', $brand) }}" method="POST"
                                            class="d-inline"
                                            onsubmit="return confirm('Are you sure you want to remove this brand and all associated data?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-light p-2 rounded-3 border-0 shadow-sm" title="Remove Brand">
                                                <i class="bi bi-trash text-danger fs-5"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-5">
                                    <div class="empty-state py-5 text-muted">
                                        <i class="bi bi-tag display-1 opacity-25 mb-4"></i>
                                        <h4 class="fw-bold">No brands found</h4>
                                        <p class="small">Start building your portfolio by adding a new brand.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            {{-- pagination part start here ======================================= --}}
            @php
                $perPageOptions = [10, 20, 50, 100];
                $perPageQuery = 'per_page';
                $perPageQueryName = 'per_page';
            @endphp
            <div class="card-footer bg-white py-3 px-4 border-top">
                <div class="row align-items-center m-0">
                    <!-- Left: Showing X to Y -->
                    @include('common.pagination.pagination_data_show', ['data' => $brands])

                    <!-- Center: Items per page -->
                    <div class="col-12 col-md-4 d-flex justify-content-center mb-3 mb-md-0 px-0">
                        <form method="GET" action="{{ $route ?? url()->current() }}"
                            class="d-flex align-items-center m-0">
                            @foreach (request()->except($perPageQueryName ?? 'per_page') as $key => $value)
                                @if (is_array($value))
                                    @foreach ($value as $v)
                                        <input type="hidden" name="{{ $key }}[]" value="{{ $v }}">
                                    @endforeach
                                @else
                                    <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                                @endif
                            @endforeach
                            <label class="text-muted small me-2 mb-0 text-nowrap fw-medium">Items per
                                page:</label>
                            <select name="{{ $perPageQueryName ?? 'per_page' }}"
                                class="form-select form-select-sm border-light bg-light rounded-pill fw-medium cursor-pointer"
                                onchange="this.form.submit()" style="width: 80px; min-height: 38px;">
                                @foreach ($perPageOptions ?? [5, 15, 30, 50] as $option)
                                    <option value="{{ $option }}"
                                        {{ request($perPageQueryName ?? 'per_page', 10) == $option ? 'selected' : '' }}>
                                        {{ $option }}</option>
                                @endforeach
                            </select>
                        </form>
                    </div>

                    <!-- Right: Pagination Links -->
                    @include('common.pagination.common_pagination', ['data' => $brands])
                </div>
            </div>
            {{-- pagination part end here ======================================= --}}
        </div>
    </div>

    @push('styles')
    <style>
        .smaller { font-size: 0.75rem; }
        .transition-row { transition: background-color 0.2s; }
        .transition-row:hover { background-color: rgba(248, 249, 250, 0.5); }
        .btn-light { background: #f8f9fa; }
        .btn-light:hover { background: #e9ecef; }
        
        .pagination { margin-bottom: 0 !important; }
        .page-link { border: none; padding: 0.5rem 0.85rem; margin: 0 2px; border-radius: 8px !important; color: #6c757d; }
        .page-item.active .page-link { background-color: #4f46e5; box-shadow: 0 4px 10px rgba(79, 70, 229, 0.2); }
        
        .pagination-wrapper p.small.text-muted { display: none !important; }
        .pagination-wrapper nav>div.d-sm-flex { justify-content: flex-end !important; }
    </style>
    @endpush
@endsection