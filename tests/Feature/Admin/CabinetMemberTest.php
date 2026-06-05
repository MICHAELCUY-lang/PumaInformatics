<?php

use App\Models\CabinetDepartment;
use App\Models\CabinetMember;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;

uses(RefreshDatabase::class);

beforeEach(function () {
    app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
    $this->role = Role::firstOrCreate(['name' => 'Admin']);
    $this->admin = User::factory()->create();
    $this->admin->assignRole($this->role);
});

it('can store a cabinet member', function () {
    $dept = CabinetDepartment::factory()->create();

    $data = [
        'department_id' => $dept->id,
        'name' => 'John Doe',
        'role_title' => 'Head of Public Relations',
        'role_hierarchy_level' => 10,
        'term_year' => '2026-2027',
        'is_active' => true,
        'biography' => 'Experienced leader.',
    ];

    $this->actingAs($this->admin)
        ->post(route('admin.cabinet-members.store'), $data)
        ->assertRedirect();

    $this->assertDatabaseHas('cabinet_members', [
        'name' => 'John Doe',
        'role_title' => 'Head of Public Relations',
        'term_year' => '2026-2027',
    ]);
});

it('clears cabinet cache on store', function () {
    $cacheSpy = $this->spy(\App\Services\CacheService::class);

    $dept = CabinetDepartment::factory()->create();

    $this->actingAs($this->admin)
        ->post(route('admin.cabinet-members.store'), [
            'department_id' => $dept->id,
            'name' => 'Jane Smith',
            'role_title' => 'Vice Head',
            'term_year' => '2026-2027',
            'is_active' => true,
        ]);

    $cacheSpy->shouldHaveReceived('flush')->with('cabinet')->once();
});
