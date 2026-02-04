<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    public function index()
    {
        // Hide Super Admins from the list for everyone
        $users = User::with('roles')
            ->where('user_type', '!=', \App\Enums\UserType::SUPER_ADMIN)
            ->latest()
            ->paginate(15);

        return view('admin.users.index', compact('users'));
    }

    public function updateRole(Request $request, User $user)
    {
        $currentUser = auth()->user();

        // 1. If target is Super Admin, block everyone (even Super Admins shouldn't demote themselves via this UI typically, but specifically block Admins)
        if ($user->isSuperAdmin()) {
            abort(403, 'Super Admin accounts cannot be modified here.');
        }

        // 2. If current user is just an Admin (not Super Admin)
        if (!$currentUser->isSuperAdmin()) {
            // Can't modify other Admins
            if ($user->isAdmin()) {
                abort(403, 'Admins cannot modify other Admin accounts.');
            }

            // Can't promote someone to Super Admin
            if ($request->user_type == \App\Enums\UserType::SUPER_ADMIN->value) {
                abort(403, 'You do not have permission to promote users to Super Admin.');
            }
        }

        $validated = $request->validate([
            'role' => 'required|in:admin,user',
            'user_type' => 'required|in:1,2,127',
        ]);

        $user->user_type = $validated['user_type'];
        $user->syncRoles([$validated['role']]);
        $user->save();

        return redirect()->route('admin.users.index')
            ->with('success', 'User updated successfully.');
    }
}
