<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create roles
        $superAdminRole = \Spatie\Permission\Models\Role::create(['name' => 'Super Admin']);
        $adminRole = \Spatie\Permission\Models\Role::create(['name' => 'Admin']);
        $userRole = \Spatie\Permission\Models\Role::create(['name' => 'User']);

        // Create initial Super Admin user
        $superAdmin = \App\Models\User::factory()->create([
            'name' => 'Super Admin',
            'email' => 'admin@puma.it',
            'password' => bcrypt('password'), // password
        ]);

        $superAdmin->assignRole($superAdminRole);
    }
}
