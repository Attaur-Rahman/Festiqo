<?php

namespace Database\Seeders;

use App\Enums\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::updateOrCreate(
            ['email' => 'admin@festiqo.com'],
            [
                'phone' => '0123456789',
                'name' => 'Super Admin',
                'password' => Hash::make('Admin@123'),
                'status' => true,
            ]
        );

        $inactiveAdmin = User::updateOrCreate(
            ['email' => 'admin2@festiqo.com'],
            [
                'phone' => '1234567890',
                'name' => 'Inactive Admin',
                'password' => Hash::make('Admin@1234'),
                'status' => false,
            ]
        );

        $admin->syncRoles([Role::ADMIN->value]);
        $inactiveAdmin->syncRoles([Role::ADMIN->value]);
    }
}
