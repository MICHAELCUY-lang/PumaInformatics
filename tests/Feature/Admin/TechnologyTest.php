<?php

use App\Models\Technology;
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

it('can list technologies', function () {
    Technology::factory()->count(3)->create();

    $this->actingAs($this->admin)
        ->get(route('admin.technologies.index'))
        ->assertOk()
        ->assertViewIs('admin.technologies.index')
        ->assertViewHas('technologies');
});

it('can create a technology', function () {
    $this->actingAs($this->admin)
        ->post(route('admin.technologies.store'), [
            'name' => 'Laravel',
            'is_active' => true,
        ])
        ->assertRedirect();

    $this->assertDatabaseHas('technologies', [
        'name' => 'Laravel',
        'slug' => 'laravel',
    ]);
});
