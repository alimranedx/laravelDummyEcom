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
        $query = \App\Models\Module::with('subModules.pages')->orderBy('sequence');
        
        // Only allow RBAC permissions to be assigned to Super Admin role
        if ($role->name !== 'Super Admin') {
            $query->where('name', '!=', 'RBAC');
        }
        
        $modules = $query->get();
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
                $pageIds = $request->pages;
                
                // Final safeguard: Filter out RBAC pages if not Super Admin role
                if ($role->name !== 'Super Admin') {
                    $rbacPageIds = \App\Models\Page::whereHas('module', function($q) {
                        $q->where('name', 'RBAC');
                    })->pluck('id')->toArray();
                    
                    $pageIds = array_diff($pageIds, $rbacPageIds);
                }

                $data = collect($pageIds)->map(function($pageId) use ($role) {
                    return [
                        'role_id' => $role->id,
                        'page_id' => $pageId,
                    ];
                })->toArray();
                
                if (!empty($data)) {
                    \Illuminate\Support\Facades\DB::table('role_pages')->insert($data);
                }
            }
        });

        return redirect()->route('admin.role-permissions.index')
            ->with('success', 'Role page permissions associated successfully.');
    }
}
