@extends('admin.layout')

@section('content')
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Admin User Role Assignment</h1>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th scope="col">Admin Name</th>
                            <th scope="col">Email</th>
                            <th scope="col">Current Roles</th>
                            <th scope="col" class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($users as $user)
                            <tr>
                                <td><span class="fw-bold">{{ $user->name }}</span></td>
                                <td>{{ $user->email }}</td>
                                <td>
                                    @forelse($user->roles as $role)
                                        <span class="badge bg-primary mb-1">{{ $role->name }}</span>
                                    @empty
                                        <span class="text-muted small">No roles assigned</span>
                                    @endforelse
                                </td>
                                <td class="text-end">
                                    <a href="{{ route('admin.admin-user-roles.edit', $user) }}"
                                        class="btn btn-sm btn-outline-primary">
                                        <i class="bi bi-person-gear"></i> Manage Roles
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center py-4 text-muted">No regular Admin accounts found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection