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
                'order' => 3,
            ],
            [
                'name' => 'Users',
                'icon' => 'fas fa-user',
                'route' => 'admin.users.index',
                'parent_id' => 3,
                'order' => 1,
            ],
            [
                'name' => 'Role',
                'icon' => 'fas fa-user-tag',
                'route' => 'admin.roles.index',
                'parent_id' => 3,
                'order' => 2,
            ],
            [
                'name' => 'Permission',
                'icon' => 'fas fa-user-lock',
                'route' => 'admin.permissions.index',
                'parent_id' => 3,
                'order' => 3,
            ],
            [
                'name' => 'Route Access',
                'icon' => 'fas fa-bars',
                'route' => 'admin.route-accesses.index',
                'parent_id' => 3,
                'order' => 4
            ],
            [
                'name' => 'Menus',
                'icon' => 'fas fa-bars',
                'route' => 'admin.menus.index',
                'parent_id' => 3,
                'order' => 5,
            ],

        ];

        foreach ($menus as $menu) {
            \App\Models\Menu::create($menu);
        }
    }
}
