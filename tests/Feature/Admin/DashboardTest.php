<?php

use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
    Role::firstOrCreate(['name' => 'Super Admin']);
});

it('denies access to dashboard for guests', function () {
    $this->get(route('admin.dashboard'))
        ->assertRedirect(route('login'));
});

it('denies access to dashboard for normal users', function () {
    $user = User::factory()->create();
    // Default user does not have Super Admin role

    $this->actingAs($user)
        ->get(route('admin.dashboard'))
        ->assertForbidden(); // Spatie RoleMiddleware throws 403
});

it('allows access to dashboard for super admins', function () {
    $admin = User::factory()->create();
    $admin->assignRole('Super Admin');

    $this->actingAs($admin)
        ->get(route('admin.dashboard'))
        ->assertOk()
        ->assertViewIs('admin.dashboard')
        ->assertViewHas('analytics');
});
