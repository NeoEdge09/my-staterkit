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
                'name' => 'Home',
                'icon' => 'ti ti-home',
                'route' => 'index',
                'order' => 1,
            ],
            [
                'name' => 'Dashboard',
                'icon' => 'ti ti-dashboard',
                'route' => 'index',
                'order' => 2,
            ],


            [
                'name' => 'Settings',
                'icon' => 'ti ti-settings',
                'order' => 2,
            ],
            [
                'name' => 'Users',
                'icon' => 'fas fa-user',
                // 'route' => 'user.index',
                'parent_id' => 3,
            ],
            [
                'name' => 'Role',
                'icon' => 'fas fa-user-tag',
                // 'route' => 'role.index',
                'parent_id' => 3,
            ],
            [
                'name' => 'Permission',
                'icon' => 'fas fa-user-lock',
                // 'route' => 'permission.index',
                'parent_id' => 3,
            ],
            [
                'name' => 'Menu',
                'icon' => 'fas fa-bars',
                // 'route' => 'menu.index',
                'parent_id' => 3,
            ],
            [
                'name' => 'Menu 1',
                'parent_id' => 7,
                'order' => 1,
            ],
            [
                'name' => 'Menu 2',
                'parent_id' => 7,
                'order' => 2,
            ],
            [
                'name' => 'Menu 3',
                'parent_id' => 7,
                'order' => 3,
            ],
            [
                'name' => 'Menu 4',
                'parent_id' => 7,
                'order' => 4,
            ],
            [
                'name' => 'Menu 5',
                'parent_id' => 7,
                'order' => 5,
            ],
            [
                'name' => 'Menu 7',
                'parent_id' => 7,
                'order' => 7,
            ],
            [
                'name' => 'Menu 7',
                'parent_id' => 7,
                'order' => 7,
            ],
            [
                'name' => 'Menu 8',
                'parent_id' => 7,
                'order' => 8,
            ],
            [
                'name' => 'Menu 9',
                'parent_id' => 7,
                'order' => 9,
            ],
            [
                'name' => 'Menu 10',
                'parent_id' => 7,
                'order' => 10,
            ],
            [
                'name' => 'Menu 7',
                'parent_id' => 7,
                'order' => 11,
            ],
            [
                'name' => 'Menu 12',
                'parent_id' => 7,
                'order' => 12,
            ],
            [
                'name' => 'Menu 13',
                'parent_id' => 7,
                'order' => 13,
            ],
        ];

        foreach ($menus as $menu) {
            \App\Models\Menu::create($menu);
        }
    }
}
