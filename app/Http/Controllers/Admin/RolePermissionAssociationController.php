<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolePermissionAssociationController extends Controller
{
    public function index()
    {
        $roles = Role::where('name', '!=', 'user')->with('permissions')->get();
        return view('admin.role-permissions.index', compact('roles'));
    }

    public function edit(Role $role)
    {
        $permissions = Permission::all();
        $rolePermissions = $role->permissions->pluck('id')->toArray();
        return view('admin.role-permissions.edit', compact('role', 'permissions', 'rolePermissions'));
    }

    public function update(Request $request, Role $role)
    {
        $request->validate([
            'permissions' => 'array',
            'permissions.*' => 'exists:permissions,id',
        ]);

        $permissionIds = collect($request->permissions)->map(fn($id) => (int) $id)->toArray();
        $role->syncPermissions($permissionIds);

        return redirect()->route('admin.role-permissions.index')
            ->with('success', 'Role permissions associated successfully.');
    }
}
