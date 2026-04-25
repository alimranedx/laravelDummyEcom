@extends('admin.layout')

@section('content')
    <div class="container-fluid py-4 animate-fade-in">
        <div class="d-flex justify-content-between align-items-center mb-5">
            <div>
                <h1 class="h3 fw-bold text-dark mb-1">Category Management</h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 small">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}" class="text-decoration-none text-muted">Dashboard</a></li>
                        <li class="breadcrumb-item active text-primary fw-medium" aria-current="page">Categories</li>
                    </ol>
                </nav>
            </div>
            <a href="{{ route('admin.categories.create') }}" class="btn btn-primary d-inline-flex align-items-center px-4 py-2 rounded-pill shadow-sm">
                <i class="bi bi-plus-lg me-2"></i><span class="fw-semibold">Add Category</span>
            </a>
        </div>

        <!-- Quick Stats -->
        <div class="row g-4 mb-5">
            <div class="col-md-4">
                <div class="card border-0 shadow-sm rounded-4 p-3 bg-white">
                    <div class="d-flex align-items-center">
                        <div class="bg-info bg-opacity-10 p-3 rounded-4 me-3">
                            <i class="bi bi-grid-3x3-gap fs-3 text-info"></i>
                        </div>
                        <div>
                            <span class="text-muted small d-block">Total Categories</span>
                            <span class="h4 fw-bold mb-0 text-dark">{{ $categories->total() }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card border-0 shadow-sm rounded-4 overflow-hidden bg-white">
            <div class="card-header bg-white border-bottom py-4 px-4">
                <form method="GET" action="{{ route('admin.categories.index') }}" id="filterForm">
                    <div class="row g-3 align-items-end justify-content-end">
                        <div class="col-md-3">
                            <label class="form-label small fw-bold text-muted text-uppercase mb-1">Brand Filter</label>
                            <select name="brand_id[]" id="brand_filter" class="selectpicker form-control" multiple
                                data-actions-box="true" data-live-search="true" title="All Brands">
                                @foreach ($brands as $brand)
                                    <option value="{{ $brand->id }}"
                                        {{ in_array($brand->id, (array) request('brand_id')) ? 'selected' : '' }}>
                                        {{ $brand->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label for="q" class="form-label small fw-bold text-muted text-uppercase mb-1">Search Keyword</label>
                            <div class="position-relative">
                                <i class="bi bi-search position-absolute top-50 start-0 translate-middle-y ms-3 text-muted"></i>
                                <input type="text" name="q" id="q" class="form-control rounded-pill ps-5 border-light bg-light"
                                    placeholder="Search categories..." value="{{ request('q') }}">
                            </div>
                        </div>
                        <div class="col-md-3 d-flex gap-2">
                            <button type="submit" class="btn btn-primary rounded-pill w-100 fw-bold">Filter</button>
                            @if (request()->anyFilled(['q', 'brand_id']))
                                <a href="{{ route('admin.categories.index') }}"
                                    class="btn btn-light rounded-pill border w-100 fw-bold">Clear</a>
                            @endif
                        </div>
                    </div>
                </form>
            </div>

            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light text-muted small text-uppercase">
                        <tr>
                            <th class="px-4 py-3 border-0">#ID</th>
                            <th class="px-4 py-3 border-0">Brand</th>
                            <th class="px-4 py-3 border-0">Category Name</th>
                            <th class="px-4 py-3 border-0">Slug</th>
                            <th class="px-4 py-3 border-0">Products</th>
                            <th class="px-4 py-3 border-0">Created</th>
                            <th class="px-4 py-3 border-0 text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="border-top-0">
                        @forelse($categories as $category)
                            <tr class="transition-row">
                                <td class="px-4 py-4 text-muted small">#{{ $category->id }}</td>
                                <td class="px-4 py-4">
                                    <span class="badge bg-primary bg-opacity-10 text-primary rounded-pill px-3 py-2 fw-normal">
                                        {{ $category->brand ? $category->brand->name : '–' }}
                                    </span>
                                </td>
                                <td class="px-4 py-4 fw-bold text-dark">{{ $category->name }}</td>
                                <td class="px-4 py-4"><code class="bg-light text-primary px-2 py-1 rounded small">/{{ $category->slug }}</code></td>
                                <td class="px-4 py-4"><span class="text-dark small"><i class="bi bi-box-seam me-1 text-muted"></i>{{ $category->products_count }}</span></td>
                                <td class="px-4 py-4 text-muted small">{{ $category->created_at->format('M d, Y') }}</td>
                                <td class="px-4 py-4 text-end">
                                    <div class="btn-group gap-2">
                                        <a href="{{ route('admin.categories.edit', $category) }}" class="btn btn-sm btn-light p-2 rounded-3 border-0 shadow-sm" title="Edit">
                                            <i class="bi bi-pencil-square text-primary fs-5"></i>
                                        </a>
                                        <form action="{{ route('admin.categories.destroy', $category) }}" method="POST" class="d-inline"
                                            onsubmit="return confirm('Delete this category?')">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-light p-2 rounded-3 border-0 shadow-sm" title="Delete">
                                                <i class="bi bi-trash text-danger fs-5"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="7" class="text-center py-5 text-muted">
                                <i class="bi bi-grid display-1 opacity-25 d-block mb-3"></i>
                                <strong>No categories found</strong>
                            </td></tr>
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
                    @include('common.pagination.pagination_data_show', ['data' => $categories])

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
                    @include('common.pagination.common_pagination', ['data' => $categories])
                </div>
            </div>
            {{-- pagination part end here ======================================= --}}
        </div>
    </div>
    @push('styles')
    <style>
        .transition-row { transition: background-color 0.2s; }
        .transition-row:hover { background-color: rgba(248,249,250,0.5); }
        
        .pagination { margin-bottom: 0 !important; }
        .page-link { border:none; padding:.5rem .85rem; margin:0 2px; border-radius:8px !important; color:#6c757d; }
        .page-item.active .page-link { background-color:#4f46e5; }
        
        .pagination-wrapper p.small.text-muted { display: none !important; }
        .pagination-wrapper nav>div.d-sm-flex { justify-content: flex-end !important; }
    </style>
    @endpush
    @push('scripts')
        <script>
            $(document).ready(function() {
                // selectpicker is now initialized globally in layout.blade.php
            });
        </script>
    @endpush
@endsection