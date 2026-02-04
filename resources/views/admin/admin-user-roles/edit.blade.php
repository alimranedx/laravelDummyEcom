@extends('admin.layout')

@section('content')
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Assign Roles to Admin: {{ $user->name }}</h1>
        <div class="btn-toolbar mb-2 mb-md-0">
            <a href="{{ route('admin.admin-user-roles.index') }}" class="btn btn-sm btn-outline-secondary">
                <i class="bi bi-arrow-left"></i> Back to List
            </a>
        </div>
    </div>

    <div class="card shadow-sm col-md-8">
        <div class="card-body">
            <form action="{{ route('admin.admin-user-roles.update', $user) }}" method="POST">
                @csrf
                @method('PATCH')

                <div class="mb-4">
                    <label class="form-label fw-bold">Select Administrative Roles</label>
                    <div class="row">
                        @foreach($roles as $role)
                            <div class="col-md-6 mb-2">
                                <div class="form-check p-2 border rounded">
                                    <input class="form-check-input ms-0" type="checkbox" name="roles[]"
                                        value="{{ $role->name }}" id="role_{{ $role->id }}" {{ in_array($role->name, $userRoles) ? 'checked' : '' }}>
                                    <label class="form-check-label ms-1" for="role_{{ $role->id }}">
                                        {{ ucfirst($role->name) }}
                                    </label>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <div class="form-text mt-2 text-info">
                        <i class="bi bi-info-circle"></i> These roles determine the specific permissions this Admin user
                        will have.
                    </div>
                </div>

                <div class="mt-4 pt-3 border-top">
                    <button type="submit" class="btn btn-primary px-4">
                        <i class="bi bi-save"></i> Save Role Assignments
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection