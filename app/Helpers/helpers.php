<?php

if (!function_exists('getMenu')) {
    function getMenu()
    {
        return \App\Models\Menu::whereNull('parent_id')
            ->orderBy('order')
            ->with('children')
            ->get();
    }
}

if (!function_exists('generateModulePermissions')) {
    function generateModulePermissions($module, $type = 'all')
    {
        $permissions = [
            'view' => $module . '-view',
            'create' => $module . '-create',
            'edit' => $module . '-edit',
            'delete' => $module . '-delete'
        ];

        if ($type !== 'all') {
            if (is_array($type)) {
                return array_values(array_intersect_key($permissions, array_flip($type)));
            }
            return isset($permissions[$type]) ? $permissions[$type] : [];
        }

        return array_values($permissions);
    }
}
