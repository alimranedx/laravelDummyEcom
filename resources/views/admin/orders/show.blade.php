@php
    use App\Enums\OrderStatus;
@endphp

@extends('admin.layout')

@section('content')
    <div class="container-fluid py-4 animate-fade-in">
        <!-- Page Header -->
        <div class="d-flex justify-content-between align-items-center mb-5">
            <div>
                <h1 class="h3 fw-bold text-dark mb-1">Order Details</h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 small">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"
                                class="text-decoration-none text-muted">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.orders.index') }}"
                                class="text-decoration-none text-muted">Orders</a></li>
                        <li class="breadcrumb-item active text-primary fw-medium" aria-current="page">#{{ $order->id }}
                        </li>
                    </ol>
                </nav>
            </div>
            <a href="{{ route('admin.orders.index') }}"
                class="btn btn-outline-secondary d-inline-flex align-items-center px-4 py-2 rounded-pill transition">
                <i class="bi bi-arrow-left me-2"></i>
                <span class="fw-semibold">Back to Orders</span>
            </a>
        </div>

        <div class="row g-4">
            <!-- Left Column: Items and Shipping -->
            <div class="col-lg-8">
                <!-- Order Items Card -->
                <div class="card border-0 shadow-sm rounded-4 overflow-hidden bg-white mb-4">
                    <div class="card-header bg-white border-0 py-4 px-4 border-bottom border-light">
                        <h5 class="mb-0 fw-bold text-dark"><i class="bi bi-cart3 me-2 text-primary"></i> Order Items</h5>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="bg-light text-muted small text-uppercase">
                                    <tr>
                                        <th class="px-4 py-3 border-0">Product Image</th>
                                        <th class="px-4 py-3 border-0">Product Name</th>
                                        <th class="px-4 py-3 border-0">Price</th>
                                        <th class="px-4 py-3 border-0">Qty</th>
                                        <th class="px-4 py-3 border-0 text-end">Subtotal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($order->items as $item)
                                        <tr class="transition-row">
                                            <td class="px-4 py-3">
                                                <div class="rounded-3 overflow-hidden shadow-sm border"
                                                    style="width: 48px; height: 48px;">
                                                    <img src="{{ $item->product->image_url }}" class="w-100 h-100" style="object-fit: cover;">
                                                </div>
                                            </td>
                                            <td class="px-4 py-3 fw-bold text-dark">{{ $item->product->name }}</td>
                                            <td class="px-4 py-3">${{ number_format($item->price, 2) }}</td>
                                            <td class="px-4 py-3">{{ $item->quantity }}</td>
                                            <td class="px-4 py-3 text-end fw-bold text-dark">
                                                ${{ number_format($item->price * $item->quantity, 2) }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot class="bg-light-subtle">
                                    <tr>
                                        <td colspan="4"
                                            class="px-4 py-4 text-end text-muted text-uppercase small fw-bold letter-spacing-1">
                                            Grand Total</td>
                                        <td class="px-4 py-4 text-end h4 fw-extrabold text-primary mb-0">
                                            ${{ number_format($order->total_price, 2) }}</td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Shipping Address Card -->
                <div class="card border-0 shadow-sm rounded-4 overflow-hidden bg-white">
                    <div class="card-header bg-white border-0 py-4 px-4 border-bottom border-light">
                        <h5 class="mb-0 fw-bold text-dark"><i class="bi bi-geo-alt me-2 text-primary"></i> Delivery Location
                        </h5>
                    </div>
                    <div class="card-body p-4">
                        <div class="bg-light p-4 rounded-4 border-start border-4 border-primary">
                            <p class="mb-0 text-dark fw-medium lh-lg">{{ $order->shipping_address }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column: Status and Customer -->
            <div class="col-lg-4">
                <!-- Status Update Card -->
                <div class="card border-0 shadow-sm rounded-4 overflow-hidden bg-white mb-4">
                    <div class="card-header bg-white border-0 py-4 px-4 border-bottom border-light">
                        <h5 class="mb-0 fw-bold text-dark"><i class="bi bi-gear-wide-connected me-2 text-primary"></i> Order
                            Status</h5>
                    </div>
                    <div class="card-body p-4 text-center">
                        @php
                            $statusClass =
                                $order->status->value === 'completed'
                                    ? 'bg-success'
                                    : ($order->status->value === 'pending'
                                        ? 'bg-warning text-dark'
                                        : 'bg-secondary');
                        @endphp
                        <div class="mb-4">
                            <span class="badge rounded-pill {{ $statusClass }} px-4 py-2 fs-6 fw-normal shadow-sm">
                                {{ $order->status->label() }}
                            </span>
                        </div>

                        <form action="{{ route('admin.orders.updateStatus', $order) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <div class="mb-3">
                                <select class="form-select rounded-pill border-light bg-light py-2 px-4 shadow-sm"
                                    name="status" required>
                                    @foreach (OrderStatus::cases() as $status)
                                        <option value="{{ $status->value }}"
                                            {{ $order->status === $status ? 'selected' : '' }}>
                                            {{ $status->label() }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <button type="submit"
                                class="btn btn-primary w-100 rounded-pill py-2 fw-bold shadow-sm transition">
                                <i class="bi bi-arrow-repeat me-1"></i> Update Status
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Customer Info Card -->
                <div class="card border-0 shadow-sm rounded-4 overflow-hidden bg-white mb-4">
                    <div class="card-header bg-white border-0 py-4 px-4 border-bottom border-light">
                        <h5 class="mb-0 fw-bold text-dark"><i class="bi bi-person-badge me-2 text-primary"></i> Customer
                            Details</h5>
                    </div>
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center mb-4">
                            <div class="avatar-circle me-3 bg-gradient-primary text-white fw-bold shadow-sm"
                                style="width: 50px; height: 50px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 1.25rem; background: linear-gradient(135deg, #4f46e5 0%, #3b82f6 100%);">
                                {{ strtoupper(substr($order->user->name ?? 'N/A', 0, 1)) }}
                            </div>
                            <div>
                                <div class="fw-bold text-dark mb-0 fs-5">{{ $order->user->name ?? 'N/A' }}</div>
                                <div class="text-muted small">{{ $order->user->email ?? 'N/A' }}</div>
                            </div>
                        </div>
                        <div class="border-top pt-3">
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-muted small">Registered:</span>
                                <span
                                    class="text-dark small fw-medium">{{ !empty($order->user->created_at) ? $order->user->created_at->format('M Y') : 'N/A' }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Payment Info Card -->
                <div class="card border-0 shadow-sm rounded-4 overflow-hidden bg-white">
                    <div class="card-header bg-white border-0 py-4 px-4 border-bottom border-light">
                        <h5 class="mb-0 fw-bold text-dark"><i class="bi bi-credit-card me-2 text-primary"></i> Financial
                            Data</h5>
                    </div>
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between mb-3 border-bottom pb-2 border-light">
                            <span class="text-muted small">Payment Method:</span>
                            <span class="text-dark small fw-bold">{{ ucfirst($order->payment_method) }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-3 border-bottom pb-2 border-light">
                            <span class="text-muted small">Payment Status:</span>
                            <span
                                class="badge rounded-pill {{ $order->payment_status === 'paid' ? 'bg-success' : 'bg-light text-muted border' }} px-3 py-1 fw-normal">
                                {{ ucfirst($order->payment_status) }}
                            </span>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span class="text-muted small">Placement Date:</span>
                            <span class="text-dark small fw-medium">{{ $order->created_at->format('M d, Y H:i') }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('styles')
        <style>
            .letter-spacing-1 {
                letter-spacing: 1px;
            }

            .transition-row {
                transition: background-color 0.2s;
            }

            .transition-row:hover {
                background-color: rgba(248, 249, 250, 0.5);
            }
        </style>
    @endpush
@endsection
