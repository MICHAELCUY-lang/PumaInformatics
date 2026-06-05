<?php

use App\Models\User;
use App\Models\UserInvitation;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;

uses(RefreshDatabase::class);

beforeEach(function () {
    app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
    $this->adminRole = Role::firstOrCreate(['name' => 'Admin']);
    $this->adminRole->givePermissionTo(Permission::firstOrCreate(['name' => 'manage.users']));
    
    $this->editorRole = Role::firstOrCreate(['name' => 'Editor']);

    $this->admin = User::factory()->create(['status' => 'active']);
    $this->admin->assignRole($this->adminRole);
    
    $this->editor = User::factory()->create(['status' => 'active']);
    $this->editor->assignRole($this->editorRole);
});

it('prevents users without manage.users permission from creating invitations', function () {
    $this->actingAs($this->editor)
        ->post(route('admin.invitations.store'), [
            'email' => 'new@puma.it',
            'role_id' => $this->editorRole->id,
        ])
        ->assertForbidden();
});

it('allows users with manage.users permission to create invitations', function () {
    $this->actingAs($this->admin)
        ->post(route('admin.invitations.store'), [
            'email' => 'new@puma.it',
            'role_id' => $this->editorRole->id,
        ])
        ->assertRedirect();

    $this->assertDatabaseHas('user_invitations', [
        'email' => 'new@puma.it',
        'role_id' => $this->editorRole->id,
        'invited_by' => $this->admin->id,
    ]);
});

it('prevents suspended users from logging in (Middleware Test)', function () {
    $suspendedUser = User::factory()->create(['status' => 'suspended']);

    $response = $this->post('/login', [
        'email' => $suspendedUser->email,
        'password' => 'password',
    ]);

    // Breeze redirects to dashboard after successful credential check
    $response->assertRedirect('/dashboard');

    // But hitting the dashboard should trigger the EnsureUserIsActive middleware and log them out
    $dashboardResponse = $this->get('/dashboard');
    $dashboardResponse->assertRedirect('/login');
    $this->assertGuest();
});
