<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Module;
use App\Models\SubModule;
use App\Models\Page;
use Illuminate\Support\Facades\DB;

class AdminModuleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Clear existing data
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('role_pages')->truncate();
        DB::table('pages')->truncate();
        DB::table('sub_modules')->truncate();
        DB::table('modules')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $modules = [
            [
                'name' => 'E-Commerce', 'icon' => 'bi bi-shop', 'sequence' => 2, 'display_name' => 'E-Commerce',
                'sub_modules' => [
                    [
                        'name' => 'Brands', 'controller' => 'App\Http\Controllers\Admin\BrandController', 'icon' => 'bi bi-patch-check', 'sequence' => 1, 'method' => 'index', 'display_name' => 'Brands',
                        'pages' => $this->resourcePages('Brands')
                    ],
                    [
                        'name' => 'Categories', 'controller' => 'App\Http\Controllers\Admin\CategoryController', 'icon' => 'bi bi-folder', 'sequence' => 2, 'method' => 'index', 'display_name' => 'Categories',
                        'pages' => $this->resourcePages('Categories')
                    ],
                    [
                        'name' => 'Products', 'controller' => 'App\Http\Controllers\Admin\ProductController', 'icon' => 'bi bi-box', 'sequence' => 3, 'method' => 'index', 'display_name' => 'Products',
                        'pages' => $this->resourcePages('Products')
                    ],
                ]
            ],
            [
                'name' => 'Sales', 'icon' => 'bi bi-cart', 'sequence' => 3, 'display_name' => 'Sales',
                'sub_modules' => [
                    [
                        'name' => 'Orders', 'controller' => 'App\Http\Controllers\Admin\OrderController', 'icon' => 'bi bi-receipt', 'sequence' => 1, 'method' => 'index', 'display_name' => 'Orders',
                        'pages' => [
                            ['name' => 'List Orders', 'method' => 'index', 'type' => 2],
                            ['name' => 'View Order', 'method' => 'show', 'type' => 2],
                            ['name' => 'Update Status', 'method' => 'updateStatus', 'type' => 1],
                        ]
                    ]
                ]
            ],
            [
                'name' => 'User Management', 'icon' => 'bi bi-people-fill', 'sequence' => 4, 'display_name' => 'Users',
                'sub_modules' => [
                    [
                        'name' => 'Admins', 'controller' => 'App\Http\Controllers\Admin\AdminManagementController', 'icon' => 'bi bi-person-badge', 'sequence' => 1, 'method' => 'index', 'display_name' => 'Admins',
                        'pages' => $this->resourcePages('Admins')
                    ],
                    [
                        'name' => 'Regular Users', 'controller' => 'App\Http\Controllers\Admin\UserManagementController', 'icon' => 'bi bi-person', 'sequence' => 2, 'method' => 'index', 'display_name' => 'Users',
                        'pages' => $this->resourcePages('Users')
                    ],
                ]
            ],
            [
                'name' => 'RBAC', 'icon' => 'bi bi-shield-lock', 'sequence' => 5, 'display_name' => 'RBAC',
                'sub_modules' => [
                    [
                        'name' => 'Role', 'controller' => 'App\Http\Controllers\Admin\RoleController', 'icon' => 'bi bi-tags', 'sequence' => 1, 'method' => 'index', 'display_name' => 'Role',
                        'pages' => $this->resourcePages('Roles')
                    ],
                    [
                        'name' => 'Role Permission Association', 'controller' => 'App\Http\Controllers\Admin\RolePermissionAssociationController', 'icon' => 'bi bi-link-45deg', 'sequence' => 3, 'method' => 'index', 'display_name' => 'Role Permission Association',
                        'pages' => [
                            ['name' => 'List Associations', 'method' => 'index', 'type' => 2],
                            ['name' => 'Edit Association', 'method' => 'edit', 'type' => 2],
                            ['name' => 'Update Association', 'method' => 'update', 'type' => 3],
                        ]
                    ],
                    [
                        'name' => 'Admin User Role', 'controller' => 'App\Http\Controllers\Admin\AdminUserRoleController', 'icon' => 'bi bi-person-badge', 'sequence' => 4, 'method' => 'index', 'display_name' => 'Admin User Role',
                        'pages' => [
                            ['name' => 'List Admin Roles', 'method' => 'index', 'type' => 2],
                            ['name' => 'Edit Admin Role', 'method' => 'edit', 'type' => 2],
                            ['name' => 'Update Admin Role', 'method' => 'update', 'type' => 1],
                        ]
                    ],
                ]
            ],
            [
                'name' => 'Reports', 'icon' => 'bi bi-graph-up', 'sequence' => 6, 'display_name' => 'Report',
                'sub_modules' => [
                    [
                        'name' => 'Sale Report', 'controller' => 'App\Http\Controllers\Admin\SaleReportController', 'icon' => 'bi bi-file-earmark-bar-graph', 'sequence' => 1, 'method' => 'index', 'display_name' => 'Sale Report',
                        'pages' => [
                            ['name' => 'View Sale Report', 'method' => 'index', 'type' => 2],
                        ]
                    ]
                ]
            ],
            [
                'name' => 'Management', 'icon' => 'bi bi-sliders', 'sequence' => 7, 'display_name' => 'Management',
                'sub_modules' => [
                    [
                        'name' => 'Notification Management', 'controller' => 'App\Http\Controllers\Admin\NotificationSettingController', 'icon' => 'bi bi-bell', 'sequence' => 1, 'method' => 'index', 'display_name' => 'Notification Management',
                        'pages' => [
                            ['name' => 'View Notification Settings', 'method' => 'index', 'type' => 2],
                            ['name' => 'Update Notification Settings', 'method' => 'update', 'type' => 3],
                        ]
                    ]
                ]
            ],
        ];

        foreach ($modules as $m) {
            $module = Module::create([
                'name' => $m['name'],
                'icon' => $m['icon'],
                'sequence' => $m['sequence'],
                'display_name' => $m['display_name'],
            ]);

            foreach ($m['sub_modules'] as $sm) {
                $subModule = SubModule::create([
                    'module_id' => $module->id,
                    'name' => $sm['name'],
                    'controller_name' => $sm['controller'],
                    'icon' => $sm['icon'],
                    'sequence' => $sm['sequence'],
                    'default_method' => $sm['method'],
                    'display_name' => $sm['display_name'],
                ]);

                foreach ($sm['pages'] as $p) {
                    Page::create([
                        'module_id' => $module->id,
                        'sub_module_id' => $subModule->id,
                        'name' => $p['name'],
                        'method_name' => $p['method'],
                        'method_type' => $p['type'],
                    ]);
                }
            }
        }

        // Assign all pages to Super Admin role
        $superAdminRole = DB::table('roles')->where('name', 'Super Admin')->first();
        if ($superAdminRole) {
            $pages = Page::all();
            foreach ($pages as $page) {
                DB::table('role_pages')->insertOrIgnore([
                    'role_id' => $superAdminRole->id,
                    'page_id' => $page->id,
                ]);
            }
        } else {
            // Fallback: If Super Admin doesn't exist, create it or use Admin
            $adminRole = DB::table('roles')->where('name', 'Admin')->first();
            if ($adminRole) {
                $pages = Page::whereHas('module', function($q) {
                    $q->where('name', '!=', 'RBAC');
                })->get();
                foreach ($pages as $page) {
                    DB::table('role_pages')->insertOrIgnore([
                        'role_id' => $adminRole->id,
                        'page_id' => $page->id,
                    ]);
                }
            }
        }
    }

    private function resourcePages($resource)
    {
        return [
            ['name' => "List $resource", 'method' => 'index', 'type' => 2],
            ['name' => "Create $resource", 'method' => 'create', 'type' => 2],
            ['name' => "Store $resource", 'method' => 'store', 'type' => 1],
            ['name' => "Edit $resource", 'method' => 'edit', 'type' => 2],
            ['name' => "Update $resource", 'method' => 'update', 'type' => 3],
            ['name' => "Delete $resource", 'method' => 'destroy', 'type' => 4],
            ['name' => "View $resource", 'method' => 'show', 'type' => 2],
        ];
    }
}
