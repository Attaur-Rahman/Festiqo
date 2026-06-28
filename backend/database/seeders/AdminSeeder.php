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
        $admin = User::firstOrCreate(
            [
                'email' => 'admin@festiqo.com',
            ],
            [
                'name' => 'Super Admin',
                'password' => Hash::make('Admin@123'),
                'status' => true,
            ]
        );

        $admin->assignRole(Role::ADMIN->value);
    }
}
