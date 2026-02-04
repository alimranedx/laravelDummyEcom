@extends('admin.layout')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Associate Permissions: {{ ucfirst($role->name) }}</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="{{ route('admin.role-permissions.index') }}" class="btn btn-sm btn-outline-secondary">
            <i class="bi bi-arrow-left"></i> Back to List
        </a>
    </div>
</div>

<div class="card shadow-sm">
    <div class="card-body">
        <form action="{{ route('admin.role-permissions.update', $role) }}" method="POST">
            @csrf
            @method('PUT')

            <h5 class="card-title mb-4">Select Permissions</h5>
            
            <div class="row">
                @foreach($permissions as $permission)
                    <div class="col-md-3 mb-3">
                        <div class="form-check card p-2 bg-light shadow-none border-0 h-100">
                            <input class="form-check-input ms-0" type="checkbox" name="permissions[]" value="{{ $permission->id }}" id="perm_{{ $permission->id }}"
                                {{ in_array($permission->id, $rolePermissions) ? 'checked' : '' }}>
                            <label class="form-check-label ms-1" for="perm_{{ $permission->id }}">
                                {{ $permission->name }}
                            </label>
                        </div>
                    </div>
                @endforeach
            </div>

            @if($role->name === 'admin')
                <div class="alert alert-warning mt-3">
                    <i class="bi bi-exclamation-triangle"></i> <strong>Note:</strong> While you can modify permissions for the 'admin' role, remember that Super Admins (type 127) bypass these checks entirely. Regular Admins will be restricted by these selections.
                </div>
            @endif

            <div class="mt-4 pt-3 border-top">
                <button type="submit" class="btn btn-primary px-4">
                    <i class="bi bi-save"></i> Save Association
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
