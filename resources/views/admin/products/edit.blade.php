@extends('admin.layout')

@section('content')
    <div class="container-fluid py-4 animate-fade-in">
        <div class="d-flex justify-content-between align-items-center mb-5">
            <div>
                <h1 class="h3 fw-bold text-dark mb-1">Edit Product</h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 small">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}" class="text-decoration-none text-muted">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.products.index') }}" class="text-decoration-none text-muted">Products</a></li>
                        <li class="breadcrumb-item active text-primary fw-medium" aria-current="page">{{ $product->name }}</li>
                    </ol>
                </nav>
            </div>
            <a href="{{ route('admin.products.index') }}" class="btn btn-outline-secondary d-inline-flex align-items-center px-4 py-2 rounded-pill">
                <i class="bi bi-arrow-left me-2"></i><span class="fw-semibold">Back to Products</span>
            </a>
        </div>

        <div class="row justify-content-center">
            <div class="col-xl-9">
                <div class="card border-0 shadow-sm rounded-4 overflow-hidden bg-white">
                    <div class="card-header bg-white border-bottom border-light py-4 px-4">
                        <h5 class="mb-0 fw-bold text-dark">Update Product Details</h5>
                        <p class="text-muted small mb-0 mt-1">Edit the information for this product in the catalog.</p>
                    </div>
                    <div class="card-body p-4 p-md-5">
                        <form action="{{ route('admin.products.update', $product) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            <div class="row g-4">
                                <!-- Brand & Category Row -->
                                <div class="col-md-6">
                                    <label for="brand_id" class="form-label small fw-bold text-uppercase text-dark" style="letter-spacing:1px">Brand</label>
                                    <select class="form-select bg-light border-0 @error('brand_id') is-invalid @enderror" id="brand_id" name="brand_id" onchange="loadCategories(this.value)">
                                        <option value="">Select Brand</option>
                                        @foreach($brands as $brand)
                                            <option value="{{ $brand->id }}" {{ old('brand_id', $product->brand_id) == $brand->id ? 'selected' : '' }}>{{ $brand->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('brand_id')<div class="invalid-feedback d-block mt-1">{{ $message }}</div>@enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="category_id" class="form-label small fw-bold text-uppercase text-dark" style="letter-spacing:1px">Category</label>
                                    <select class="form-select bg-light border-0 @error('category_id') is-invalid @enderror" id="category_id" name="category_id">
                                        <option value="">Select Category</option>
                                        @foreach($categories as $category)
                                            @php $selectedBrandId = old('brand_id', $product->brand_id); @endphp
                                            @if($selectedBrandId && $category->brand_id == $selectedBrandId)
                                                <option value="{{ $category->id }}" {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                    @error('category_id')<div class="invalid-feedback d-block mt-1">{{ $message }}</div>@enderror
                                </div>

                                <!-- Product Name -->
                                <div class="col-12">
                                    <label for="name" class="form-label small fw-bold text-uppercase text-dark" style="letter-spacing:1px">Product Name</label>
                                    <div class="input-group input-group-lg">
                                        <span class="input-group-text bg-light border-0 text-muted px-4"><i class="bi bi-box-seam"></i></span>
                                        <input type="text" class="form-control bg-light border-0 @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $product->name) }}" required>
                                    </div>
                                    @error('name')<div class="invalid-feedback d-block mt-1">{{ $message }}</div>@enderror
                                </div>

                                <!-- Description -->
                                <div class="col-12">
                                    <label for="description" class="form-label small fw-bold text-uppercase text-dark" style="letter-spacing:1px">Description</label>
                                    <textarea class="form-control bg-light border-0 @error('description') is-invalid @enderror" id="description" name="description" rows="3">{{ old('description', $product->description) }}</textarea>
                                    @error('description')<div class="invalid-feedback d-block mt-1">{{ $message }}</div>@enderror
                                </div>

                                <!-- Price & Stock -->
                                <div class="col-md-6">
                                    <label for="price" class="form-label small fw-bold text-uppercase text-dark" style="letter-spacing:1px">Price ($)</label>
                                    <div class="input-group input-group-lg">
                                        <span class="input-group-text bg-light border-0 text-muted px-4"><i class="bi bi-currency-dollar"></i></span>
                                        <input type="number" step="0.01" class="form-control bg-light border-0 @error('price') is-invalid @enderror" id="price" name="price" value="{{ old('price', $product->price) }}" required>
                                    </div>
                                    @error('price')<div class="invalid-feedback d-block mt-1">{{ $message }}</div>@enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="stock" class="form-label small fw-bold text-uppercase text-dark" style="letter-spacing:1px">Stock Quantity</label>
                                    <div class="input-group input-group-lg">
                                        <span class="input-group-text bg-light border-0 text-muted px-4"><i class="bi bi-layers"></i></span>
                                        <input type="number" class="form-control bg-light border-0 @error('stock') is-invalid @enderror" id="stock" name="stock" value="{{ old('stock', $product->stock) }}" required>
                                    </div>
                                    @error('stock')<div class="invalid-feedback d-block mt-1">{{ $message }}</div>@enderror
                                </div>

                                <!-- Product Image -->
                                <div class="col-12">
                                    <label class="form-label small fw-bold text-uppercase text-dark" style="letter-spacing:1px">Product Image</label>
                                    @if($product->image_path)
                                        <div class="mb-3">
                                            <div class="rounded-3 overflow-hidden shadow-sm border d-inline-block">
                                                <img src="{{ asset('storage/' . $product->image_path) }}" alt="{{ $product->name }}" style="max-height:120px; object-fit:cover">
                                            </div>
                                        </div>
                                    @endif
                                    <input type="file" class="form-control bg-light border-0 @error('image_path') is-invalid @enderror" id="image_path" name="image_path" accept="image/*">
                                    @error('image_path')<div class="invalid-feedback d-block mt-1">{{ $message }}</div>@enderror
                                </div>

                                <div class="col-12 mt-4">
                                    <button type="submit" class="btn btn-dark rounded-pill px-5 py-3 fw-bold w-100 shadow-lg">
                                        <i class="bi bi-check-circle-fill me-2 text-info"></i>Update Product
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

@push('scripts')
<script>
    function loadCategories(brandId) {
        const categorySelect = document.getElementById('category_id');
        categorySelect.innerHTML = '<option value="">Select Category</option>';
        if (!brandId) return;
        fetch(`/admin/brands/${brandId}/categories`)
            .then(response => response.json())
            .then(data => {
                data.forEach(category => {
                    const option = document.createElement('option');
                    option.value = category.id;
                    option.textContent = category.name;
                    categorySelect.appendChild(option);
                });
            })
            .catch(error => console.error('Error fetching categories:', error));
    }
</script>
@endpush
@endsection