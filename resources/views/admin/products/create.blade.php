@extends('admin.layout')

@section('content')
    <div class="container-fluid py-4 animate-fade-in">
        <div class="d-flex justify-content-between align-items-center mb-5">
            <div>
                <h1 class="h3 fw-bold text-dark mb-1">Add New Product</h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 small">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}" class="text-decoration-none text-muted">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.products.index') }}" class="text-decoration-none text-muted">Products</a></li>
                        <li class="breadcrumb-item active text-primary fw-medium" aria-current="page">New Product</li>
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
                        <h5 class="mb-0 fw-bold text-dark">Product Details</h5>
                        <p class="text-muted small mb-0 mt-1">Fill in the information below to add a new product to the catalog.</p>
                    </div>
                    <div class="card-body p-4 p-md-5">
                        <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="row g-4">
                                <!-- Brand & Category Row -->
                                <div class="col-md-6">
                                    <label for="brand_id" class="form-label small fw-bold text-uppercase text-dark" style="letter-spacing:1px">Brand</label>
                                    <select class="form-select bg-light border-0 @error('brand_id') is-invalid @enderror" id="brand_id" name="brand_id" onchange="loadCategories(this.value)">
                                        <option value="">Select Brand</option>
                                        @foreach($brands as $brand)
                                            <option value="{{ $brand->id }}" {{ old('brand_id') == $brand->id ? 'selected' : '' }}>{{ $brand->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('brand_id')<div class="invalid-feedback d-block mt-1">{{ $message }}</div>@enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="category_id" class="form-label small fw-bold text-uppercase text-dark" style="letter-spacing:1px">Category</label>
                                    <select class="form-select bg-light border-0 @error('category_id') is-invalid @enderror" id="category_id" name="category_id">
                                        <option value="">Select Category</option>
                                        @foreach($categories as $category)
                                            @if(old('brand_id') && $category->brand_id == old('brand_id'))
                                                <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                                            @elseif(!old('brand_id'))
                                                <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
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
                                        <input type="text" class="form-control bg-light border-0 @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" placeholder="e.g. Galaxy S24 Ultra" required>
                                    </div>
                                    @error('name')<div class="invalid-feedback d-block mt-1">{{ $message }}</div>@enderror
                                </div>

                                <!-- Description -->
                                <div class="col-12">
                                    <label for="description" class="form-label small fw-bold text-uppercase text-dark" style="letter-spacing:1px">Description</label>
                                    <textarea class="form-control bg-light border-0 @error('description') is-invalid @enderror" id="description" name="description" rows="3" placeholder="Product description...">{{ old('description') }}</textarea>
                                    @error('description')<div class="invalid-feedback d-block mt-1">{{ $message }}</div>@enderror
                                </div>

                                <!-- Price & Stock -->
                                <div class="col-md-6">
                                    <label for="price" class="form-label small fw-bold text-uppercase text-dark" style="letter-spacing:1px">Price ($)</label>
                                    <div class="input-group input-group-lg">
                                        <span class="input-group-text bg-light border-0 text-muted px-4"><i class="bi bi-currency-dollar"></i></span>
                                        <input type="number" step="0.01" class="form-control bg-light border-0 @error('price') is-invalid @enderror" id="price" name="price" value="{{ old('price') }}" placeholder="0.00" required>
                                    </div>
                                    @error('price')<div class="invalid-feedback d-block mt-1">{{ $message }}</div>@enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="stock" class="form-label small fw-bold text-uppercase text-dark" style="letter-spacing:1px">Stock Quantity</label>
                                    <div class="input-group input-group-lg">
                                        <span class="input-group-text bg-light border-0 text-muted px-4"><i class="bi bi-layers"></i></span>
                                        <input type="number" class="form-control bg-light border-0 @error('stock') is-invalid @enderror" id="stock" name="stock" value="{{ old('stock') }}" placeholder="0" required>
                                    </div>
                                    @error('stock')<div class="invalid-feedback d-block mt-1">{{ $message }}</div>@enderror
                                </div>

                                <!-- Product Image -->
                                <div class="col-12">
                                    <label for="images" class="form-label small fw-bold text-uppercase text-dark" style="letter-spacing:1px">Product Images</label>
                                    <input type="file" class="form-control bg-light border-0 @error('images.*') is-invalid @enderror" id="images" name="images[]" accept=".jpg,.jpeg,.png,.gif" multiple>
                                    <small class="text-muted mt-2 d-block">You can upload up to 10 images. Allowed formats: JPG, JPEG, PNG, GIF.</small>
                                    @error('images.*')<div class="invalid-feedback d-block mt-1">{{ $message }}</div>@enderror
                                    
                                    <!-- Image Preview Container -->
                                    <div id="image-preview-container" class="mt-3 d-flex flex-wrap gap-3"></div>
                                </div>

                                <div class="col-12 mt-4">
                                    <button type="submit" class="btn btn-primary rounded-pill px-5 py-3 fw-bold w-100 shadow-lg">
                                        <i class="bi bi-check-lg me-2"></i>Create Product
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
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
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

    $(document).ready(function() {
        $('#images').on('change', function(e) {
            $('#image-preview-container').empty();
            let files = e.target.files;
            
            if (files) {
                $.each(files, function(index, file) {
                    let reader = new FileReader();
                    
                    reader.onload = function(e) {
                        let imgElement = $('<img>').attr('src', e.target.result)
                                                  .addClass('img-thumbnail')
                                                  .css({'width': '100px', 'height': '100px', 'object-fit': 'cover'});
                        $('#image-preview-container').append(imgElement);
                    }
                    
                    reader.readAsDataURL(file);
                });
            }
        });
    });
</script>
@endpush
@endsection