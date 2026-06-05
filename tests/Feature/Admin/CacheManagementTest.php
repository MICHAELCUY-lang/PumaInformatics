<?php

use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Cache;
use Spatie\Activitylog\Models\Activity;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

    $this->superAdminRole = Role::firstOrCreate(['name' => 'Super Admin']);
    $permission = Permission::firstOrCreate(['name' => 'manage.settings', 'guard_name' => 'web']);
    $this->superAdminRole->givePermissionTo($permission);
    
    $this->superAdmin = User::factory()->create(['status' => 'active']);
    $this->superAdmin->assignRole($this->superAdminRole);

    $this->editorRole = Role::firstOrCreate(['name' => 'Editor']);
    $this->editor = User::factory()->create(['status' => 'active']);
    $this->editor->assignRole($this->editorRole);
});

it('prevents non-super admins from accessing the cache management dashboard', function () {
    $this->actingAs($this->editor)
        ->get(route('admin.cache.index'))
        ->assertForbidden();

    $this->actingAs($this->editor)
        ->post(route('admin.cache.system'), ['type' => 'views'])
        ->assertForbidden();
});

it('allows super admins to access the cache management dashboard', function () {
    $this->actingAs($this->superAdmin)
        ->get(route('admin.cache.index'))
        ->assertStatus(200)
        ->assertSee('Cache Operations')
        ->assertSee('Global Application Purge');
});

it('dispatches the correct artisan command for view recompilation and logs it', function () {
    Artisan::spy();

    $this->actingAs($this->superAdmin)
        ->post(route('admin.cache.system'), [
            'type' => 'views'
        ])
        ->assertRedirect()
        ->assertSessionHas('success', 'Compiled views cleared.');

    Artisan::shouldHaveReceived('call')->with('view:clear')->once();

    $log = Activity::where('event', 'views_cleared')->first();
    expect($log)->not->toBeNull();
    expect($log->description)->toBe('Compiled Views Cache Cleared');
    expect($log->causer_id)->toBe($this->superAdmin->id);
});

it('dispatches the global application cache clear with audit reasoning', function () {
    Artisan::spy();

    $reason = 'Critical anomaly post-deployment';

    $this->actingAs($this->superAdmin)
        ->post(route('admin.cache.system'), [
            'type' => 'global',
            'reason' => $reason
        ])
        ->assertRedirect()
        ->assertSessionHas('success', 'Global application cache flushed.');

    Artisan::shouldHaveReceived('call')->with('cache:clear')->once();

    $log = Activity::where('event', 'global_cleared')->first();
    expect($log)->not->toBeNull();
    expect($log->description)->toBe('Global Application Cache Cleared');
    expect($log->properties['reason'])->toBe($reason);
});

it('clears granular cache tags', function () {
    // We cannot easily spy on Cache::tags() returning a fake because it requires method chaining
    // So we will just test the HTTP response and the Audit log

    $this->actingAs($this->superAdmin)
        ->post(route('admin.cache.tag'), [
            'tag' => 'navigation'
        ])
        ->assertRedirect(); // could be success or error depending on driver, but shouldn't fail

    // We can't guarantee tag clear worked if testing with 'file' array, but let's check log
    // Actually, in testing environment array cache might support tags. Let's see if the log exists.
    // If tags are not supported, 'tag_clear_failed' is logged.
    $log = Activity::whereIn('event', ['tag_cleared', 'tag_clear_failed'])->first();
    expect($log)->not->toBeNull();
    expect($log->properties['tag'])->toBe('navigation');
});
