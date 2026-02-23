<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class AdminUserRoleController extends Controller
{
    public function index()
    {
        // Only list Admin type users (user_type = 1)
        $users = User::where('user_type', \App\Enums\UserType::ADMIN)->with('roles')->get();
        return view('admin.admin-user-role.index', compact('users'));
    }

    public function edit(User $user)
    {
        if (!$user->isAdmin() || $user->isSuperAdmin()) {
            abort(403, 'This interface is for managing regular Admin roles only.');
        }

        $roles = Role::where('name', '!=', 'user')->get();
        $userRoles = $user->roles->pluck('name')->toArray();

        return view('admin.admin-user-role.edit', compact('user', 'roles', 'userRoles'));
    }

    public function update(Request $request, User $user)
    {
        if (!$user->isAdmin() || $user->isSuperAdmin()) {
            abort(403, 'This interface is for managing regular Admin roles only.');
        }

        $request->validate([
            'roles' => 'required|array',
            'roles.*' => 'exists:roles,name',
        ]);

        $user->syncRoles($request->roles);

        return redirect()->route('admin.admin-user-role.index')
            ->with('success', 'Admin user roles updated successfully.');
    }
}
