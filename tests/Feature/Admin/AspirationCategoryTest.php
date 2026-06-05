<?php

use App\Models\AspirationCategory;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
    $this->role = Role::firstOrCreate(['name' => 'Admin']);
    $this->admin = User::factory()->create();
    $this->admin->assignRole($this->role);
});

it('can list aspiration categories', function () {
    AspirationCategory::factory()->count(3)->create();

    $this->actingAs($this->admin)
        ->get(route('admin.aspiration-categories.index'))
        ->assertOk()
        ->assertViewIs('admin.aspiration-categories.index')
        ->assertViewHas('categories');
});

it('can create a category', function () {
    $this->actingAs($this->admin)
        ->post(route('admin.aspiration-categories.store'), [
            'name' => 'Facilities',
            'is_active' => true,
        ])
        ->assertRedirect();

    $this->assertDatabaseHas('aspiration_categories', [
        'name' => 'Facilities',
        'slug' => 'facilities',
    ]);
});
