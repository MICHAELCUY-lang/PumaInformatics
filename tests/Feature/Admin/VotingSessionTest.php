<?php

use App\Models\VotingSession;
use App\Models\Candidate;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
    $this->role = Role::firstOrCreate(['name' => 'Admin']);
    $permission = \Spatie\Permission\Models\Permission::firstOrCreate(['name' => 'manage.voting']);
    $this->role->givePermissionTo($permission);
    $this->admin = User::factory()->create();
    $this->admin->assignRole($this->role);
});

it('can create a voting session', function () {
    $this->actingAs($this->admin)
        ->post(route('admin.voting-sessions.store'), [
            'title' => 'Presidential Election 2026',
            'description' => 'Main election',
            'status' => 'draft',
            'start_date' => now()->addDay()->toDateTimeString(),
            'end_date' => now()->addDays(3)->toDateTimeString(),
            'results_visibility' => 'private',
        ])
        ->assertRedirect();

    $this->assertDatabaseHas('voting_sessions', [
        'title' => 'Presidential Election 2026',
        'status' => 'draft',
    ]);
});
