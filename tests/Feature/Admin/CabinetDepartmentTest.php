<?php

use App\Models\CabinetDepartment;
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

it('can create a department', function () {
    $this->actingAs($this->admin)
        ->post(route('admin.cabinet-departments.store'), [
            'name' => 'Public Relations',
            'order' => 1,
            'is_active' => true,
        ])
        ->assertRedirect();

    $this->assertDatabaseHas('cabinet_departments', [
        'name' => 'Public Relations',
        'slug' => 'public-relations',
    ]);
});
