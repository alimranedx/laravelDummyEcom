@extends('admin.layout')

@section('content')
    <div class="container-fluid py-4 animate-fade-in">
        <!-- Page Header -->
        <div class="d-flex justify-content-between align-items-center mb-5">
            <div>
                <h1 class="h3 fw-bold text-dark mb-1">User Management</h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 small">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}" class="text-decoration-none text-muted">Dashboard</a></li>
                        <li class="breadcrumb-item active text-primary fw-medium" aria-current="page">Users</li>
                    </ol>
                </nav>
            </div>
            <a href="{{ route('admin.user-management.create') }}" class="btn btn-primary d-inline-flex align-items-center px-4 py-2 rounded-pill shadow-sm transition">
                <i class="bi bi-plus-lg me-2"></i>
                <span class="fw-semibold">Add New User</span>
            </a>
        </div>

        <!-- Quick Stats -->
        <div class="row g-4 mb-5">
            <div class="col-md-4">
                <div class="card border-0 shadow-sm rounded-4 p-3 bg-white">
                    <div class="d-flex align-items-center">
                        <div class="bg-success bg-opacity-10 p-3 rounded-4 me-3">
                            <i class="bi bi-people fs-3 text-success"></i>
                        </div>
                        <div>
                            <span class="text-muted small d-block">Total Customers</span>
                            <span class="h4 fw-bold mb-0 text-dark">{{ $users->total() }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content Card -->
        <div class="card border-0 shadow-sm rounded-4 overflow-hidden bg-white">
            <div class="card-header bg-white border-0 py-4 px-4 d-flex justify-content-between align-items-center">
                <h5 class="mb-0 fw-bold text-dark">Customer Accounts</h5>
                <div class="search-box position-relative" style="max-width: 300px;">
                    <i class="bi bi-search position-absolute top-50 start-0 translate-middle-y ms-3 text-muted"></i>
                    <input type="text" class="form-control rounded-pill ps-5 border-light bg-light" placeholder="Search customers...">
                </div>
            </div>
            
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light text-muted small text-uppercase">
                        <tr>
                            <th class="px-4 py-3 border-0">#ID</th>
                            <th class="px-4 py-3 border-0">Customer Details</th>
                            <th class="px-4 py-3 border-0">Joined On</th>
                            <th class="px-4 py-3 border-0 text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="border-top-0">
                        @forelse($users as $user)
                            <tr class="transition-row">
                                <td class="px-4 py-4 text-muted small">#{{ $user->id }}</td>
                                <td class="px-4 py-4">
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-circle me-3 bg-gradient-success text-white fw-bold shadow-sm" style="background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);">
                                            {{ strtoupper(substr($user->name, 0, 1)) }}
                                        </div>
                                        <div>
                                            <div class="fw-bold text-dark mb-0 fs-6">{{ $user->name }}</div>
                                            <div class="text-muted small">{{ $user->email }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-4 py-4">
                                    <div class="d-flex flex-column">
                                        <span class="text-dark fw-medium small">{{ $user->created_at->format('M d, Y') }}</span>
                                        <span class="text-muted smaller">{{ $user->created_at->diffForHumans() }}</span>
                                    </div>
                                </td>
                                <td class="px-4 py-4 text-end">
                                    <div class="btn-group gap-2">
                                        <a href="{{ route('admin.user-management.edit', $user) }}"
                                            class="btn btn-sm btn-light p-2 rounded-3 border-0 shadow-sm" title="Edit Customer">
                                            <i class="bi bi-pencil-square text-primary fs-5"></i>
                                        </a>
                                        <form action="{{ route('admin.user-management.destroy', $user) }}" method="POST"
                                            class="d-inline"
                                            onsubmit="return confirm('Are you sure you want to delete this customer account?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-light p-2 rounded-3 border-0 shadow-sm" title="Delete Account">
                                                <i class="bi bi-trash text-danger fs-5"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center py-5">
                                    <div class="empty-state py-5">
                                        <i class="bi bi-person-x display-1 text-muted opacity-25 mb-4"></i>
                                        <h4 class="fw-bold text-muted">No customers found</h4>
                                        <p class="text-muted small">Try adjusting your search or add a new customer.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            @if($users->hasPages())
                <div class="card-footer bg-white py-4 px-4 border-top">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="small text-muted">
                            Showing {{ $users->firstItem() }} to {{ $users->lastItem() }} of {{ $users->total() }} results
                        </div>
                        <div>
                            {{ $users->links() }}
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>

    @push('styles')
    <style>
        .smaller { font-size: 0.75rem; }
        .transition-row { transition: background-color 0.2s; }
        .transition-row:hover { background-color: rgba(248, 249, 250, 0.5); }
        .avatar-circle { width: 42px; height: 42px; font-size: 1rem; }
        .btn-light { background: #f8f9fa; }
        .btn-light:hover { background: #e9ecef; }
        
        .pagination { margin-bottom: 0; }
        .page-link { border: none; padding: 0.5rem 0.85rem; margin: 0 2px; border-radius: 8px !important; color: #6c757d; }
        .page-item.active .page-link { background-color: #4f46e5; box-shadow: 0 4px 10px rgba(79, 70, 229, 0.2); }
    </style>
    @endpush
@endsection