@extends('admin.layout')

@section('content')
    <div class="container-fluid py-4 animate-fade-in">
        <div class="d-flex justify-content-between align-items-center mb-5">
            <div>
                <h1 class="h3 fw-bold text-dark mb-1">Role Permissions</h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 small">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}" class="text-decoration-none text-muted">Dashboard</a></li>
                        <li class="breadcrumb-item active text-primary fw-medium" aria-current="page">Role Permissions</li>
                    </ol>
                </nav>
            </div>
        </div>

        <div class="card border-0 shadow-sm rounded-4 overflow-hidden bg-white">
            <div class="card-header bg-white border-0 py-4 px-4">
                <h5 class="mb-0 fw-bold text-dark">Role Permission Associations</h5>
                <p class="text-muted small mb-0 mt-1">Manage which pages each role can access.</p>
            </div>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light text-muted small text-uppercase">
                        <tr>
                            <th class="px-4 py-3 border-0">Role</th>
                            <th class="px-4 py-3 border-0">Assigned Permissions</th>
                            <th class="px-4 py-3 border-0 text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="border-top-0">
                        @foreach($roles as $role)
                            <tr class="transition-row">
                                <td class="px-4 py-4">
                                    <span class="fw-bold text-dark">{{ ucfirst($role->name) }}</span>
                                </td>
                                <td class="px-4 py-4">
                                    @if($role->pages_count > 0)
                                        <span class="badge bg-success rounded-pill px-3 py-2 fw-normal">{{ $role->pages_count }} Pages Assigned</span>
                                    @else
                                        <span class="text-muted small fst-italic">No pages assigned</span>
                                    @endif
                                </td>
                                <td class="px-4 py-4 text-end">
                                    <a href="{{ route('admin.role-permission-association.edit', $role) }}"
                                        class="btn btn-sm btn-light p-2 rounded-3 border-0 shadow-sm d-inline-flex align-items-center gap-1 px-3">
                                        <i class="bi bi-link-45deg text-primary fs-5"></i>
                                        <span class="small fw-medium text-dark">Manage</span>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
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