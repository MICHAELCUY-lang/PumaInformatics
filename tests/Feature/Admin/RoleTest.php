<?php

use App\Models\User;
use Spatie\Permission\Models\Role;

uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

test('super admin can access admin dashboard', function () {
    // The role already exists: TestCase seeds RolesAndPermissionsSeeder.
    $role = Role::findByName('Super Admin');
    $user = User::factory()->create(['status' => 'active']);
    $user->assignRole($role);

    $response = $this->actingAs($user)->get('/admin');

    $response->assertStatus(200);
});

test('guest cannot access admin dashboard', function () {
    $response = $this->get('/admin');

    $response->assertRedirect('/login');
});
