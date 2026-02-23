<x-app-layout>
    <x-slot name="header">
        <h2 class="h4 mb-0 text-white">
            {{ __('Welcome back, ') }} {{ Auth::user()->name }}
        </h2>
    </x-slot>

    <div class="py-4">
        <div class="container">
            <!-- Stats Row -->
            <div class="row mb-4">
                <div class="col-md-4 mb-3 mb-md-0">
                    <div class="card border-0 shadow-sm bg-primary text-white h-100">
                        <div class="card-body d-flex align-items-center">
                            <div class="flex-grow-1">
                                <h6 class="text-uppercase mb-1" style="font-size: 0.75rem; letter-spacing: 1px; opacity: 0.8;">Total Orders</h6>
                                <h3 class="mb-0">{{ $stats['total_orders'] }}</h3>
                            </div>
                            <div class="p-3 bg-white bg-opacity-25 rounded shadow-sm">
                                <i class="bi bi-cart-check fs-4"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-3 mb-md-0">
                    <div class="card border-0 shadow-sm bg-warning text-white h-100">
                        <div class="card-body d-flex align-items-center">
                            <div class="flex-grow-1">
                                <h6 class="text-uppercase mb-1" style="font-size: 0.75rem; letter-spacing: 1px; opacity: 0.8;">Pending Orders</h6>
                                <h3 class="mb-0">{{ $stats['pending_orders'] }}</h3>
                            </div>
                            <div class="p-3 bg-white bg-opacity-25 rounded shadow-sm">
                                <i class="bi bi-clock-history fs-4"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card border-0 shadow-sm bg-success text-white h-100">
                        <div class="card-body d-flex align-items-center">
                            <div class="flex-grow-1">
                                <h6 class="text-uppercase mb-1" style="font-size: 0.75rem; letter-spacing: 1px; opacity: 0.8;">Completed Orders</h6>
                                <h3 class="mb-0">{{ $stats['completed_orders'] }}</h3>
                            </div>
                            <div class="p-3 bg-white bg-opacity-25 rounded shadow-sm">
                                <i class="bi bi-bag-check fs-4"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <!-- User Profile Summary -->
                <div class="col-lg-4 mb-4">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-header bg-white border-bottom-0 pt-4 pb-0">
                            <h5 class="card-title fw-bold">Account Overview</h5>
                        </div>
                        <div class="card-body">
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item px-0 border-0 d-flex justify-content-between">
                                    <small class="text-muted">Name</small>
                                    <span class="fw-semibold text-end">{{ $user->name }}</span>
                                </li>
                                <li class="list-group-item px-0 border-0 d-flex justify-content-between">
                                    <small class="text-muted">Email</small>
                                    <span class="fw-semibold text-end">{{ $user->email }}</span>
                                </li>
                                <li class="list-group-item px-0 border-0 d-flex justify-content-between">
                                    <small class="text-muted">Member Since</small>
                                    <span class="fw-semibold text-end">{{ $user->created_at->format('M d, Y') }}</span>
                                </li>
                            </ul>
                            <div class="mt-4">
                                <a href="{{ route('profile.edit') }}" class="btn btn-outline-primary btn-sm w-100 rounded-pill">Manage Profile</a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Recent Orders Table -->
                <div class="col-lg-8 mb-4">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-header bg-white border-bottom-0 d-flex justify-content-between align-items-center pt-4 pb-0">
                            <h5 class="card-title fw-bold mb-0">Recent Orders</h5>
                            <a href="#" class="btn btn-link btn-sm text-decoration-none p-0">View All</a>
                        </div>
                        <div class="card-body">
                            @if($recentOrders->count() > 0)
                                <div class="table-responsive">
                                    <table class="table table-hover align-middle">
                                        <thead class="bg-light">
                                            <tr>
                                                <th class="border-0 small text-uppercase">Order ID</th>
                                                <th class="border-0 small text-uppercase">Date</th>
                                                <th class="border-0 small text-uppercase text-end">Total</th>
                                                <th class="border-0 small text-uppercase text-center">Status</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($recentOrders as $order)
                                                <tr>
                                                    <td class="fw-bold">#ORD-{{ str_pad($order->id, 5, '0', STR_PAD_LEFT) }}</td>
                                                    <td>{{ $order->created_at->format('d M Y') }}</td>
                                                    <td class="text-end fw-semibold">${{ number_format($order->total_price, 2) }}</td>
                                                    <td class="text-center">
                                                        @php
                                                            $badgeClass = match($order->status) {
                                                                \App\Enums\OrderStatus::PENDING => 'bg-warning',
                                                                \App\Enums\OrderStatus::COMPLETED => 'bg-success',
                                                                \App\Enums\OrderStatus::CANCELLED => 'bg-danger',
                                                                default => 'bg-secondary'
                                                            };
                                                        @endphp
                                                        <span class="badge {{ $badgeClass }} rounded-pill px-3">{{ $order->status->name ?? $order->status }}</span>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <div class="text-center py-5">
                                    <div class="mb-3">
                                        <i class="bi bi-journal-x fs-1 text-muted"></i>
                                    </div>
                                    <p class="text-muted mb-0">You haven't placed any orders yet.</p>
                                    <a href="{{ route('home') }}" class="btn btn-primary btn-sm rounded-pill mt-3 px-4">Start Shopping</a>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>