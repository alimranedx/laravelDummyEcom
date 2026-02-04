@extends('admin.layout')

@section('content')
    <div class="row mb-3">
        <div class="col-12">
            <h1>Users</h1>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Type</th>
                            <th>Role</th>
                            <th>Joined</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($users as $user)
                            <tr>
                                <td>{{ $user->id }}</td>
                                <td>{{ $user->name }}</td>
                                <td>{{ $user->email }}</td>
                                <td>
                                    @php
                                        $typeClass = match ($user->user_type) {
                                            \App\Enums\UserType::SUPER_ADMIN => 'dark',
                                            \App\Enums\UserType::ADMIN => 'danger',
                                            \App\Enums\UserType::USER => 'success',
                                            default => 'secondary'
                                        };
                                    @endphp
                                    <span class="badge bg-{{ $typeClass }}">
                                        {{ $user->user_type->label() }}
                                    </span>
                                </td>
                                <td>
                                    <span class="badge bg-info text-dark">
                                        {{ $user->getRoleNames()->first() ?? 'None' }}
                                    </span>
                                </td>
                                <td>{{ $user->created_at->format('M d, Y') }}</td>
                                <td>
                                    @php
                                        $canEdit = auth()->user()->isSuperAdmin() || !$user->isAdmin();
                                    @endphp

                                    @if ($canEdit)
                                        <form action="{{ route('admin.users.updateRole', $user) }}" method="POST"
                                            class="d-flex gap-1">
                                            @csrf
                                            @method('PATCH')
                                            <select name="user_type" class="form-select form-select-sm"
                                                onchange="this.form.submit()">
                                                @foreach (\App\Enums\UserType::cases() as $type)
                                                    @if ($type !== \App\Enums\UserType::SUPER_ADMIN || auth()->user()->isSuperAdmin())
                                                        <option value="{{ $type->value }}" {{ $user->user_type === $type ? 'selected' : '' }}>
                                                            {{ $type->label() }}
                                                        </option>
                                                    @endif
                                                @endforeach
                                            </select>
                                            <select name="role" class="form-select form-select-sm" onchange="this.form.submit()">
                                                <option value="user" {{ $user->hasRole('user') ? 'selected' : '' }}>User
                                                    Role
                                                </option>
                                                <option value="admin" {{ $user->hasRole('admin') ? 'selected' : '' }}>Admin
                                                    Role
                                                </option>
                                            </select>
                                        </form>
                                    @else
                                        <span class="text-muted small">Access Restricted</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center">No users found</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            {{ $users->links() }}
        </div>
    </div>
@endsection