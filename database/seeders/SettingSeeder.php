<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\Setting::updateOrCreate(['key' => 'sale_notification_enabled'], ['value' => '1']);
        \App\Models\Setting::updateOrCreate(['key' => 'user_registered_notification_enabled'], ['value' => '1']);
    }
}
