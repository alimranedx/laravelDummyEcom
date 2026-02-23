@extends('admin.layout')

@section('content')
    <div class="container-fluid py-4 animate-fade-in">
        <div class="d-flex justify-content-between align-items-center mb-5">
            <div>
                <h1 class="h3 fw-bold text-dark mb-1">Edit Brand</h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 small">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}" class="text-decoration-none text-muted">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.brands.index') }}" class="text-decoration-none text-muted">Brands</a></li>
                        <li class="breadcrumb-item active text-primary fw-medium" aria-current="page">{{ $brand->name }}</li>
                    </ol>
                </nav>
            </div>
            <a href="{{ route('admin.brands.index') }}" class="btn btn-outline-secondary d-inline-flex align-items-center px-4 py-2 rounded-pill">
                <i class="bi bi-arrow-left me-2"></i><span class="fw-semibold">Back to Brands</span>
            </a>
        </div>

        <div class="row justify-content-center">
            <div class="col-xl-8">
                <div class="card border-0 shadow-sm rounded-4 overflow-hidden bg-white">
                    <div class="card-header bg-white border-bottom border-light py-4 px-4">
                        <h5 class="mb-0 fw-bold text-dark">Update Brand Details</h5>
                        <p class="text-muted small mb-0 mt-1">Edit the information for this brand.</p>
                    </div>
                    <div class="card-body p-4 p-md-5">
                        <form action="{{ route('admin.brands.update', $brand) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            <div class="row g-4">
                                <div class="col-12">
                                    <label for="name" class="form-label small fw-bold text-uppercase text-dark" style="letter-spacing:1px">Brand Name</label>
                                    <div class="input-group input-group-lg">
                                        <span class="input-group-text bg-light border-0 text-muted px-4"><i class="bi bi-tag"></i></span>
                                        <input type="text" name="name" id="name" class="form-control bg-light border-0 @error('name') is-invalid @enderror"
                                            value="{{ old('name', $brand->name) }}" required>
                                    </div>
                                    @error('name')<div class="invalid-feedback d-block mt-1">{{ $message }}</div>@enderror
                                </div>

                                <div class="col-12">
                                    <label for="logo" class="form-label small fw-bold text-uppercase text-dark" style="letter-spacing:1px">Brand Logo</label>
                                    <div id="logo-preview-container" class="mb-3 text-center">
                                        <img id="logo-preview" src="{{ $brand->logo_url }}" alt="{{ $brand->name }}" class="img-thumbnail rounded-4 shadow-sm" style="max-height:120px">
                                    </div>
                                    <input type="file" name="logo" id="logo" class="form-control bg-light border-0 @error('logo') is-invalid @enderror"
                                        onchange="previewImage(this,'logo-preview','logo-preview-container')">
                                    @error('logo')<div class="invalid-feedback d-block mt-1">{{ $message }}</div>@enderror
                                </div>

                                <div class="col-12">
                                    <label for="description" class="form-label small fw-bold text-uppercase text-dark" style="letter-spacing:1px">Description</label>
                                    <textarea name="description" id="description"
                                        class="form-control bg-light border-0 @error('description') is-invalid @enderror"
                                        rows="4">{{ old('description', $brand->description) }}</textarea>
                                    @error('description')<div class="invalid-feedback d-block mt-1">{{ $message }}</div>@enderror
                                </div>

                                <div class="col-12 mt-4">
                                    <button type="submit" class="btn btn-dark rounded-pill px-5 py-3 fw-bold w-100 shadow-lg">
                                        <i class="bi bi-check-circle-fill me-2 text-info"></i>Update Brand
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
        function previewImage(input, previewId, containerId) {
            const preview = document.getElementById(previewId);
            const container = document.getElementById(containerId);
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = e => { preview.src = e.target.result; container.classList.remove('d-none'); };
                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>
    @endpush
@endsection