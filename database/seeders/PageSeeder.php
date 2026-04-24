<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //

        DB::table('pages')->insert([

            ['id' => 1, 'module_id' => 1, 'sub_module_id' => 1, 'name' => 'List Brands', 'method_name' => 'index', 'method_type' => 2, 'created_at' => Carbon::parse('2026-02-23 09:52:53'), 'updated_at' => Carbon::parse('2026-02-23 09:52:53')],
            ['id' => 2, 'module_id' => 1, 'sub_module_id' => 1, 'name' => 'Create Brands', 'method_name' => 'create', 'method_type' => 2, 'created_at' => Carbon::parse('2026-02-23 09:52:53'), 'updated_at' => Carbon::parse('2026-02-23 09:52:53')],
            ['id' => 3, 'module_id' => 1, 'sub_module_id' => 1, 'name' => 'Store Brands', 'method_name' => 'store', 'method_type' => 1, 'created_at' => Carbon::parse('2026-02-23 09:52:53'), 'updated_at' => Carbon::parse('2026-02-23 09:52:53')],
            ['id' => 4, 'module_id' => 1, 'sub_module_id' => 1, 'name' => 'Edit Brands', 'method_name' => 'edit', 'method_type' => 2, 'created_at' => Carbon::parse('2026-02-23 09:52:53'), 'updated_at' => Carbon::parse('2026-02-23 09:52:53')],
            ['id' => 5, 'module_id' => 1, 'sub_module_id' => 1, 'name' => 'Update Brands', 'method_name' => 'update', 'method_type' => 3, 'created_at' => Carbon::parse('2026-02-23 09:52:53'), 'updated_at' => Carbon::parse('2026-02-23 09:52:53')],
            ['id' => 6, 'module_id' => 1, 'sub_module_id' => 1, 'name' => 'Delete Brands', 'method_name' => 'destroy', 'method_type' => 4, 'created_at' => Carbon::parse('2026-02-23 09:52:53'), 'updated_at' => Carbon::parse('2026-02-23 09:52:53')],
            ['id' => 7, 'module_id' => 1, 'sub_module_id' => 1, 'name' => 'View Brands', 'method_name' => 'show', 'method_type' => 2, 'created_at' => Carbon::parse('2026-02-23 09:52:53'), 'updated_at' => Carbon::parse('2026-02-23 09:52:53')],

            ['id' => 8, 'module_id' => 1, 'sub_module_id' => 2, 'name' => 'List Categories', 'method_name' => 'index', 'method_type' => 2, 'created_at' => Carbon::parse('2026-02-23 09:52:53'), 'updated_at' => Carbon::parse('2026-02-23 09:52:53')],
            ['id' => 9, 'module_id' => 1, 'sub_module_id' => 2, 'name' => 'Create Categories', 'method_name' => 'create', 'method_type' => 2, 'created_at' => Carbon::parse('2026-02-23 09:52:53'), 'updated_at' => Carbon::parse('2026-02-23 09:52:53')],
            ['id' => 10, 'module_id' => 1, 'sub_module_id' => 2, 'name' => 'Store Categories', 'method_name' => 'store', 'method_type' => 1, 'created_at' => Carbon::parse('2026-02-23 09:52:53'), 'updated_at' => Carbon::parse('2026-02-23 09:52:53')],
            ['id' => 11, 'module_id' => 1, 'sub_module_id' => 2, 'name' => 'Edit Categories', 'method_name' => 'edit', 'method_type' => 2, 'created_at' => Carbon::parse('2026-02-23 09:52:53'), 'updated_at' => Carbon::parse('2026-02-23 09:52:53')],
            ['id' => 12, 'module_id' => 1, 'sub_module_id' => 2, 'name' => 'Update Categories', 'method_name' => 'update', 'method_type' => 3, 'created_at' => Carbon::parse('2026-02-23 09:52:53'), 'updated_at' => Carbon::parse('2026-02-23 09:52:53')],
            ['id' => 13, 'module_id' => 1, 'sub_module_id' => 2, 'name' => 'Delete Categories', 'method_name' => 'destroy', 'method_type' => 4, 'created_at' => Carbon::parse('2026-02-23 09:52:53'), 'updated_at' => Carbon::parse('2026-02-23 09:52:53')],
            ['id' => 14, 'module_id' => 1, 'sub_module_id' => 2, 'name' => 'View Categories', 'method_name' => 'show', 'method_type' => 2, 'created_at' => Carbon::parse('2026-02-23 09:52:53'), 'updated_at' => Carbon::parse('2026-02-23 09:52:53')],

            ['id' => 15, 'module_id' => 1, 'sub_module_id' => 3, 'name' => 'List Products', 'method_name' => 'index', 'method_type' => 2, 'created_at' => Carbon::parse('2026-02-23 09:52:53'), 'updated_at' => Carbon::parse('2026-02-23 09:52:53')],
            ['id' => 16, 'module_id' => 1, 'sub_module_id' => 3, 'name' => 'Create Products', 'method_name' => 'create', 'method_type' => 2, 'created_at' => Carbon::parse('2026-02-23 09:52:53'), 'updated_at' => Carbon::parse('2026-02-23 09:52:53')],
            ['id' => 17, 'module_id' => 1, 'sub_module_id' => 3, 'name' => 'Store Products', 'method_name' => 'store', 'method_type' => 1, 'created_at' => Carbon::parse('2026-02-23 09:52:53'), 'updated_at' => Carbon::parse('2026-02-23 09:52:53')],
            ['id' => 18, 'module_id' => 1, 'sub_module_id' => 3, 'name' => 'Edit Products', 'method_name' => 'edit', 'method_type' => 2, 'created_at' => Carbon::parse('2026-02-23 09:52:53'), 'updated_at' => Carbon::parse('2026-02-23 09:52:53')],
            ['id' => 19, 'module_id' => 1, 'sub_module_id' => 3, 'name' => 'Update Products', 'method_name' => 'update', 'method_type' => 3, 'created_at' => Carbon::parse('2026-02-23 09:52:53'), 'updated_at' => Carbon::parse('2026-02-23 09:52:53')],
            ['id' => 20, 'module_id' => 1, 'sub_module_id' => 3, 'name' => 'Delete Products', 'method_name' => 'destroy', 'method_type' => 4, 'created_at' => Carbon::parse('2026-02-23 09:52:53'), 'updated_at' => Carbon::parse('2026-02-23 09:52:53')],
            ['id' => 21, 'module_id' => 1, 'sub_module_id' => 3, 'name' => 'View Products', 'method_name' => 'show', 'method_type' => 2, 'created_at' => Carbon::parse('2026-02-23 09:52:53'), 'updated_at' => Carbon::parse('2026-02-23 09:52:53')],

            ['id' => 22, 'module_id' => 2, 'sub_module_id' => 4, 'name' => 'List Orders', 'method_name' => 'index', 'method_type' => 2, 'created_at' => Carbon::parse('2026-02-23 09:52:53'), 'updated_at' => Carbon::parse('2026-02-23 09:52:53')],
            ['id' => 23, 'module_id' => 2, 'sub_module_id' => 4, 'name' => 'View Order', 'method_name' => 'show', 'method_type' => 2, 'created_at' => Carbon::parse('2026-02-23 09:52:53'), 'updated_at' => Carbon::parse('2026-02-23 09:52:53')],
            ['id' => 24, 'module_id' => 2, 'sub_module_id' => 4, 'name' => 'Update Status', 'method_name' => 'updateStatus', 'method_type' => 1, 'created_at' => Carbon::parse('2026-02-23 09:52:53'), 'updated_at' => Carbon::parse('2026-02-23 09:52:53')],

            ['id' => 25, 'module_id' => 3, 'sub_module_id' => 5, 'name' => 'List Admins', 'method_name' => 'index', 'method_type' => 2, 'created_at' => Carbon::parse('2026-02-23 09:52:53'), 'updated_at' => Carbon::parse('2026-02-23 09:52:53')],
            ['id' => 26, 'module_id' => 3, 'sub_module_id' => 5, 'name' => 'Create Admins', 'method_name' => 'create', 'method_type' => 2, 'created_at' => Carbon::parse('2026-02-23 09:52:53'), 'updated_at' => Carbon::parse('2026-02-23 09:52:53')],
            ['id' => 27, 'module_id' => 3, 'sub_module_id' => 5, 'name' => 'Store Admins', 'method_name' => 'store', 'method_type' => 1, 'created_at' => Carbon::parse('2026-02-23 09:52:53'), 'updated_at' => Carbon::parse('2026-02-23 09:52:53')],
            ['id' => 28, 'module_id' => 3, 'sub_module_id' => 5, 'name' => 'Edit Admins', 'method_name' => 'edit', 'method_type' => 2, 'created_at' => Carbon::parse('2026-02-23 09:52:53'), 'updated_at' => Carbon::parse('2026-02-23 09:52:53')],
            ['id' => 29, 'module_id' => 3, 'sub_module_id' => 5, 'name' => 'Update Admins', 'method_name' => 'update', 'method_type' => 3, 'created_at' => Carbon::parse('2026-02-23 09:52:53'), 'updated_at' => Carbon::parse('2026-02-23 09:52:53')],
            ['id' => 30, 'module_id' => 3, 'sub_module_id' => 5, 'name' => 'Delete Admins', 'method_name' => 'destroy', 'method_type' => 4, 'created_at' => Carbon::parse('2026-02-23 09:52:53'), 'updated_at' => Carbon::parse('2026-02-23 09:52:53')],
            ['id' => 31, 'module_id' => 3, 'sub_module_id' => 5, 'name' => 'View Admins', 'method_name' => 'show', 'method_type' => 2, 'created_at' => Carbon::parse('2026-02-23 09:52:53'), 'updated_at' => Carbon::parse('2026-02-23 09:52:53')],

            ['id' => 32, 'module_id' => 3, 'sub_module_id' => 6, 'name' => 'List Users', 'method_name' => 'index', 'method_type' => 2, 'created_at' => Carbon::parse('2026-02-23 09:52:53'), 'updated_at' => Carbon::parse('2026-02-23 09:52:53')],
            ['id' => 33, 'module_id' => 3, 'sub_module_id' => 6, 'name' => 'Create Users', 'method_name' => 'create', 'method_type' => 2, 'created_at' => Carbon::parse('2026-02-23 09:52:53'), 'updated_at' => Carbon::parse('2026-02-23 09:52:53')],
            ['id' => 34, 'module_id' => 3, 'sub_module_id' => 6, 'name' => 'Store Users', 'method_name' => 'store', 'method_type' => 1, 'created_at' => Carbon::parse('2026-02-23 09:52:53'), 'updated_at' => Carbon::parse('2026-02-23 09:52:53')],
            ['id' => 35, 'module_id' => 3, 'sub_module_id' => 6, 'name' => 'Edit Users', 'method_name' => 'edit', 'method_type' => 2, 'created_at' => Carbon::parse('2026-02-23 09:52:53'), 'updated_at' => Carbon::parse('2026-02-23 09:52:53')],
            ['id' => 36, 'module_id' => 3, 'sub_module_id' => 6, 'name' => 'Update Users', 'method_name' => 'update', 'method_type' => 3, 'created_at' => Carbon::parse('2026-02-23 09:52:53'), 'updated_at' => Carbon::parse('2026-02-23 09:52:53')],
            ['id' => 37, 'module_id' => 3, 'sub_module_id' => 6, 'name' => 'Delete Users', 'method_name' => 'destroy', 'method_type' => 4, 'created_at' => Carbon::parse('2026-02-23 09:52:53'), 'updated_at' => Carbon::parse('2026-02-23 09:52:53')],
            ['id' => 38, 'module_id' => 3, 'sub_module_id' => 6, 'name' => 'View Users', 'method_name' => 'show', 'method_type' => 2, 'created_at' => Carbon::parse('2026-02-23 09:52:53'), 'updated_at' => Carbon::parse('2026-02-23 09:52:53')],

            ['id' => 39, 'module_id' => 4, 'sub_module_id' => 7, 'name' => 'List Roles', 'method_name' => 'index', 'method_type' => 2, 'created_at' => Carbon::parse('2026-02-23 09:52:53'), 'updated_at' => Carbon::parse('2026-02-23 09:52:53')],
            ['id' => 40, 'module_id' => 4, 'sub_module_id' => 7, 'name' => 'Create Roles', 'method_name' => 'create', 'method_type' => 2, 'created_at' => Carbon::parse('2026-02-23 09:52:53'), 'updated_at' => Carbon::parse('2026-02-23 09:52:53')],
            ['id' => 41, 'module_id' => 4, 'sub_module_id' => 7, 'name' => 'Store Roles', 'method_name' => 'store', 'method_type' => 1, 'created_at' => Carbon::parse('2026-02-23 09:52:53'), 'updated_at' => Carbon::parse('2026-02-23 09:52:53')],
            ['id' => 42, 'module_id' => 4, 'sub_module_id' => 7, 'name' => 'Edit Roles', 'method_name' => 'edit', 'method_type' => 2, 'created_at' => Carbon::parse('2026-02-23 09:52:53'), 'updated_at' => Carbon::parse('2026-02-23 09:52:53')],
            ['id' => 43, 'module_id' => 4, 'sub_module_id' => 7, 'name' => 'Update Roles', 'method_name' => 'update', 'method_type' => 3, 'created_at' => Carbon::parse('2026-02-23 09:52:53'), 'updated_at' => Carbon::parse('2026-02-23 09:52:53')],
            ['id' => 44, 'module_id' => 4, 'sub_module_id' => 7, 'name' => 'Delete Roles', 'method_name' => 'destroy', 'method_type' => 4, 'created_at' => Carbon::parse('2026-02-23 09:52:53'), 'updated_at' => Carbon::parse('2026-02-23 09:52:53')],
            ['id' => 45, 'module_id' => 4, 'sub_module_id' => 7, 'name' => 'View Roles', 'method_name' => 'show', 'method_type' => 2, 'created_at' => Carbon::parse('2026-02-23 09:52:53'), 'updated_at' => Carbon::parse('2026-02-23 09:52:53')],

            ['id' => 46, 'module_id' => 4, 'sub_module_id' => 8, 'name' => 'List Associations', 'method_name' => 'index', 'method_type' => 2, 'created_at' => Carbon::parse('2026-02-23 09:52:53'), 'updated_at' => Carbon::parse('2026-02-23 09:52:53')],
            ['id' => 47, 'module_id' => 4, 'sub_module_id' => 8, 'name' => 'Edit Association', 'method_name' => 'edit', 'method_type' => 2, 'created_at' => Carbon::parse('2026-02-23 09:52:53'), 'updated_at' => Carbon::parse('2026-02-23 09:52:53')],
            ['id' => 48, 'module_id' => 4, 'sub_module_id' => 8, 'name' => 'Update Association', 'method_name' => 'update', 'method_type' => 3, 'created_at' => Carbon::parse('2026-02-23 09:52:54'), 'updated_at' => Carbon::parse('2026-02-23 09:52:54')],

            ['id' => 49, 'module_id' => 4, 'sub_module_id' => 9, 'name' => 'List Admin Roles', 'method_name' => 'index', 'method_type' => 2, 'created_at' => Carbon::parse('2026-02-23 09:52:54'), 'updated_at' => Carbon::parse('2026-02-23 09:52:54')],
            ['id' => 50, 'module_id' => 4, 'sub_module_id' => 9, 'name' => 'Edit Admin Role', 'method_name' => 'edit', 'method_type' => 2, 'created_at' => Carbon::parse('2026-02-23 09:52:54'), 'updated_at' => Carbon::parse('2026-02-23 09:52:54')],
            ['id' => 51, 'module_id' => 4, 'sub_module_id' => 9, 'name' => 'Update Admin Role', 'method_name' => 'update', 'method_type' => 1, 'created_at' => Carbon::parse('2026-02-23 09:52:54'), 'updated_at' => Carbon::parse('2026-02-23 09:52:54')],

            ['id' => 52, 'module_id' => 5, 'sub_module_id' => 10, 'name' => 'View Sale Report', 'method_name' => 'index', 'method_type' => 2, 'created_at' => Carbon::parse('2026-02-23 09:52:54'), 'updated_at' => Carbon::parse('2026-02-23 09:52:54')],

            ['id' => 53, 'module_id' => 6, 'sub_module_id' => 11, 'name' => 'View Notification Settings', 'method_name' => 'index', 'method_type' => 2, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id' => 54, 'module_id' => 6, 'sub_module_id' => 11, 'name' => 'Update Notification Settings', 'method_name' => 'update', 'method_type' => 3, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],

        ]);
    }
}
