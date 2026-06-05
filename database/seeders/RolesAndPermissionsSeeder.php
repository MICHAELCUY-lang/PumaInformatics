<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create granular permissions
        $permissions = [
            'manage.users',
            'manage.roles',
            'manage.news',
            'manage.events',
            'manage.voting',
            'manage.aspirations',
            'manage.projects',
            'manage.partners',
            'manage.navigation',
            'manage.settings',
            'view.activity_logs',
            'view.security_logs',
            'manage.audit_retention',
            'manage.cache',
        ];

        foreach ($permissions as $permission) {
            \Spatie\Permission\Models\Permission::firstOrCreate(['name' => $permission]);
        }

        // Create Roles and assign created permissions
        $superAdmin = \Spatie\Permission\Models\Role::firstOrCreate(['name' => 'Super Admin']);
        // Super Admin gets everything via Gate::before in AuthServiceProvider/AppServiceProvider usually,
        // but we can explicitly assign or leave it to Gate.

        $admin = \Spatie\Permission\Models\Role::firstOrCreate(['name' => 'Admin']);
        
        // Admins get everything EXCEPT security logs and audit retention (which are Super Admin only)
        $adminPermissions = array_filter($permissions, function($p) {
            return !in_array($p, ['view.security_logs', 'manage.audit_retention']);
        });
        $admin->givePermissionTo($adminPermissions);

        $editor = \Spatie\Permission\Models\Role::firstOrCreate(['name' => 'Editor']);
        $editor->givePermissionTo([
            'manage.news', 'manage.events', 'manage.projects', 'manage.partners', 'view.activity_logs'
        ]);

        $moderator = \Spatie\Permission\Models\Role::firstOrCreate(['name' => 'Moderator']);
        $moderator->givePermissionTo([
            'manage.aspirations'
        ]);

        $viewer = \Spatie\Permission\Models\Role::firstOrCreate(['name' => 'Viewer']);
        // Viewers have no explicit manage permissions
    }
}
