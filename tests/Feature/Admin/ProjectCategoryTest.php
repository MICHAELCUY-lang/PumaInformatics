<?php

use App\Models\ProjectCategory;
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

it('can list project categories', function () {
    ProjectCategory::factory()->count(3)->create();

    $this->actingAs($this->admin)
        ->get(route('admin.project-categories.index'))
        ->assertOk()
        ->assertViewIs('admin.project-categories.index')
        ->assertViewHas('categories');
});

it('can create a category', function () {
    $this->actingAs($this->admin)
        ->post(route('admin.project-categories.store'), [
            'name' => 'Web Development',
            'is_active' => true,
        ])
        ->assertRedirect();

    $this->assertDatabaseHas('project_categories', [
        'name' => 'Web Development',
        'slug' => 'web-development',
    ]);
});
