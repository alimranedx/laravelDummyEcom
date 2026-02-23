@extends('admin.layout')

@section('content')
    <div class="container-fluid py-4 animate-fade-in">
        <div class="d-flex justify-content-between align-items-center mb-5">
            <div>
                <h1 class="h3 fw-bold text-dark mb-1">Admin Role Assignment</h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 small">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}" class="text-decoration-none text-muted">Dashboard</a></li>
                        <li class="breadcrumb-item active text-primary fw-medium" aria-current="page">Admin Roles</li>
                    </ol>
                </nav>
            </div>
        </div>

        <div class="card border-0 shadow-sm rounded-4 overflow-hidden bg-white">
            <div class="card-header bg-white border-0 py-4 px-4">
                <h5 class="mb-0 fw-bold text-dark">Admin User Roles</h5>
                <p class="text-muted small mb-0 mt-1">Assign roles to admin users to control their permissions.</p>
            </div>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light text-muted small text-uppercase">
                        <tr>
                            <th class="px-4 py-3 border-0">Admin Name</th>
                            <th class="px-4 py-3 border-0">Email</th>
                            <th class="px-4 py-3 border-0">Current Roles</th>
                            <th class="px-4 py-3 border-0 text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="border-top-0">
                        @forelse($users as $user)
                            <tr class="transition-row">
                                <td class="px-4 py-4">
                                    <div class="d-flex align-items-center">
                                        <div class="me-3 bg-primary bg-opacity-10 text-primary fw-bold rounded-circle d-flex align-items-center justify-content-center" style="width:38px;height:38px;font-size:.9rem">
                                            {{ strtoupper(substr($user->name, 0, 1)) }}
                                        </div>
                                        <span class="fw-bold text-dark">{{ $user->name }}</span>
                                    </div>
                                </td>
                                <td class="px-4 py-4 text-muted small">{{ $user->email }}</td>
                                <td class="px-4 py-4">
                                    @forelse($user->roles as $role)
                                        <span class="badge bg-primary bg-opacity-10 text-primary rounded-pill px-3 me-1 fw-normal">{{ $role->name }}</span>
                                    @empty
                                        <span class="text-muted small fst-italic">No roles assigned</span>
                                    @endforelse
                                </td>
                                <td class="px-4 py-4 text-end">
                                    <a href="{{ route('admin.admin-user-role.edit', $user) }}"
                                        class="btn btn-sm btn-light p-2 rounded-3 border-0 shadow-sm d-inline-flex align-items-center gap-1 px-3">
                                        <i class="bi bi-person-gear text-primary fs-5"></i>
                                        <span class="small fw-medium text-dark">Manage</span>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="4" class="text-center py-5 text-muted">No admin accounts found.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @push('styles')
    <style>
        .transition-row { transition: background-color 0.2s; }
        .transition-row:hover { background-color: rgba(248,249,250,0.5); }
    </style>
    @endpush
@endsection