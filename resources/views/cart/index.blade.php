@extends('layouts.frontend')

@section('content')
<div class="container py-5 animate-fade-in">
    <div class="d-flex align-items-center mb-5">
        <h1 class="fw-extrabold mb-0 me-3">Your Shopping Bag</h1>
        <span class="badge bg-primary rounded-pill px-3">{{ count($cart) }} Items</span>
    </div>

    @if(count($cart) > 0)
        <div class="row g-5">
            <div class="col-lg-8">
                <div class="premium-card bg-white shadow-sm border-0 p-4">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead>
                                <tr class="text-uppercase small fw-bold text-muted border-bottom-0">
                                    <th class="ps-0 py-3">Product details</th>
                                    <th class="py-3">Quantity</th>
                                    <th class="py-3">Price</th>
                                    <th class="py-3 text-end pe-0">Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($cart as $id => $details)
                                    <tr data-id="{{ $id }}" class="border-bottom-0">
                                        <td class="ps-0 py-4">
                                            <div class="d-flex align-items-center">
                                                <div class="rounded-3 overflow-hidden me-3 shadow-sm border" style="width: 80px; height: 80px;">
                                                    @if($details['image'])
                                                        <img src="{{ asset('storage/' . $details['image']) }}" class="w-100 h-100" style="object-fit: cover;">
                                                    @else
                                                        <div class="bg-light w-100 h-100 d-flex align-items-center justify-content-center">
                                                            <i class="bi bi-image text-muted"></i>
                                                        </div>
                                                    @endif
                                                </div>
                                                <div>
                                                    <h6 class="fw-bold mb-1">{{ $details['name'] }}</h6>
                                                    <button class="btn btn-link p-0 text-danger small text-decoration-none remove-from-cart">
                                                        <i class="bi bi-trash3 me-1"></i>Remove
                                                    </button>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="py-4">
                                            <div class="input-group input-group-sm shadow-sm rounded-pill overflow-hidden border" style="width: 110px;">
                                                <input type="number" value="{{ $details['quantity'] }}" class="form-control border-0 text-center quantity update-cart" min="1">
                                            </div>
                                        </td>
                                        <td class="py-4 fw-medium">${{ number_format($details['price'], 2) }}</td>
                                        <td class="py-4 text-end pe-0 fw-bold text-primary">${{ number_format($details['price'] * $details['quantity'], 2) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                
                <a href="{{ route('home') }}" class="btn btn-link text-muted text-decoration-none mt-4 ps-0">
                    <i class="bi bi-arrow-left me-2"></i>Continue Shopping
                </a>
            </div>

            <div class="col-lg-4">
                <div class="sidebar-section shadow-sm border-0 animate-fade-in" style="animation-delay: 0.1s;">
                    <h5 class="fw-bold mb-4">Order Summary</h5>
                    @php $total = 0 @endphp
                    @foreach($cart as $details)
                        @php $total += $details['price'] * $details['quantity'] @endphp
                    @endforeach
                    
                    <div class="d-flex justify-content-between mb-3 text-muted">
                        <span>Original Price</span>
                        <span>${{ number_format($total, 2) }}</span>
                    </div>
                    <div class="d-flex justify-content-between mb-3 text-muted">
                        <span>Delivery Fee</span>
                        <span class="text-success">Free</span>
                    </div>
                    <hr class="my-4 opacity-50">
                    <div class="d-flex justify-content-between mb-5 h4">
                        <strong class="fw-extrabold">Total Amount</strong>
                        <strong class="text-primary fw-extrabold">${{ number_format($total, 2) }}</strong>
                    </div>
                    
                    <a href="{{ route('checkout.index') }}" class="btn btn-premium w-100 py-3 rounded-4 shadow-lg h5 mb-0">
                        Proceed to Checkout<i class="bi bi-arrow-right ms-2"></i>
                    </a>
                </div>
            </div>
        </div>
    @else
        <div class="text-center py-5 bg-white rounded-4 shadow-sm animate-fade-in">
            <i class="bi bi-bag-x-fill display-1 text-muted opacity-25 mb-4 d-block"></i>
            <h2 class="fw-bold mb-3">Your bag is empty</h2>
            <p class="text-muted mb-5">Looks like you haven't made your choice yet.</p>
            <a href="{{ route('home') }}" class="btn btn-premium btn-lg px-5 py-3 rounded-pill fw-bold shadow-lg">
                Discover Products
            </a>
        </div>
    @endif
</div>

<script type="module">
    import $ from 'jquery';
    window.$ = window.jQuery = $;

    $(".update-cart").change(function (e) {
        e.preventDefault();
        var ele = $(this);
        $.ajax({
            url: '{{ route('cart.update') }}',
            method: "patch",
            data: {
                _token: '{{ csrf_token() }}', 
                id: ele.parents("tr").attr("data-id"), 
                quantity: ele.val()
            },
            success: function (response) {
               window.location.reload();
            }
        });
    });

    $(".remove-from-cart").click(function (e) {
        e.preventDefault();
        var ele = $(this);
        if(confirm("Discard this item from your bag?")) {
            $.ajax({
                url: '{{ route('cart.remove') }}',
                method: "DELETE",
                data: {
                    _token: '{{ csrf_token() }}', 
                    id: ele.parents("tr").attr("data-id")
                },
                success: function (response) {
                    window.location.reload();
                }
            });
        }
    });
</script>
@endsection