@extends('admin.layout')

@section('content')
    <div class="container-fluid py-4 animate-fade-in">
        <!-- Page Header -->
        <div class="d-flex justify-content-between align-items-center mb-5">
            <div>
                <h1 class="h3 fw-bold text-dark mb-1">Create User</h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 small">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}" class="text-decoration-none text-muted">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.user-management.index') }}" class="text-decoration-none text-muted">Users</a></li>
                        <li class="breadcrumb-item active text-primary fw-medium" aria-current="page">New Account</li>
                    </ol>
                </nav>
            </div>
            <a href="{{ route('admin.user-management.index') }}" class="btn btn-outline-secondary d-inline-flex align-items-center px-4 py-2 rounded-pill transition">
                <i class="bi bi-arrow-left me-2"></i>
                <span class="fw-semibold">Back to List</span>
            </a>
        </div>

        <div class="row justify-content-center">
            <div class="col-xl-8">
                <div class="card border-0 shadow-sm rounded-4 overflow-hidden bg-white">
                    <div class="card-header bg-white border-0 py-4 px-4 border-bottom border-light">
                        <h5 class="mb-0 fw-bold text-dark">Customer Details</h5>
                        <p class="text-muted small mb-0 mt-1">Fill in the information to create a new customer account.</p>
                    </div>
                    <div class="card-body p-4 p-md-5">
                        <form action="{{ route('admin.user-management.store') }}" method="POST">
                            @csrf
                            <div class="row g-4">
                                <!-- Name -->
                                <div class="col-12">
                                    <label for="name" class="form-label small fw-bold text-dark text-uppercase letter-spacing-1">Full Name</label>
                                    <div class="input-group input-group-lg">
                                        <span class="input-group-text bg-light border-0 text-muted px-4"><i class="bi bi-person"></i></span>
                                        <input type="text" class="form-control bg-light border-0 @error('name') is-invalid @enderror" 
                                               id="name" name="name" value="{{ old('name') }}" placeholder="e.g. John Doe" required>
                                    </div>
                                    @error('name')
                                        <div class="invalid-feedback d-block mt-2">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Email -->
                                <div class="col-12">
                                    <label for="email" class="form-label small fw-bold text-dark text-uppercase letter-spacing-1">Email Address</label>
                                    <div class="input-group input-group-lg">
                                        <span class="input-group-text bg-light border-0 text-muted px-4"><i class="bi bi-envelope"></i></span>
                                        <input type="email" class="form-control bg-light border-0 @error('email') is-invalid @enderror" 
                                               id="email" name="email" value="{{ old('email') }}" placeholder="user@example.com" required>
                                    </div>
                                    @error('email')
                                        <div class="invalid-feedback d-block mt-2">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Password Row -->
                                <div class="col-md-6">
                                    <label for="password" class="form-label small fw-bold text-dark text-uppercase letter-spacing-1">Password</label>
                                    <div class="input-group input-group-lg">
                                        <span class="input-group-text bg-light border-0 text-muted px-4"><i class="bi bi-lock"></i></span>
                                        <input type="password" class="form-control bg-light border-0 @error('password') is-invalid @enderror" 
                                               id="password" name="password" placeholder="••••••••" required>
                                    </div>
                                    @error('password')
                                        <div class="invalid-feedback d-block mt-2">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label for="password_confirmation" class="form-label small fw-bold text-dark text-uppercase letter-spacing-1">Confirm Password</label>
                                    <div class="input-group input-group-lg">
                                        <span class="input-group-text bg-light border-0 text-muted px-4"><i class="bi bi-shield-lock"></i></span>
                                        <input type="password" class="form-control bg-light border-0" 
                                               id="password_confirmation" name="password_confirmation" placeholder="••••••••" required>
                                    </div>
                                </div>

                                <!-- Submit Button -->
                                <div class="col-12 mt-5">
                                    <button type="submit" class="btn btn-primary d-inline-flex align-items-center justify-content-center px-5 py-3 rounded-pill shadow-lg w-100 fw-bold transition h5 mb-0">
                                        <i class="bi bi-person-plus-fill me-2"></i>
                                        Register New Customer
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('styles')
    <style>
        .letter-spacing-1 { letter-spacing: 1px; }
        .form-control:focus { background-color: #fff !important; box-shadow: 0 4px 12px rgba(0,0,0,0.05); }
        .input-group-text i { font-size: 1.1rem; }
        .btn-primary { background-color: #4f46e5; border: none; }
        .btn-primary:hover { background-color: #4338ca; transform: translateY(-2px); box-shadow: 0 8px 20px rgba(79, 70, 229, 0.3); }
    </style>
    @endpush
@endsection