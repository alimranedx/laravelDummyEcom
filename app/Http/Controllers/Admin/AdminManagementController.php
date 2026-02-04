<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Enums\UserType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class AdminManagementController extends Controller
{
    public function index()
    {
        $admins = User::where('user_type', UserType::ADMIN)->latest()->paginate(10);
        return view('admin.admin-management.index', compact('admins'));
    }

    public function create()
    {
        return view('admin.admin-management.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $admin = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'user_type' => UserType::ADMIN,
        ]);

        $admin->assignRole('admin');

        return redirect()->route('admin.admin-management.index')
            ->with('success', 'Admin created successfully.');
    }

    public function edit(User $admin)
    {
        if ($admin->user_type !== UserType::ADMIN) {
            abort(404);
        }
        return view('admin.admin-management.edit', compact('admin'));
    }

    public function update(Request $request, User $admin)
    {
        if ($admin->user_type !== UserType::ADMIN) {
            abort(404);
        }

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $admin->id],
            'password' => ['nullable', 'confirmed', Rules\Password::defaults()],
        ]);

        $admin->name = $request->name;
        $admin->email = $request->email;
        if ($request->filled('password')) {
            $admin->password = Hash::make($request->password);
        }
        $admin->save();

        return redirect()->route('admin.admin-management.index')
            ->with('success', 'Admin updated successfully.');
    }

    public function destroy(User $admin)
    {
        if ($admin->user_type !== UserType::ADMIN) {
            abort(404);
        }
        $admin->delete();
        return redirect()->route('admin.admin-management.index')
            ->with('success', 'Admin deleted successfully.');
    }
}
