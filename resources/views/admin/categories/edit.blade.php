@extends('admin.layout')

@section('content')
    <div class="container-fluid py-4 animate-fade-in">
        <div class="d-flex justify-content-between align-items-center mb-5">
            <div>
                <h1 class="h3 fw-bold text-dark mb-1">Edit Category</h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 small">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}" class="text-decoration-none text-muted">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.categories.index') }}" class="text-decoration-none text-muted">Categories</a></li>
                        <li class="breadcrumb-item active text-primary fw-medium" aria-current="page">{{ $category->name }}</li>
                    </ol>
                </nav>
            </div>
            <a href="{{ route('admin.categories.index') }}" class="btn btn-outline-secondary d-inline-flex align-items-center px-4 py-2 rounded-pill">
                <i class="bi bi-arrow-left me-2"></i><span class="fw-semibold">Back to Categories</span>
            </a>
        </div>

        <div class="row justify-content-center">
            <div class="col-xl-8">
                <div class="card border-0 shadow-sm rounded-4 overflow-hidden bg-white">
                    <div class="card-header bg-white border-bottom border-light py-4 px-4">
                        <h5 class="mb-0 fw-bold text-dark">Update Category Details</h5>
                    </div>
                    <div class="card-body p-4 p-md-5">
                        <form action="{{ route('admin.categories.update', $category) }}" method="POST">
                            @csrf @method('PUT')
                            <div class="row g-4">
                                <div class="col-12">
                                    <label for="brand_id" class="form-label small fw-bold text-uppercase text-dark" style="letter-spacing:1px">Brand</label>
                                    <select class="form-select bg-light border-0 @error('brand_id') is-invalid @enderror" id="brand_id" name="brand_id">
                                        <option value="">Select Brand</option>
                                        @foreach($brands as $brand)
                                            <option value="{{ $brand->id }}" {{ old('brand_id', $category->brand_id) == $brand->id ? 'selected' : '' }}>{{ $brand->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('brand_id')<div class="invalid-feedback d-block mt-1">{{ $message }}</div>@enderror
                                </div>
                                <div class="col-12">
                                    <label for="name" class="form-label small fw-bold text-uppercase text-dark" style="letter-spacing:1px">Category Name</label>
                                    <div class="input-group input-group-lg">
                                        <span class="input-group-text bg-light border-0 text-muted px-4"><i class="bi bi-grid"></i></span>
                                        <input type="text" class="form-control bg-light border-0 @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $category->name) }}" required>
                                    </div>
                                    @error('name')<div class="invalid-feedback d-block mt-1">{{ $message }}</div>@enderror
                                </div>
                                <div class="col-12">
                                    <label for="description" class="form-label small fw-bold text-uppercase text-dark" style="letter-spacing:1px">Description</label>
                                    <textarea class="form-control bg-light border-0 @error('description') is-invalid @enderror" id="description" name="description" rows="3">{{ old('description', $category->description) }}</textarea>
                                    @error('description')<div class="invalid-feedback d-block mt-1">{{ $message }}</div>@enderror
                                </div>
                                <div class="col-12 mt-4">
                                    <button type="submit" class="btn btn-dark rounded-pill px-5 py-3 fw-bold w-100 shadow-lg">
                                        <i class="bi bi-check-circle-fill me-2 text-info"></i>Update Category
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection