<?php

namespace Database\Seeders\Admin;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $adminRole = \Spatie\Permission\Models\Role::create(['name' => 'admin']);
        $userRole = \Spatie\Permission\Models\Role::create(['name' => 'user']);

        $modules = [
            'dashboard',
            'user',
            'role',
            'permission',
            'menus',
            'routeAccess',
        ];


        foreach ($modules as $module) {
            // Generate permissions for each module
            $permissions = generateModulePermissions($module);

            foreach ($permissions as $permission) {
                Permission::create([
                    'name' => $permission,
                    'module' => $module
                ]);
            }
        }

        // Assign permissions to roles
        $adminRole->givePermissionTo(Permission::all());
        $userRole->givePermissionTo(Permission::all());
    }
}
