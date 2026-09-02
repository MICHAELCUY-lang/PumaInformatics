<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

/**
 * The authoritative definition of the RBAC matrix.
 *
 * Safe to re-run on every deploy: roles and permissions are created with
 * firstOrCreate and role permissions are synced rather than appended, so this
 * doubles as the mechanism for rolling out new permissions.
 */
class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Every permission the application checks anywhere in app/.
     */
    public const PERMISSIONS = [
        'manage.users',
        'manage.roles',
        'manage.news',
        'manage.events',
        'manage.cabinet',
        'manage.voting',
        'manage.aspirations',
        'manage.projects',
        'manage.partners',
        'manage.navigation',
        'manage.media',
        'manage.settings',
        'view.activity_logs',
        'view.security_logs',
        'manage.audit_retention',
        'manage.cache',
    ];

    /**
     * Super Admin is deliberately absent: it is granted everything by the
     * Gate::before hook in AppServiceProvider.
     */
    public const ROLE_MATRIX = [
        'Admin' => [
            'manage.users',
            'manage.roles',
            'manage.news',
            'manage.events',
            'manage.cabinet',
            'manage.voting',
            'manage.aspirations',
            'manage.projects',
            'manage.partners',
            'manage.navigation',
            'manage.media',
            'manage.settings',
            'view.activity_logs',
            'manage.cache',
        ],
        'Editor' => [
            'manage.news',
            'manage.events',
            'manage.projects',
            'manage.partners',
            'manage.media',
            'view.activity_logs',
        ],
        'Moderator' => [
            'manage.aspirations',
        ],
        'Viewer' => [],
        'User' => [],
    ];

    public function run(): void
    {
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        foreach (self::PERMISSIONS as $permission) {
            Permission::firstOrCreate(['name' => $permission, 'guard_name' => 'web']);
        }

        Role::firstOrCreate(['name' => 'Super Admin', 'guard_name' => 'web']);

        foreach (self::ROLE_MATRIX as $roleName => $permissions) {
            $role = Role::firstOrCreate(['name' => $roleName, 'guard_name' => 'web']);
            $role->syncPermissions($permissions);
        }

        app(PermissionRegistrar::class)->forgetCachedPermissions();
    }
}
