@extends('admin.layout')

@section('content')
    <div class="container-fluid py-4 animate-fade-in">
        <div class="d-flex justify-content-between align-items-center mb-5">
            <div>
                <h1 class="h3 fw-bold text-dark mb-1">Product Catalog</h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 small">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"
                                class="text-decoration-none text-muted">Dashboard</a></li>
                        <li class="breadcrumb-item active text-primary fw-medium" aria-current="page">Products</li>
                    </ol>
                </nav>
            </div>
            <a href="{{ route('admin.products.create') }}"
                class="btn btn-primary d-inline-flex align-items-center px-4 py-2 rounded-pill shadow-sm">
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
            {{-- Filter Row --}}
            <div class="card-header bg-white border-bottom py-4 px-4">
                <form method="GET" action="{{ route('admin.products.index') }}" id="filterForm">
                    <div class="row g-3 align-items-end">

                        <div class="col-md-3">
                            <label for="date_range" class="form-label small fw-bold text-muted text-uppercase mb-1">Date
                                Range</label>
                            <input class="form-control rounded-pill border-light bg-light" type="text" name="date_range"
                                id="date_range" value="{{ request('date_range') }}" placeholder="Select date range" />
                        </div>
                        <div class="col-md-2">
                            <label class="form-label small fw-bold text-muted text-uppercase mb-1">Brand</label>
                            <select name="brand_id[]" id="brand_filter" class="selectpicker form-control" multiple
                                data-actions-box="true" data-live-search="true" title="All Brands">
                                @foreach ($brands as $brand)
                                    <option value="{{ $brand->id }}"
                                        {{ in_array($brand->id, (array) request('brand_id')) ? 'selected' : '' }}>
                                        {{ $brand->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label small fw-bold text-muted text-uppercase mb-1">Category</label>
                            <select name="category_id[]" id="category_filter" class="selectpicker form-control" multiple
                                data-actions-box="true" data-live-search="true" title="All Categories">
                                @foreach ($categories as $cat)
                                    <option value="{{ $cat->id }}" data-brand-id="{{ $cat->brand_id }}"
                                        {{ in_array($cat->id, (array) request('category_id')) ? 'selected' : '' }}>
                                        {{ $cat->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="q" class="form-label small fw-bold text-muted text-uppercase mb-1">Search
                                Keyword</label>
                            <div class="position-relative">
                                <i
                                    class="bi bi-search position-absolute top-50 start-0 translate-middle-y ms-3 text-muted"></i>
                                <input type="text" name="q" id="q"
                                    class="form-control rounded-pill ps-5 border-light bg-light"
                                    placeholder="Search products..." value="{{ request('q') }}">
                            </div>
                        </div>
                        <div class="col-md-2 d-flex gap-2">
                            <button type="submit" class="btn btn-primary rounded-pill w-100 fw-bold">Filter</button>
                            @if (request()->anyFilled(['q', 'date_range', 'category_id', 'brand_id']))
                                <a href="{{ route('admin.products.index') }}"
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
                                        <div class="rounded-3 overflow-hidden shadow-sm border me-3"
                                            style="width:48px;height:48px;flex-shrink:0">
                                            <img src="{{ $product->image_url }}" alt="{{ $product->name }}"
                                                style="width:100%;height:100%;object-fit:cover">
                                        </div>
                                        <span class="fw-bold text-dark">{{ $product->name }}</span>
                                    </div>
                                </td>
                                <td class="px-4 py-4">
                                    <span
                                        class="badge bg-info bg-opacity-10 text-info rounded-pill px-3 py-2 fw-normal small">{{ $product->category->name ?? 'N/A' }}</span>
                                </td>
                                <td class="px-4 py-4 fw-bold text-dark">${{ number_format($product->price, 2) }}</td>
                                <td class="px-4 py-4">
                                    <span
                                        class="badge rounded-pill {{ $product->stock > 10 ? 'bg-success' : ($product->stock > 0 ? 'bg-warning text-dark' : 'bg-danger') }} px-3 py-2 fw-normal small">
                                        {{ $product->stock }} units
                                    </span>
                                </td>
                                <td class="px-4 py-4 text-muted small">{{ $product->created_at->format('M d, Y') }}</td>
                                <td class="px-4 py-4 text-end">
                                    <div class="btn-group gap-2">
                                        <a href="{{ route('admin.products.edit', $product) }}"
                                            class="btn btn-sm btn-light p-2 rounded-3 border-0 shadow-sm" title="Edit">
                                            <i class="bi bi-pencil-square text-primary fs-5"></i>
                                        </a>
                                        <form action="{{ route('admin.products.destroy', $product) }}" method="POST"
                                            class="d-inline" onsubmit="return confirm('Delete this product?')">
                                            @csrf @method('DELETE')
                                            <button type="submit"
                                                class="btn btn-sm btn-light p-2 rounded-3 border-0 shadow-sm">
                                                <i class="bi bi-trash text-danger fs-5"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-5 text-muted">
                                    <i class="bi bi-box display-1 opacity-25 d-block mb-3"></i>
                                    <strong>No products found</strong>
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
                    @include('common.pagination.pagination_data_show', ['data' => $products])

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
                                        {{ request($perPageQueryName ?? 'per_page', 5) == $option ? 'selected' : '' }}>
                                        {{ $option }}</option>
                                @endforeach
                            </select>
                        </form>
                    </div>

                    <!-- Right: Pagination Links -->
                    @include('common.pagination.common_pagination', ['data' => $products])
                </div>
            </div>

            {{-- pagination part end here ======================================= --}}

        </div>
    </div>
    @push('styles')
        <link rel="stylesheet" href="{{ asset('/assets/bootstrap-select-1.14.0-beta3/css/bootstrap-select.min.css') }}">
        <style>
            .transition-row {
                transition: background-color 0.2s;
            }

            .transition-row:hover {
                background-color: rgba(248, 249, 250, 0.5);
            }

            .page-link {
                border: none;
                padding: .5rem .85rem;
                margin: 0 2px;
                border-radius: 8px !important;
            }

            .page-item.active .page-link {
                background-color: #4f46e5;
            }

            .pagination {
                margin-bottom: 0 !important;
            }

            .pagination-wrapper p.small.text-muted {
                display: none !important;
            }

            .pagination-wrapper nav>div.d-sm-flex {
                justify-content: flex-end !important;
            }
        </style>
    @endpush
    @push('scripts')
        <!-- 3. Bootstrap Select (correct version) -->
        <script src="{{ asset('/assets/bootstrap-select-1.14.0-beta3/js/bootstrap-select.min.js') }}"></script>

        <!-- 4. Init -->
        <script>
            $(document).ready(function() {
                $('.selectpicker').selectpicker();

                // Dynamic Category Filter Based on Brand Selection
                $('#brand_filter').on('changed.bs.select', function(e, clickedIndex, isSelected, previousValue) {
                    filterCategoriesByBrand();
                });

                function filterCategoriesByBrand() {
                    let selectedBrands = $('#brand_filter').val() || [];

                    $('#category_filter option').each(function() {
                        let brandId = $(this).data('brand-id');

                        if (selectedBrands.length === 0 || selectedBrands.includes(String(brandId))) {
                            $(this).removeClass('d-none').prop('disabled', false); // Show
                        } else {
                            $(this).addClass('d-none').prop('disabled', true); // Hide
                            $(this).prop('selected', false); // Deselect
                        }
                    });

                    $('#category_filter').selectpicker('refresh');
                }

                // Initialize dependent dropdown state on load
                filterCategoriesByBrand();

                $('#date_range').daterangepicker({
                    autoUpdateInput: false,
                    showDropdowns: true,
                    alwaysShowCalendars: true,
                    linkedCalendars: false,
                    locale: {
                        format: 'YYYY/MM/DD',
                        cancelLabel: 'Clear'
                    },
                    ranges: {
                        'Today': [moment(), moment()],
                        'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                        'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                        'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                        'This Month': [moment().startOf('month'), moment().endOf('month')],
                        'Last Month': [
                            moment().subtract(1, 'month').startOf('month'),
                            moment().subtract(1, 'month').endOf('month')
                        ]
                    }
                });

                $('#date_range').on('apply.daterangepicker', function(ev, picker) {
                    $(this).val(picker.startDate.format('YYYY/MM/DD') + ' - ' + picker.endDate.format(
                        'YYYY/MM/DD'));
                });

                $('#date_range').on('cancel.daterangepicker', function(ev, picker) {
                    $(this).val('');
                });
            });
        </script>
    @endpush
@endsection
