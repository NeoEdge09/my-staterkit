<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Database\Seeders\Admin\RolePermissionSeeder;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            MenuSeeder::class,
            RolePermissionSeeder::class,
            RouteAccessSeeder::class,
        ]);
        User::factory(50)->create();

        User::factory()->create([
            'name' => 'dika',
            'email' => 'zoro@gmail.com',
        ])->assignRole('admin');
    }
}
