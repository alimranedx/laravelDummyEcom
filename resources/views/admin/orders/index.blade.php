@extends('admin.layout')

@section('content')
    <div class="container-fluid py-4 animate-fade-in">
        <!-- Page Header -->
        <div class="d-flex justify-content-between align-items-center mb-5">
            <div>
                <h1 class="h3 fw-bold text-dark mb-1">Order Management</h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 small">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}" class="text-decoration-none text-muted">Dashboard</a></li>
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
            <div class="card-header bg-white border-0 py-4 px-4 d-flex justify-content-between align-items-center">
                <h5 class="mb-0 fw-bold text-dark">Recent Orders</h5>
                <div class="search-box position-relative" style="max-width: 300px;">
                    <i class="bi bi-search position-absolute top-50 start-0 translate-middle-y ms-3 text-muted"></i>
                    <input type="text" class="form-control rounded-pill ps-5 border-light bg-light" placeholder="Search orders...">
                </div>
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
                                        <div class="avatar-circle-sm me-2 bg-gradient-secondary text-white fw-bold shadow-sm" style="width: 32px; height: 32px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 0.75rem; background: linear-gradient(135deg, #bdc3c7 0%, #2c3e50 100%);">
                                            {{ strtoupper(substr($order->user->name, 0, 1)) }}
                                        </div>
                                        <span class="fw-medium text-dark">{{ $order->user->name }}</span>
                                    </div>
                                </td>
                                <td class="px-4 py-4 fw-bold text-dark">${{ number_format($order->total_price, 2) }}</td>
                                <td class="px-4 py-4">
                                    @php
                                        $statusClass = $order->status->value === 'completed' ? 'bg-success' : ($order->status->value === 'pending' ? 'bg-warning text-dark' : 'bg-secondary');
                                    @endphp
                                    <span class="badge rounded-pill {{ $statusClass }} px-3 py-2 small fw-normal">
                                        {{ $order->status->label() }}
                                    </span>
                                </td>
                                <td class="px-4 py-4">
                                    <span class="text-{{ $order->payment_status === 'paid' ? 'success' : 'muted' }} small fw-medium">
                                        <i class="bi bi-{{ $order->payment_status === 'paid' ? 'check-circle-fill' : 'dash-circle' }} me-1"></i>
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
            
            @if($orders->hasPages())
                <div class="card-footer bg-white py-4 px-4 border-top">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="small text-muted">
                            Showing {{ $orders->firstItem() }} to {{ $orders->lastItem() }} of {{ $orders->total() }} results
                        </div>
                        <div>
                            {{ $orders->links() }}
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>

    @push('styles')
    <style>
        .transition-row { transition: background-color 0.2s; }
        .transition-row:hover { background-color: rgba(248, 249, 250, 0.5); }
        .btn-light { background: #f8f9fa; }
        .btn-light:hover { background: #e9ecef; }
        
        /* Pagination custom styling */
        .pagination { margin-bottom: 0; }
        .page-link { border: none; padding: 0.5rem 0.85rem; margin: 0 2px; border-radius: 8px !important; color: #6c757d; }
        .page-item.active .page-link { background-color: #4f46e5; box-shadow: 0 4px 10px rgba(79, 70, 229, 0.2); }
    </style>
    @endpush
@endsection
锋