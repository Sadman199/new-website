<?php

namespace Database\Seeders;

use App\Models\Admin;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        Admin::updateOrCreate(
            ['email' => 'admin@brokerscourt.com'],
            [
                'name' => 'BrokersCourt Admin',
                'password' => Hash::make('Admin@Brokers2026'),
                'photo' => 'default-admin.png',
                'token' => '',
            ]
        );
    }
}
