@php
    use App\Enums\OrderStatus;
@endphp

@extends('admin.layout')

@section('content')
    <div class="row mb-3">
        <div class="col-md-6">
            <h1>Order #{{ $order->id }}</h1>
        </div>
        <div class="col-md-6 text-end">
            <a href="{{ route('admin.orders.index') }}" class="btn btn-secondary">Back to Orders</a>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card mb-3">
                <div class="card-header">
                    <h5 class="mb-0">Order Items</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Product</th>
                                    <th>Price</th>
                                    <th>Quantity</th>
                                    <th>Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($order->items as $item)
                                    <tr>
                                        <td>{{ $item->product->name }}</td>
                                        <td>${{ number_format($item->price, 2) }}</td>
                                        <td>{{ $item->quantity }}</td>
                                        <td>${{ number_format($item->price * $item->quantity, 2) }}</td>
                                    </tr>
                                @endforeach
                                <tr>
                                    <td colspan="3" class="text-end"><strong>Total:</strong></td>
                                    <td><strong>${{ number_format($order->total_price, 2) }}</strong></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card mb-3">
                <div class="card-header">
                    <h5 class="mb-0">Customer Information</h5>
                </div>
                <div class="card-body">
                    <p><strong>Name:</strong> {{ $order->user->name }}</p>
                    <p><strong>Email:</strong> {{ $order->user->email }}</p>
                </div>
            </div>

            <div class="card mb-3">
                <div class="card-header">
                    <h5 class="mb-0">Order Details</h5>
                </div>
                <div class="card-body">
                    <p><strong>Status:</strong>
                        <span
                            class="badge bg-{{ $order->status->value === 'completed' ? 'success' : ($order->status->value === 'pending' ? 'warning' : 'secondary') }}">
                            {{ $order->status->label() }}
                        </span>
                    </p>
                    <p><strong>Payment Status:</strong> {{ ucfirst($order->payment_status) }}</p>
                    <p><strong>Payment Method:</strong> {{ ucfirst($order->payment_method) }}</p>
                    <p><strong>Date:</strong> {{ $order->created_at->format('M d, Y H:i') }}</p>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Update Status</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.orders.updateStatus', $order) }}" method="POST">
                        @csrf
                        @method('PATCH')

                        <div class="mb-3">
                            <select class="form-select" name="status" required>
                                @foreach(OrderStatus::cases() as $status)
                                    <option value="{{ $status->value }}" {{ $order->status === $status ? 'selected' : '' }}>
                                        {{ $status->label() }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <button type="submit" class="btn btn-primary w-100">Update Status</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-3">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Shipping Address</h5>
                </div>
                <div class="card-body">
                    <p>{{ $order->shipping_address }}</p>
                </div>
            </div>
        </div>
    </div>
@endsection