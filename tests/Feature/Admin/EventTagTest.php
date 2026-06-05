<?php

use App\Models\EventTag;
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

it('can create an event tag', function () {
    $this->actingAs($this->admin)
        ->post(route('admin.event-tags.store'), [
            'name' => 'Laravel',
        ])
        ->assertRedirect();

    $this->assertDatabaseHas('event_tags', [
        'name' => 'Laravel',
        'slug' => 'laravel',
    ]);
});
