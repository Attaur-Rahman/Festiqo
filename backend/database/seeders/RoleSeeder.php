<?php

namespace Database\Seeders;

use App\Enums\Role;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role as SpatieRole;
use App\Enums\Permission as PermissionEnum;
use Spatie\Permission\Models\Permission;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create Roles
        $admin = SpatieRole::firstOrCreate([
            'name' => Role::ADMIN->value,
            'guard_name' => 'sanctum',
        ]);

        $eventCoordinator = SpatieRole::firstOrCreate([
            'name' => Role::EVENT_COORDINATOR->value,
            'guard_name' => 'sanctum',
        ]);

        $student = SpatieRole::firstOrCreate([
            'name' => Role::STUDENT->value,
            'guard_name' => 'sanctum',
        ]);

        $studentCoordinator = SpatieRole::firstOrCreate([
            'name' => Role::STUDENT_COORDINATOR->value,
            'guard_name' => 'sanctum',
        ]);

        // Admin gets all permissions
        $admin->syncPermissions(
            Permission::pluck('name')->toArray()
        );

        // Event Coordinator permissions
        $eventCoordinator->syncPermissions([
            PermissionEnum::DASHBOARD_VIEW->value,

            PermissionEnum::CATEGORY_VIEW->value,

            PermissionEnum::EVENT_VIEW->value,
            PermissionEnum::EVENT_CREATE->value,
            PermissionEnum::EVENT_UPDATE->value,

            PermissionEnum::REGISTRATION_VIEW->value,
            PermissionEnum::REGISTRATION_VERIFY->value,
        ]);

        // Student Coordinator permissions
        $studentCoordinator->syncPermissions([
            PermissionEnum::DASHBOARD_VIEW->value,

            PermissionEnum::CATEGORY_VIEW->value,

            PermissionEnum::REGISTRATION_VIEW->value,
            PermissionEnum::REGISTRATION_VERIFY->value,
        ]);

        // Student permissions
        $student->syncPermissions([
            PermissionEnum::DASHBOARD_VIEW->value,

            PermissionEnum::CERTIFICATE_DOWNLOAD->value,
        ]);
    }
}
