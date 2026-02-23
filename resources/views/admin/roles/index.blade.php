@extends('admin.layout')

@section('content')
    <div class="container-fluid py-4 animate-fade-in">
        <div class="d-flex justify-content-between align-items-center mb-5">
            <div>
                <h1 class="h3 fw-bold text-dark mb-1">Role Management</h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 small">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}" class="text-decoration-none text-muted">Dashboard</a></li>
                        <li class="breadcrumb-item active text-primary fw-medium" aria-current="page">Roles</li>
                    </ol>
                </nav>
            </div>
            <a href="{{ route('admin.roles.create') }}" class="btn btn-primary d-inline-flex align-items-center px-4 py-2 rounded-pill shadow-sm">
                <i class="bi bi-plus-lg me-2"></i><span class="fw-semibold">Create New Role</span>
            </a>
        </div>

        <div class="card border-0 shadow-sm rounded-4 overflow-hidden bg-white">
            <div class="card-header bg-white border-0 py-4 px-4">
                <h5 class="mb-0 fw-bold text-dark">System Roles</h5>
            </div>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light text-muted small text-uppercase">
                        <tr>
                            <th class="px-4 py-3 border-0">#ID</th>
                            <th class="px-4 py-3 border-0">Role Name</th>
                            <th class="px-4 py-3 border-0">Created</th>
                            <th class="px-4 py-3 border-0 text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="border-top-0">
                        @foreach($roles as $role)
                            <tr class="transition-row">
                                <td class="px-4 py-4 text-muted small">{{ $role->id }}</td>
                                <td class="px-4 py-4">
                                    <span class="fw-bold text-dark">{{ ucfirst($role->name) }}</span>
                                    @if($role->name === 'admin' || $role->name === 'user')
                                        <span class="badge bg-secondary ms-2 rounded-pill fw-normal">System</span>
                                    @endif
                                </td>
                                <td class="px-4 py-4 text-muted small">{{ $role->created_at->format('M d, Y') }}</td>
                                <td class="px-4 py-4 text-end">
                                    @if($role->name !== 'admin' && $role->name !== 'user')
                                        <div class="btn-group gap-2">
                                            <a href="{{ route('admin.roles.edit', $role) }}" class="btn btn-sm btn-light p-2 rounded-3 border-0 shadow-sm">
                                                <i class="bi bi-pencil-square text-primary fs-5"></i>
                                            </a>
                                            <form action="{{ route('admin.roles.destroy', $role) }}" method="POST" class="d-inline"
                                                onsubmit="return confirm('Delete this role?')">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-light p-2 rounded-3 border-0 shadow-sm">
                                                    <i class="bi bi-trash text-danger fs-5"></i>
                                                </button>
                                            </form>
                                        </div>
                                    @else
                                        <span class="text-muted small fst-italic">Protected</span>
                                    @endif
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