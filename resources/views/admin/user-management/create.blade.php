@extends('admin.layout')

@section('content')
    <div class="py-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="h3 mb-0 text-gray-800">Create New User</h1>
                <p class="text-muted small mb-0">Register a new regular user account.</p>
            </div>
            <a href="{{ route('admin.user-management.index') }}"
                class="btn btn-outline-secondary btn-sm shadow-sm transition">
                <i class="bi bi-arrow-left me-1"></i> Back to List
            </a>
        </div>

        <div class="row">
            <div class="col-md-06">
                <div class="card border-0 shadow-sm border-top border-primary border-4">
                    <div class="card-body p-4">
                        <form action="{{ route('admin.user-management.store') }}" method="POST">
                            @csrf
                            <div class="mb-4">
                                <label for="name" class="form-label fw-bold">Full Name</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0"><i
                                            class="bi bi-person text-muted"></i></span>
                                    <input type="text"
                                        class="form-control bg-light border-start-0 @error('name') is-invalid @enderror"
                                        id="name" name="name" value="{{ old('name') }}" placeholder="Enter user's name"
                                        required>
                                </div>
                                @error('name')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label for="email" class="form-label fw-bold">Email Address</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0"><i
                                            class="bi bi-envelope text-muted"></i></span>
                                    <input type="email"
                                        class="form-control bg-light border-start-0 @error('email') is-invalid @enderror"
                                        id="email" name="email" value="{{ old('email') }}" placeholder="user@example.com"
                                        required>
                                </div>
                                @error('email')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="row mb-4">
                                <div class="col-md-6 mb-3 mb-md-0">
                                    <label for="password" class="form-label fw-bold">Password</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-end-0"><i
                                                class="bi bi-lock text-muted"></i></span>
                                        <input type="password"
                                            class="form-control bg-light border-start-0 @error('password') is-invalid @enderror"
                                            id="password" name="password" placeholder="Min 8 characters" required>
                                    </div>
                                    @error('password')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="password_confirmation" class="form-label fw-bold">Confirm</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-end-0"><i
                                                class="bi bi-shield-lock text-muted"></i></span>
                                        <input type="password" class="form-control bg-light border-start-0"
                                            id="password_confirmation" name="password_confirmation"
                                            placeholder="Repeat password" required>
                                    </div>
                                </div>
                            </div>

                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-primary py-2 fw-bold shadow-sm transition">
                                    <i class="bi bi-person-plus me-1"></i> Register User Account
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="card mt-4 border-0 shadow-sm bg-light">
                    <div class="card-body small text-muted">
                        <i class="bi bi-info-circle me-1"></i> This account will be created with the <strong>user</strong>
                        role and regular customer permissions.
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .transition {
            transition: all 0.2s ease-in-out;
        }

        .transition:hover {
            transform: translateY(-1px);
        }

        .input-group-text {
            color: #6c757d;
        }
    </style>
@endsection