@extends('admin.layout')

@section('content')
    <div class="py-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="h3 mb-0 text-gray-800">Admin Management</h1>
                <p class="text-muted small mb-0">Manage system administrators and their access.</p>
            </div>
            <a href="{{ route('admin.admin-management.create') }}" class="btn btn-primary btn-sm shadow-sm transition">
                <i class="bi bi-plus-lg me-1"></i> Add New Admin
            </a>
        </div>

        <div class="card border-0 shadow-sm overflow-hidden">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light text-muted small text-uppercase">
                        <tr>
                            <th class="px-4 py-3 border-0" style="width: 80px;">#</th>
                            <th class="px-4 py-3 border-0">Administrator</th>
                            <th class="px-4 py-3 border-0">Email</th>
                            <th class="px-4 py-3 border-0">Joined Date</th>
                            <th class="px-4 py-3 border-0 text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="border-top-0">
                        @forelse($admins as $admin)
                            <tr>
                                <td class="px-4 py-3 text-muted">{{ $admin->id }}</td>
                                <td class="px-4 py-3">
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-circle me-3 bg-primary bg-opacity-10 text-primary fw-bold">
                                            {{ strtoupper(substr($admin->name, 0, 1)) }}
                                        </div>
                                        <span class="fw-semibold text-dark">{{ $admin->name }}</span>
                                    </div>
                                </td>
                                <td class="px-4 py-3 text-muted small">{{ $admin->email }}</td>
                                <td class="px-4 py-3">
                                    <span class="badge bg-light text-dark fw-normal border">
                                        {{ $admin->created_at->format('M d, Y') }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-end">
                                    <div class="btn-group shadow-sm">
                                        <a href="{{ route('admin.admin-management.edit', $admin) }}"
                                            class="btn btn-sm btn-white border" title="Edit">
                                            <i class="bi bi-pencil text-info"></i>
                                        </a>
                                        <form action="{{ route('admin.admin-management.destroy', $admin) }}" method="POST"
                                            class="d-inline"
                                            onsubmit="return confirm('Are you sure you want to delete this admin?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-white border" title="Delete">
                                                <i class="bi bi-trash text-danger"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-5 text-muted">
                                    <i class="bi bi-people display-4 d-block mb-3 opacity-25"></i>
                                    No administrative accounts found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($admins->hasPages())
                <div class="card-footer bg-white py-3 border-top-0">
                    {{ $admins->links() }}
                </div>
            @endif
        </div>
    </div>

    <style>
        .avatar-circle {
            width: 35px;
            height: 35px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.85rem;
        }

        .transition {
            transition: all 0.2s ease-in-out;
        }

        .transition:hover {
            transform: translateY(-1px);
        }

        .btn-white {
            background-color: #fff;
        }

        .btn-white:hover {
            background-color: #f8f9fa;
        }
    </style>
@endsection