@extends('layouts.frontend')

@section('content')
    <div class="container py-5 animate-fade-in">
        <div class="d-flex align-items-center mb-5">
            <a href="{{ route('cart.index') }}" class="btn btn-white btn-sm rounded-circle shadow-sm border me-3">
                <i class="bi bi-arrow-left"></i>
            </a>
            <h1 class="fw-extrabold mb-0">Complete Your Order</h1>
        </div>

        <div class="row g-5">
            <div class="col-lg-7">
                <div class="premium-card bg-white shadow-sm border-0 p-5">
                    <h5 class="fw-bold mb-4 d-flex align-items-center">
                        <span class="badge bg-primary rounded-circle me-3"
                            style="width: 25px; height: 25px; display: inline-flex; align-items: center; justify-content: center; font-size: 0.8rem;">1</span>
                        Shipping Destination
                    </h5>
                    <form id="checkout-form" action="{{ route('checkout.place') }}" method="POST">
                        @csrf
                        <div class="row g-4 mb-5">
                            <div class="col-md-6">
                                <label class="form-label small fw-bold text-uppercase text-muted mb-2">Recipient
                                    Name</label>
                                <input type="text" name="name" class="form-control rounded-3 py-3 shadow-sm border-light"
                                    placeholder="e.g. John Doe" required
                                    value="{{ auth()->user() ? auth()->user()->name : '' }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-bold text-uppercase text-muted mb-2">Phone Number</label>
                                <input type="text" name="phone" class="form-control rounded-3 py-3 shadow-sm border-light"
                                    placeholder="+8801xxxxxxxxx" required>
                            </div>
                            <div class="col-12">
                                <label class="form-label small fw-bold text-uppercase text-muted mb-2">Full Delivery
                                    Address</label>
                                <textarea name="address" class="form-control rounded-3 py-3 shadow-sm border-light" rows="3"
                                    placeholder="House #, Road #, City..." required></textarea>
                            </div>
                        </div>

                        <h5 class="fw-bold mb-4 d-flex align-items-center">
                            <span class="badge bg-primary rounded-circle me-3"
                                style="width: 25px; height: 25px; display: inline-flex; align-items: center; justify-content: center; font-size: 0.8rem;">2</span>
                            Choose Payment Mode
                        </h5>
                        <div class="row g-3 mb-5">
                            <div class="col-md-6">
                                <div class="form-check custom-checkout-radio p-0">
                                    <input class="form-check-input d-none" type="radio" name="payment_method" id="cod"
                                        value="cod" checked>
                                    <label class="card premium-card p-4 h-100 cursor-pointer text-center" for="cod">
                                        <i class="bi bi-cash-stack fs-1 text-primary mb-2"></i>
                                        <div class="fw-bold">Cash on Delivery</div>
                                        <small class="text-muted">Pay when you receive</small>
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-check custom-checkout-radio p-0">
                                    <input class="form-check-input d-none" type="radio" name="payment_method" id="online"
                                        value="online">
                                    <label class="card premium-card p-4 h-100 cursor-pointer text-center" for="online">
                                        <i class="bi bi-wallet2 fs-1 text-primary mb-2"></i>
                                        <div class="fw-bold">Online Payment</div>
                                        <small class="text-muted">bKash, Nagad, Card</small>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-premium w-100 py-3 rounded-4 shadow-lg h5 mb-0">
                            Confirm Purchase <i class="bi bi-check-lg ms-2"></i>
                        </button>
                    </form>
                </div>
            </div>

            <div class="col-lg-5">
                <div class="sidebar-section shadow-sm border-0 animate-fade-in" style="animation-delay: 0.1s;">
                    <h5 class="fw-bold mb-4">Cart Snapshot</h5>
                    @php $total = 0 @endphp
                    @foreach($cart as $id => $details)
                        @php $total += $details['price'] * $details['quantity'] @endphp
                        <div class="d-flex align-items-center mb-4">
                            <div class="rounded-3 overflow-hidden shadow-sm border me-3"
                                style="width: 50px; height: 50px; flex-shrink: 0;">
                                @if($details['image'])
                                    <img src="{{ asset('storage/' . $details['image']) }}" class="w-100 h-100"
                                        style="object-fit: cover;">
                                @else
                                    <div class="bg-light w-100 h-100 d-flex align-items-center justify-content-center">
                                        <i class="bi bi-image text-muted small"></i>
                                    </div>
                                @endif
                            </div>
                            <div class="flex-grow-1">
                                <h6 class="mb-0 fw-bold small">{{ $details['name'] }}</h6>
                                <small class="text-muted">{{ $details['quantity'] }} unit(s)</small>
                            </div>
                            <span
                                class="fw-bold fw-medium">${{ number_format($details['price'] * $details['quantity'], 2) }}</span>
                        </div>
                    @endforeach
                    <hr class="my-4 opacity-50">
                    <div class="d-flex justify-content-between h4 mb-0">
                        <strong class="fw-extrabold">Total payable</strong>
                        <strong class="text-primary fw-extrabold">${{ number_format($total, 2) }}</strong>
                    </div>
                </div>

                <div class="bg-white p-4 rounded-4 shadow-sm border-0 border-start border-4 border-warning mt-4 animate-fade-in"
                    style="animation-delay: 0.2s;">
                    <small class="text-muted d-block mb-1 fw-bold text-uppercase">Payment Note</small>
                    <p class="mb-0 small">"Online Payment" feature is currently in demonstration mode. Selecting it will
                        simulate a successful transaction.</p>
                </div>
            </div>
        </div>
    </div>

    <style>
        .cursor-pointer {
            cursor: pointer;
        }

        .custom-checkout-radio input:checked+label {
            border-color: var(--primary) !important;
            background-color: var(--card-hover) !important;
            box-shadow: var(--shadow-md) !important;
        }
    </style>
@endsection