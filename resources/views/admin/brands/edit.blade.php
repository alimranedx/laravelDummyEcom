@extends('admin.layout')

@section('content')
    <div class="row mb-3">
        <div class="col-md-12">
            <h1>Edit Brand: {{ $brand->name }}</h1>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <form action="{{ route('admin.brands.update', $brand) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="mb-3">
                    <label for="name" class="form-label">Name</label>
                    <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror"
                        value="{{ old('name', $brand->name) }}" required>
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="logo" class="form-label">Logo</label>
                    <div class="mb-2" id="logo-preview-container">
                        <img id="logo-preview" src="{{ $brand->logo_url }}" alt="{{ $brand->name }}"
                            style="max-height: 100px; border-radius: 4px;">
                    </div>
                    <input type="file" name="logo" id="logo" class="form-control @error('logo') is-invalid @enderror"
                        onchange="previewImage(this, 'logo-preview', 'logo-preview-container')">
                    @error('logo')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                @push('scripts')
                    <script>
                        function previewImage(input, previewId, containerId) {
                            const preview = document.getElementById(previewId);
                            const container = document.getElementById(containerId);

                            if (input.files && input.files[0]) {
                                const reader = new FileReader();
                                reader.onload = function (e) {
                                    preview.src = e.target.result;
                                    container.classList.remove('d-none');
                                }
                                reader.readAsDataURL(input.files[0]);
                            }
                        }
                    </script>
                @endpush

                <div class="mb-3">
                    <label for="description" class="form-label">Description</label>
                    <textarea name="description" id="description"
                        class="form-control @error('description') is-invalid @enderror"
                        rows="4">{{ old('description', $brand->description) }}</textarea>
                    @error('description')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <button type="submit" class="btn btn-primary">Update Brand</button>
                <a href="{{ route('admin.brands.index') }}" class="btn btn-secondary">Cancel</a>
            </form>
        </div>
    </div>
@endsection