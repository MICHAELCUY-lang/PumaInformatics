<?php

use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Spatie\Activitylog\Models\Activity;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;

uses(RefreshDatabase::class);

beforeEach(function () {
    app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
    
    $this->superAdminRole = Role::firstOrCreate(['name' => 'Super Admin']);
    
    $this->editorRole = Role::firstOrCreate(['name' => 'Editor']);
    // Ensure permission is created and assigned
    $permission = Permission::firstOrCreate(['name' => 'view.activity_logs', 'guard_name' => 'web']);
    $this->editorRole->givePermissionTo($permission);

    $this->viewerRole = Role::firstOrCreate(['name' => 'Viewer']);
    
    $this->superAdmin = User::factory()->create(['status' => 'active']);
    $this->superAdmin->assignRole($this->superAdminRole);
    
    $this->editor = User::factory()->create(['status' => 'active']);
    $this->editor->assignRole($this->editorRole);

    $this->viewer = User::factory()->create(['status' => 'active']);
    $this->viewer->assignRole($this->viewerRole);
});

it('prevents viewers from accessing the activity logs', function () {
    $this->actingAs($this->viewer)
        ->get(route('admin.activity-logs.index'))
        ->assertForbidden();
});

it('allows editors to view non-security logs', function () {
    // Create a regular log
    $contentLog = Activity::create([
        'log_name' => 'default',
        'description' => 'updated article',
        'event' => 'updated',
        'subject_type' => 'App\Models\Article',
        'subject_id' => 1,
    ]);

    // Create a security log
    $securityLog = Activity::create([
        'log_name' => 'security',
        'description' => 'failed login',
        'event' => 'failed',
    ]);

    $response = $this->actingAs($this->editor)
        ->get(route('admin.activity-logs.index'));

    if ($response->status() === 403) {
        dd('403 Forbidden', 'User can:', $this->editor->can('view.activity_logs'));
    }

    $response->assertStatus(200);
    $response->assertSee('updated article');
    $response->assertDontSee('failed login'); // Filtered out by the service
});

it('prevents editors from viewing security log details', function () {
    $securityLog = Activity::create([
        'log_name' => 'security',
        'description' => 'failed login',
        'event' => 'failed',
    ]);

    $this->actingAs($this->editor)
        ->get(route('admin.activity-logs.show', $securityLog))
        ->assertForbidden();
});

it('redacts sensitive information like passwords in the show view', function () {
    $user = User::factory()->create();
    
    $authLog = activity()
        ->useLog('auth')
        ->performedOn($user)
        ->event('created')
        ->withProperties([
            'attributes' => [
                'name' => 'John Doe',
                'password' => Hash::make('secret_password'),
                'token' => 'super_secret_token_123',
            ]
        ])
        ->log('user created');

    $response = $this->actingAs($this->superAdmin)
        ->get(route('admin.activity-logs.show', $authLog));

    $response->assertStatus(200);
    $response->assertSee('John Doe');
    $response->assertSee('******** (REDACTED)');
    $response->assertDontSee('secret_password');
    $response->assertDontSee('super_secret_token_123');
});
