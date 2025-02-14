<?php

namespace Database\Seeders;

use App\Models\RouteAccess;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Route;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Str;

class RouteAccessSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get all routes from admin.php
        $routes = Route::getRoutes();

        // Get or create admin role
        $adminRole = Role::firstOrCreate(['name' => 'admin']);

        foreach ($routes as $route) {
            if (str_starts_with($route->getName(), 'admin.')) {
                // Extract the base permission name (e.g., 'users' from 'admin.users.index')
                $routeParts = explode('.', $route->getName());
                $resourceName = $routeParts[1];
                $action = $routeParts[2];

                // Map route actions to permission types
                $actionMap = [
                    'index' => 'view',
                    'show' => 'view',
                    'create' => 'create',
                    'store' => 'create',
                    'edit' => 'edit',
                    'update' => 'edit',
                    'destroy' => 'delete'
                ];

                // Convert route name to singular (e.g., users -> user)
                $moduleName = Str::singular($resourceName);

                // Get the permission type from action map
                $permissionType = $actionMap[$action] ?? $action;

                // Generate permission name using the same format as RolePermissionSeeder
                $permissionName = "$moduleName-$permissionType";

                // Find existing permission
                $permission = Permission::where('name', $permissionName)->first();

                if ($permission) {
                    // Create route access
                    RouteAccess::firstOrCreate([
                        'route_name' => $route->getName(),
                        'role_id' => $adminRole->id,
                        'permission_id' => $permission->id,
                    ]);
                }
            }
        }
    }
}
