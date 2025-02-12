<?php

namespace App\Traits;

use Illuminate\Support\Facades\DB;

trait User
{
    public static function getpermissionGroups()
    {
        $permission_groups = DB::table('permissions')
            ->select('module as name')
            ->groupBy('module')
            ->get();
        return $permission_groups;
    }

    public static function getpermissionsByGroupName($module)
    {
        $permissions = DB::table('permissions')
            ->select('name', 'id')
            ->where('module', $module)
            ->get();
        return $permissions;
    }

    public static function roleHasPermissions($role, $permissions)
    {
        $hasPermission = true;
        foreach ($permissions as $permission) {
            if (!$role->hasPermissionTo($permission->name)) {
                $hasPermission = false;
                return $hasPermission;
            }
        }
        return $hasPermission;
    }
}
