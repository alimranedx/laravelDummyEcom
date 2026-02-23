@extends('layouts.frontend')

@section('content')
    <!-- Hero Slider Section -->
    @if($sliderProducts->count() > 0)
        <section id="heroSlider" class="carousel slide hero-slider shadow-lg animate-fade-in" data-bs-ride="carousel">
            <div class="carousel-indicators">
                @foreach($sliderProducts as $index => $sp)
                    <button type="button" data-bs-target="#heroSlider" data-bs-slide-to="{{ $index }}" class="{{ $index == 0 ? 'active' : '' }}" aria-current="true"></button>
                @endforeach
            </div>
            <div class="carousel-inner">
                @foreach($sliderProducts as $index => $sp)
                    <div class="carousel-item {{ $index == 0 ? 'active' : '' }}">
                        <img src="{{ asset('storage/' . $sp->image_path) }}" class="d-block w-100" alt="{{ $sp->name }}">
                        <div class="slider-overlay"></div>
                        <div class="carousel-caption d-none d-md-block animate-fade-in">
                            <h1 class="display-3 fw-extrabold">{{ $sp->name }}</h1>
                            <p class="lead">{{ Str::limit(strip_tags($sp->description), 120) }}</p>
                            <div class="d-flex gap-3">
                                <a href="{{ route('product.show', $sp->slug) }}" class="btn btn-light btn-lg px-5 py-3 rounded-pill fw-bold shadow-sm">
                                    Shop Now <i class="bi bi-arrow-right-short"></i>
                                </a>
                                <span class="btn btn-premium btn-lg px-4 py-3 rounded-pill fw-bold border-0" style="background: rgba(255,255,255,0.1); backdrop-filter: blur(10px);">
                                    ${{ number_format($sp->price, 2) }}
                                </span>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            @if($sliderProducts->count() > 1)
                <button class="carousel-control-prev" type="button" data-bs-target="#heroSlider" data-bs-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Previous</span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#heroSlider" data-bs-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Next</span>
                </button>
            @endif
        </section>
    @else
        <!-- Fallback Hero Section -->
        <section class="hero-gradient text-center animate-fade-in shadow-lg">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-lg-8">
                        <h1 class="display-3 fw-extrabold mb-4">Discover Your Future Style</h1>
                        <p class="lead mb-5 opacity-75 fs-4">Explore our curated collection of premium products designed for the modern individual.</p>
                        <a href="#products" class="btn btn-light btn-lg px-5 py-3 rounded-pill fw-bold shadow-sm">
                            Shop Now <i class="bi bi-arrow-right-short"></i>
                        </a>
                    </div>
                </div>
            </div>
        </section>
    @endif

    <div class="container" id="products">
        <div class="row">
            <!-- Sidebar Filters -->
            <div class="col-md-3">
                <div class="sidebar-section shadow-sm mb-4 animate-fade-in">
                    <h5 class="fw-bold mb-4">Categories</h5>
                    <div class="list-group list-group-flush border-0">
                        <a href="{{ route('home') }}"
                            class="list-group-item list-group-item-action {{ !request('category') ? 'active text-white' : '' }}">
                            <i class="bi bi-grid-fill me-2"></i>All Products
                        </a>
                        @foreach($categories as $category)
                            <a href="{{ route('home', ['category' => $category->slug]) }}"
                                class="list-group-item list-group-item-action {{ request('category') == $category->slug ? 'active text-white' : '' }}">
                                <i class="bi bi-chevron-right me-2 small"></i>{{ $category->name }}
                            </a>
                        @endforeach
                    </div>
                </div>

                <div class="sidebar-section shadow-sm animate-fade-in" style="animation-delay: 0.1s;">
                    <h5 class="fw-bold mb-4">Refine by Price</h5>
                    <form action="{{ route('home') }}" method="GET">
                        @if(request('category'))
                            <input type="hidden" name="category" value="{{ request('category') }}">
                        @endif
                        <div class="mb-4">
                            <label class="form-label small fw-bold text-uppercase text-muted">Price Range</label>
                            <div class="input-group mb-2 shadow-sm">
                                <span class="input-group-text bg-white border-end-0"><i
                                        class="bi bi-currency-dollar"></i></span>
                                <input type="number" name="min_price" class="form-control border-start-0 ps-0"
                                    placeholder="Min" value="{{ request('min_price') }}">
                            </div>
                            <div class="input-group shadow-sm">
                                <span class="input-group-text bg-white border-end-0"><i
                                        class="bi bi-currency-dollar"></i></span>
                                <input type="number" name="max_price" class="form-control border-start-0 ps-0"
                                    placeholder="Max" value="{{ request('max_price') }}">
                            </div>
                        </div>
                        <button type="submit" class="btn btn-premium w-100 rounded-pill mb-3">Apply Filter</button>
                        <a href="{{ route('home') }}" class="btn btn-link w-100 text-muted text-decoration-none small">Clear
                            All</a>
                    </form>
                </div>
            </div>

            <!-- Product Grid -->
            <div class="col-md-9">
                <div class="d-flex justify-content-between align-items-center mb-5 flex-wrap gap-3 animate-fade-in">
                    <h2 class="fw-extrabold mb-0">Our Collection</h2>
                    <div class="dropdown">
                        <button
                            class="btn btn-white bg-white shadow-sm border rounded-pill px-4 py-2 dropdown-toggle fw-bold"
                            type="button" data-bs-toggle="dropdown">
                            <i class="bi bi-sort-down me-2 text-primary"></i>
                            {{ request('sort') == 'price_asc' ? 'Lower Price First' : (request('sort') == 'price_desc' ? 'Highest Price First' : 'Newly Added') }}
                        </button>
                        <ul class="dropdown-menu border-0 shadow-lg mt-2">
                            <li><a class="dropdown-item py-2"
                                    href="{{ request()->fullUrlWithQuery(['sort' => 'newest']) }}"><i
                                        class="bi bi-clock me-2"></i>Newly Added</a></li>
                            <li><a class="dropdown-item py-2"
                                    href="{{ request()->fullUrlWithQuery(['sort' => 'price_asc']) }}"><i
                                        class="bi bi-sort-numeric-down me-2"></i>Lower Price First</a></li>
                            <li><a class="dropdown-item py-2"
                                    href="{{ request()->fullUrlWithQuery(['sort' => 'price_desc']) }}"><i
                                        class="bi bi-sort-numeric-up-alt me-2"></i>Highest Price First</a></li>
                        </ul>
                    </div>
                </div>

                <div class="row g-4">
                    @forelse($products as $index => $product)
                        <div class="col-md-4 animate-fade-in" style="animation-delay: {{ 0.1 * ($index % 3) }}s;">
                            <div class="premium-card h-100 shadow-sm border-0">
                                <div class="card-img-wrapper">
                                    <a href="{{ route('product.show', $product->slug) }}">
                                        @if($product->image_path)
                                            <img src="{{ asset('storage/' . $product->image_path) }}" alt="{{ $product->name }}">
                                        @else
                                            <div class="bg-light d-flex align-items-center justify-content-center h-100 w-100">
                                                <i class="bi bi-image text-muted fs-1"></i>
                                            </div>
                                        @endif
                                    </a>
                                    @if($product->stock <= 0)
                                        <span class="position-absolute top-0 end-0 m-3 badge bg-danger rounded-pill shadow-sm">Sold
                                            Out</span>
                                    @endif
                                </div>
                                <div class="card-body p-4 d-flex flex-column">
                                    <span
                                        class="badge bg-primary-subtle text-primary border border-primary-subtle rounded-pill mb-2 align-self-start small">
                                        {{ $product->category->name ?? 'Uncategorized' }}
                                    </span>
                                    <h5 class="card-title fw-bold mb-3">
                                        <a href="{{ route('product.show', $product->slug) }}"
                                            class="text-decoration-none text-dark">{{ $product->name }}</a>
                                    </h5>
                                    <div class="mt-auto d-flex justify-content-between align-items-center">
                                        <span
                                            class="fs-4 fw-extrabold text-primary">${{ number_format($product->price, 2) }}</span>
                                        @if($product->stock > 0)
                                            <a href="{{ route('cart.add', $product->id) }}"
                                                class="btn btn-premium rounded-circle p-2 shadow-sm d-flex align-items-center justify-content-center"
                                                style="width: 40px; height: 40px;">
                                                <i class="bi bi-cart-plus fs-5"></i>
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-12 py-5 text-center">
                            <div class="bg-white p-5 rounded-4 shadow-sm">
                                <i class="bi bi-search display-1 text-muted opacity-25 mb-4"></i>
                                <h3 class="fw-bold">No results found</h3>
                                <p class="text-muted">Try adjusting your filters to find what you're looking for.</p>
                            </div>
                        </div>
                    @endforelse
                </div>

                <div class="mt-5 d-flex justify-content-center">
                    {{ $products->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection