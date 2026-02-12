@extends('layouts.frontend')

@section('content')
    <div class="container py-5 animate-fade-in">
        <div class="row g-5">
            <div class="col-md-6">
                <div class="premium-card p-0 shadow-lg border-0 bg-transparent rounded-4 overflow-hidden">
                    @if($product->image_path)
                        <img src="{{ asset('storage/' . $product->image_path) }}" class="img-fluid w-100"
                            style="min-height: 500px; object-fit: cover;" alt="{{ $product->name }}">
                    @else
                        <div class="bg-white d-flex align-items-center justify-content-center rounded-4"
                            style="min-height: 500px;">
                            <i class="bi bi-image text-muted display-1"></i>
                        </div>
                    @endif
                </div>
            </div>
            <div class="col-md-6">
                <nav aria-label="breadcrumb" class="mb-4">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-decoration-none">Home</a></li>
                        @if($product->category)
                            <li class="breadcrumb-item">
                                <a href="{{ route('home', ['category' => $product->category->slug]) }}"
                                    class="text-decoration-none">{{ $product->category->name }}</a>
                            </li>
                        @endif
                        <li class="breadcrumb-item active fw-bold text-primary">{{ $product->name }}</li>
                    </ol>
                </nav>

                <h1 class="display-3 fw-extrabold mb-3">{{ $product->name }}</h1>

                <div class="d-flex align-items-center mb-4 gap-3">
                    <h2 class="text-primary fw-extrabold mb-0">${{ number_format($product->price, 2) }}</h2>
                    <span
                        class="badge {{ $product->stock > 0 ? 'bg-success-subtle text-success border border-success' : 'bg-danger-subtle text-danger border border-danger' }} rounded-pill px-3 py-2">
                        {{ $product->stock > 0 ? '● In Stock (' . $product->stock . ')' : '○ Out of Stock' }}
                    </span>
                </div>

                <div class="mb-5 bg-white p-4 rounded-4 shadow-sm border border-light">
                    <h5 class="fw-bold mb-3 d-flex align-items-center">
                        <i class="bi bi-text-left me-2 text-primary"></i>Description
                    </h5>
                    <p class="text-muted mb-0 lh-lg">{{ $product->description }}</p>
                </div>

                @if($product->stock > 0)
                    <div class="d-grid gap-3 d-md-flex">
                        <a href="{{ route('cart.add', $product->id) }}"
                            class="btn btn-premium btn-xl px-5 py-4 rounded-4 fw-bold shadow-lg flex-fill">
                            <i class="bi bi-cart-plus-fill me-3 fs-4"></i>Add to Shopping Cart
                        </a>
                    </div>
                    <p class="text-muted small mt-4 text-center text-md-start">
                        <i class="bi bi-shield-check me-2"></i>Secure checkout & guaranteed satisfaction
                    </p>
                @endif
            </div>
        </div>
    </div>
@endsection