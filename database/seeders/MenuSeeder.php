<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $menus = [
            [
                'name' => 'Dashboard',
                'icon' => 'fas fa-tachometer-alt',
                'route' => 'index',
                'order' => 1,
            ],
            [
                'name' => 'Setting',
                'icon' => 'fas fa-cog',
                'order' => 2,
            ],
            [
                'name' => 'Users',
                'icon' => 'fas fa-user',
                // 'route' => 'user.index',
                'parent_id' => 2,
            ],
            [
                'name' => 'Role',
                'icon' => 'fas fa-user-tag',
                // 'route' => 'role.index',
                'parent_id' => 2,
            ],
            [
                'name' => 'Permission',
                'icon' => 'fas fa-user-lock',
                // 'route' => 'permission.index',
                'parent_id' => 2,
            ],
            [
                'name' => 'Menu',
                'icon' => 'fas fa-bars',
                // 'route' => 'menu.index',
                'parent_id' => 2,
            ],
        ];

        foreach ($menus as $menu) {
            \App\Models\Menu::create($menu);
        }
    }
}
