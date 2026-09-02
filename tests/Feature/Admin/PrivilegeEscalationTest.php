<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;

uses(RefreshDatabase::class);

beforeEach(function () {
    app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

    // Admin holds manage.users and manage.roles but is NOT a Super Admin.
    $this->admin = User::factory()->create(['status' => 'active']);
    $this->admin->assignRole('Admin');

    $this->superAdmin = User::factory()->create(['status' => 'active']);
    $this->superAdmin->assignRole('Super Admin');
});

it('stops an admin from granting the super admin role on create', function () {
    $this->actingAs($this->admin)
        ->post(route('admin.users.store'), [
            'name' => 'Trojan',
            'email' => 'trojan@example.test',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'status' => 'active',
            'roles' => ['Super Admin'],
        ])
        ->assertSessionHasErrors('roles.0');

    expect(User::where('email', 'trojan@example.test')->exists())->toBeFalse();
});

it('stops an admin from promoting itself to super admin', function () {
    $this->actingAs($this->admin)
        ->put(route('admin.users.update', $this->admin), [
            'name' => $this->admin->name,
            'email' => $this->admin->email,
            'status' => 'active',
            'roles' => ['Super Admin'],
        ])
        ->assertSessionHasErrors('roles.0');

    expect($this->admin->fresh()->hasRole('Super Admin'))->toBeFalse();
});

it('allows a super admin to grant the super admin role', function () {
    $target = User::factory()->create(['status' => 'active']);

    $this->actingAs($this->superAdmin)
        ->put(route('admin.users.update', $target), [
            'name' => $target->name,
            'email' => $target->email,
            'status' => 'active',
            'roles' => ['Super Admin'],
        ])
        ->assertSessionHasNoErrors();

    expect($target->fresh()->hasRole('Super Admin'))->toBeTrue();
});

it('stops an admin from inviting a new super admin', function () {
    $superAdminRole = Role::findByName('Super Admin');

    $this->actingAs($this->admin)
        ->post(route('admin.invitations.store'), [
            'email' => 'backdoor@example.test',
            'role_id' => $superAdminRole->id,
        ])
        ->assertSessionHasErrors('role_id');

    $this->assertDatabaseMissing('user_invitations', ['email' => 'backdoor@example.test']);
});

it('stops an admin from granting itself a permission it does not hold', function () {
    $adminRole = Role::findByName('Admin');

    expect($this->admin->can('view.security_logs'))->toBeFalse();

    $this->actingAs($this->admin)
        ->put(route('admin.roles.update', $adminRole), [
            'name' => 'Admin',
            'permissions' => ['manage.users', 'view.security_logs'],
        ])
        ->assertSessionHasErrors('permissions.1');

    expect($this->admin->fresh()->can('view.security_logs'))->toBeFalse();
});

it('stops an admin from editing the super admin role', function () {
    $superAdminRole = Role::findByName('Super Admin');

    $this->actingAs($this->admin)
        ->put(route('admin.roles.update', $superAdminRole), [
            'name' => 'Super Admin',
            'permissions' => [],
        ])
        ->assertSessionHasErrors('name');
});

it('stops an operator from deleting their own account', function () {
    $this->actingAs($this->admin)
        ->delete(route('admin.users.destroy', $this->admin))
        ->assertSessionHas('error');

    expect(User::find($this->admin->id))->not->toBeNull();
});

it('stops a super admin from stripping their own super admin role', function () {
    $this->actingAs($this->superAdmin)
        ->put(route('admin.users.update', $this->superAdmin), [
            'name' => $this->superAdmin->name,
            'email' => $this->superAdmin->email,
            'status' => 'active',
            'roles' => ['Admin'],
        ]);

    expect($this->superAdmin->fresh()->hasRole('Super Admin'))->toBeTrue();
});
