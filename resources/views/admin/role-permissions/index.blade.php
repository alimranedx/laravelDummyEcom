@extends('admin.layout')

@section('content')
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Role Permission Association</h1>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th scope="col" style="width: 150px;">Role</th>
                            <th scope="col">Assigned Permissions</th>
                            <th scope="col" class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($roles as $role)
                            <tr>
                                <td>
                                    <span class="fw-bold">{{ ucfirst($role->name) }}</span>
                                </td>
                                <td>
                                    @if($role->pages_count > 0)
                                        <span class="badge bg-success">{{ $role->pages_count }} Pages Assigned</span>
                                    @else
                                        <span class="text-muted small italic">No pages assigned</span>
                                    @endif
                                </td>
                                <td class="text-end">
                                    <a href="{{ route('admin.role-permission-association.edit', $role) }}"
                                        class="btn btn-sm btn-outline-primary">
                                        <i class="bi bi-link-45deg"></i> Manage Associations
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection