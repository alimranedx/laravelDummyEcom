<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ModuleSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('modules')->insert([
            [
                'id' => 1,
                'name' => 'E-Commerce',
                'icon' => 'bi bi-shop',
                'sequence' => 2,
                'display_name' => 'E-Commerce',
                'created_at' => Carbon::parse('2026-02-23 09:52:53'),
                'updated_at' => Carbon::parse('2026-02-23 09:52:53'),
            ],
            [
                'id' => 2,
                'name' => 'Sales',
                'icon' => 'bi bi-cart',
                'sequence' => 3,
                'display_name' => 'Sales',
                'created_at' => Carbon::parse('2026-02-23 09:52:53'),
                'updated_at' => Carbon::parse('2026-02-23 09:52:53'),
            ],
            [
                'id' => 3,
                'name' => 'User Management',
                'icon' => 'bi bi-people-fill',
                'sequence' => 4,
                'display_name' => 'Users',
                'created_at' => Carbon::parse('2026-02-23 09:52:53'),
                'updated_at' => Carbon::parse('2026-02-23 09:52:53'),
            ],
            [
                'id' => 4,
                'name' => 'RBAC',
                'icon' => 'bi bi-shield-lock',
                'sequence' => 5,
                'display_name' => 'RBAC',
                'created_at' => Carbon::parse('2026-02-23 09:52:53'),
                'updated_at' => Carbon::parse('2026-02-23 09:52:53'),
            ],
            [
                'id' => 5,
                'name' => 'Reports',
                'icon' => 'bi bi-graph-up',
                'sequence' => 6,
                'display_name' => 'Report',
                'created_at' => Carbon::parse('2026-02-23 09:52:54'),
                'updated_at' => Carbon::parse('2026-02-23 09:52:54'),
            ],
        ]);
    }
}