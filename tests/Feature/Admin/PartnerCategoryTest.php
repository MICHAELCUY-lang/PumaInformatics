<?php

use App\Models\PartnerCategory;
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

it('can create a hierarchical category', function () {
    $parent = PartnerCategory::factory()->create(['name' => 'Academic']);

    $this->actingAs($this->admin)
        ->post(route('admin.partner-categories.store'), [
            'name' => 'University',
            'parent_id' => $parent->id,
            'is_active' => true,
        ])
        ->assertRedirect();

    $this->assertDatabaseHas('partner_categories', [
        'name' => 'University',
        'parent_id' => $parent->id,
    ]);
});
