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
