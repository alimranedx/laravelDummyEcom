@extends('admin.layout')

@section('content')
    <div class="container-fluid py-4 animate-fade-in">
        <!-- Page Header -->
        <div class="d-flex justify-content-between align-items-center mb-5">
            <div>
                <h1 class="h3 fw-bold text-dark mb-1">Order Management</h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 small">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"
                                class="text-decoration-none text-muted">Dashboard</a></li>
                        <li class="breadcrumb-item active text-primary fw-medium" aria-current="page">Orders</li>
                    </ol>
                </nav>
            </div>
            <!-- Quick Filter Info -->
            <div class="text-end d-none d-md-block">
                <span class="badge bg-light text-muted border px-3 py-2 rounded-pill">
                    <i class="bi bi-clock-history me-2"></i> Real-time Order Flow
                </span>
            </div>
        </div>

        <!-- Main Content Card -->
        <div class="card border-0 shadow-sm rounded-4 overflow-hidden bg-white">
            <div class="card-header bg-white border-0 py-4 px-4 d-flex flex-column flex-md-row justify-content-between align-items-center gap-3">
                <h5 class="mb-0 fw-bold text-dark">Recent Orders</h5>
                <form method="GET" action="{{ route('admin.orders.index') }}" class="search-box position-relative d-flex gap-2 w-100" style="max-width: 400px;">
                    <div class="position-relative flex-grow-1">
                        <i class="bi bi-search position-absolute top-50 start-0 translate-middle-y ms-3 text-muted"></i>
                        <input type="text" name="q" value="{{ request('q') }}" class="form-control rounded-pill ps-5 border-light bg-light w-100"
                            placeholder="Search by Order ID or Customer...">
                    </div>
                    <button type="submit" class="btn btn-primary rounded-pill px-4 fw-medium shadow-sm">Search</button>
                    @if(request()->filled('q'))
                        <a href="{{ route('admin.orders.index') }}" class="btn btn-light rounded-pill border px-3">Clear</a>
                    @endif
                </form>
            </div>

            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light text-muted small text-uppercase">
                        <tr>
                            <th class="px-4 py-3 border-0">Order ID</th>
                            <th class="px-4 py-3 border-0">Customer</th>
                            <th class="px-4 py-3 border-0">Total Amount</th>
                            <th class="px-4 py-3 border-0">Order Status</th>
                            <th class="px-4 py-3 border-0">Payment</th>
                            <th class="px-4 py-3 border-0">Date</th>
                            <th class="px-4 py-3 border-0 text-end">Management</th>
                        </tr>
                    </thead>
                    <tbody class="border-top-0">
                        @forelse($orders as $order)
                            <tr class="transition-row">
                                <td class="px-4 py-4 fw-bold text-primary small">#{{ $order->id }}</td>
                                <td class="px-4 py-4">
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-circle-sm me-2 bg-gradient-secondary text-white fw-bold shadow-sm"
                                            style="width: 32px; height: 32px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 0.75rem; background: linear-gradient(135deg, #bdc3c7 0%, #2c3e50 100%);">
                                            {{ strtoupper(substr($order->user->name ?? 'N/A', 0, 1)) }}
                                        </div>
                                        <span class="fw-medium text-dark">{{ $order->user->name ?? 'N/A' }}</span>
                                    </div>
                                </td>
                                <td class="px-4 py-4 fw-bold text-dark">${{ number_format($order->total_price, 2) }}</td>
                                <td class="px-4 py-4">
                                    @php
                                        $statusClass =
                                            $order->status->value === 'completed'
                                                ? 'bg-success'
                                                : ($order->status->value === 'pending'
                                                    ? 'bg-warning text-dark'
                                                    : 'bg-secondary');
                                    @endphp
                                    <span class="badge rounded-pill {{ $statusClass }} px-3 py-2 small fw-normal">
                                        {{ $order->status->label() }}
                                    </span>
                                </td>
                                <td class="px-4 py-4">
                                    <span
                                        class="text-{{ $order->payment_status === 'paid' ? 'success' : 'muted' }} small fw-medium">
                                        <i
                                            class="bi bi-{{ $order->payment_status === 'paid' ? 'check-circle-fill' : 'dash-circle' }} me-1"></i>
                                        {{ ucfirst($order->payment_status) }}
                                    </span>
                                </td>
                                <td class="px-4 py-4">
                                    <span class="text-muted small">{{ $order->created_at->format('M d, Y H:i') }}</span>
                                </td>
                                <td class="px-4 py-4 text-end">
                                    <a href="{{ route('admin.orders.show', $order) }}"
                                        class="btn btn-sm btn-light p-2 rounded-3 border-0 shadow-sm" title="View Details">
                                        <i class="bi bi-eye-fill text-primary fs-5"></i>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-5">
                                    <div class="empty-state py-5 text-muted">
                                        <i class="bi bi-box-seam display-1 opacity-25 mb-4"></i>
                                        <h4 class="fw-bold">No orders found</h4>
                                        <p class="small">There are no transactions recorded at this time.</p>
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
                    @include('common.pagination.pagination_data_show', ['data' => $orders])

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
                    @include('common.pagination.common_pagination', ['data' => $orders])
                </div>
            </div>
            {{-- pagination part end here ======================================= --}}
        </div>
    </div>

    @push('styles')
        <style>
            .transition-row {
                transition: background-color 0.2s;
            }

            .transition-row:hover {
                background-color: rgba(248, 249, 250, 0.5);
            }

            .btn-light {
                background: #f8f9fa;
            }

            .btn-light:hover {
                background: #e9ecef;
            }

            /* Pagination custom styling */
            .pagination {
                margin-bottom: 0;
            }

            .page-link {
                border: none;
                padding: 0.5rem 0.85rem;
                margin: 0 2px;
                border-radius: 8px !important;
                color: #6c757d;
            }

            .page-item.active .page-link {
                background-color: #4f46e5;
                box-shadow: 0 4px 10px rgba(79, 70, 229, 0.2);
            }
            
            .pagination-wrapper p.small.text-muted { display: none !important; }
            .pagination-wrapper nav>div.d-sm-flex { justify-content: flex-end !important; }
        </style>
    @endpush
@endsection
