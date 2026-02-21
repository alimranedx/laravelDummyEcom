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
        $roles = Role::where('name', '!=', 'user')->get();
        foreach ($roles as $role) {
            $role->pages_count = \Illuminate\Support\Facades\DB::table('role_pages')
                ->where('role_id', $role->id)
                ->count();
        }
        return view('admin.role-permissions.index', compact('roles'));
    }

    public function edit(Role $role)
    {
        $modules = \App\Models\Module::with('subModules.pages')->orderBy('sequence')->get();
        $rolePages = \Illuminate\Support\Facades\DB::table('role_pages')
            ->where('role_id', $role->id)
            ->pluck('page_id')
            ->toArray();
            
        return view('admin.role-permissions.edit', compact('role', 'modules', 'rolePages'));
    }

    public function update(Request $request, Role $role)
    {
        $request->validate([
            'pages' => 'array',
            'pages.*' => 'exists:pages,id',
        ]);

        \Illuminate\Support\Facades\DB::transaction(function () use ($request, $role) {
            \Illuminate\Support\Facades\DB::table('role_pages')
                ->where('role_id', $role->id)
                ->delete();

            if ($request->has('pages')) {
                $data = collect($request->pages)->map(function($pageId) use ($role) {
                    return [
                        'role_id' => $role->id,
                        'page_id' => $pageId,
                    ];
                })->toArray();
                
                \Illuminate\Support\Facades\DB::table('role_pages')->insert($data);
            }
        });

        return redirect()->route('admin.role-permissions.index')
            ->with('success', 'Role page permissions associated successfully.');
    }
}
