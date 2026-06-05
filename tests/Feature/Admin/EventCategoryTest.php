<?php

use App\Models\EventCategory;
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

it('can list event categories', function () {
    EventCategory::factory()->count(3)->create();

    $this->actingAs($this->admin)
        ->get(route('admin.event-categories.index'))
        ->assertOk()
        ->assertViewIs('admin.event-categories.index')
        ->assertViewHas('categories');
});

it('can create a category', function () {
    $this->actingAs($this->admin)
        ->post(route('admin.event-categories.store'), [
            'name' => 'Technology',
            'is_active' => true,
        ])
        ->assertRedirect();

    $this->assertDatabaseHas('event_categories', [
        'name' => 'Technology',
        'slug' => 'technology',
    ]);
});
