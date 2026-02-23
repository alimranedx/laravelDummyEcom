@extends('admin.layout')

@section('content')
    <div class="container-fluid py-4 animate-fade-in">
        <div class="d-flex justify-content-between align-items-center mb-5">
            <div>
                <h1 class="h3 fw-bold text-dark mb-1">Assign Admin Roles</h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 small">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}" class="text-decoration-none text-muted">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.admin-user-role.index') }}" class="text-decoration-none text-muted">Admin Roles</a></li>
                        <li class="breadcrumb-item active text-primary fw-medium" aria-current="page">{{ $user->name }}</li>
                    </ol>
                </nav>
            </div>
            <a href="{{ route('admin.admin-user-role.index') }}" class="btn btn-outline-secondary d-inline-flex align-items-center px-4 py-2 rounded-pill">
                <i class="bi bi-arrow-left me-2"></i><span class="fw-semibold">Back to List</span>
            </a>
        </div>

        <div class="row justify-content-center">
            <div class="col-xl-8">
                <div class="card border-0 shadow-sm rounded-4 overflow-hidden bg-white">
                    <div class="card-header bg-white border-bottom border-light py-4 px-4">
                        <div class="d-flex align-items-center">
                            <div class="bg-primary bg-opacity-10 text-primary fw-bold rounded-circle d-flex align-items-center justify-content-center me-3" style="width:48px;height:48px;font-size:1.2rem">
                                {{ strtoupper(substr($user->name, 0, 1)) }}
                            </div>
                            <div>
                                <h5 class="mb-0 fw-bold text-dark">{{ $user->name }}</h5>
                                <p class="text-muted small mb-0">{{ $user->email }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="card-body p-4 p-md-5">
                        <form action="{{ route('admin.admin-user-role.update', $user) }}" method="POST">
                            @csrf @method('PATCH')
                            <div class="mb-4">
                                <label class="form-label small fw-bold text-uppercase text-dark" style="letter-spacing:1px">Select Administrative Roles</label>
                                <div class="row g-3 mt-1">
                                    @foreach($roles as $role)
                                        <div class="col-md-6">
                                            <div class="form-check p-3 border rounded-3 bg-light h-100 d-flex align-items-center gap-2">
                                                <input class="form-check-input ms-0" type="checkbox" name="roles[]"
                                                    value="{{ $role->name }}" id="role_{{ $role->id }}" {{ in_array($role->name, $userRoles) ? 'checked' : '' }}>
                                                <label class="form-check-label fw-medium text-dark ms-1" for="role_{{ $role->id }}">
                                                    {{ ucfirst($role->name) }}
                                                </label>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                                <p class="text-info small mt-3 mb-0">
                                    <i class="bi bi-info-circle me-1"></i> These roles determine the specific permissions this admin will have.
                                </p>
                            </div>

                            <div class="mt-5 pt-3 border-top">
                                <button type="submit" class="btn btn-primary rounded-pill px-5 py-3 fw-bold w-100 shadow-lg">
                                    <i class="bi bi-shield-check me-2"></i>Save Role Assignments
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection