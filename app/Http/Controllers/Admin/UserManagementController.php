<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Enums\UserType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class UserManagementController extends Controller
{
    public function index()
    {
        $users = User::where('user_type', UserType::USER)->latest()->paginate(10);
        return view('admin.user-management.index', compact('users'));
    }

    public function create()
    {
        return view('admin.user-management.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'user_type' => UserType::USER,
        ]);

        $user->assignRole('user');

        return redirect()->route('admin.user-management.index')
            ->with('success', 'User created successfully.');
    }

    public function edit(User $user)
    {
        if ($user->user_type !== UserType::USER) {
            abort(404);
        }
        return view('admin.user-management.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        if ($user->user_type !== UserType::USER) {
            abort(404);
        }

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'password' => ['nullable', 'confirmed', Rules\Password::defaults()],
        ]);

        $user->name = $request->name;
        $user->email = $request->email;
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }
        $user->save();

        return redirect()->route('admin.user-management.index')
            ->with('success', 'User updated successfully.');
    }

    public function destroy(User $user)
    {
        if ($user->user_type !== UserType::USER) {
            abort(404);
        }
        $user->delete();
        return redirect()->route('admin.user-management.index')
            ->with('success', 'User deleted successfully.');
    }
}
