<?php

use App\Models\Navigation;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
    $this->role = Role::firstOrCreate(['name' => 'Super Admin']);
    $this->admin = User::factory()->create();
    $this->admin->assignRole($this->role);
});

it('can list navigations in admin panel', function () {
    Navigation::factory()->count(3)->create();

    $this->actingAs($this->admin)
        ->get(route('admin.navigations.index'))
        ->assertOk()
        ->assertViewIs('admin.navigations.index')
        ->assertViewHas('navigations');
});

it('can create a new navigation item', function () {
    $data = [
        'name' => 'Home',
        'url' => '/',
        'is_external' => false,
        'is_active' => true,
    ];

    $this->actingAs($this->admin)
        ->post(route('admin.navigations.store'), $data)
        ->assertRedirect(route('admin.navigations.index'));

    $this->assertDatabaseHas('navigations', [
        'name' => 'Home',
        'url' => '/',
    ]);
});

it('validates required fields when creating navigation', function () {
    $this->actingAs($this->admin)
        ->post(route('admin.navigations.store'), [])
        ->assertSessionHasErrors(['name', 'url']);
});

it('can reorder navigation items', function () {
    $nav1 = Navigation::factory()->create(['order' => 1]);
    $nav2 = Navigation::factory()->create(['order' => 2]);

    $this->actingAs($this->admin)
        ->post(route('admin.navigations.reorder'), [
            'items' => [
                ['id' => $nav2->id, 'order' => 1],
                ['id' => $nav1->id, 'order' => 2],
            ]
        ])
        ->assertOk();

    $this->assertEquals(1, $nav2->fresh()->order);
    $this->assertEquals(2, $nav1->fresh()->order);
});

it('clears cache when navigation is created', function () {
    $cacheSpy = $this->spy(\App\Services\CacheService::class);

    $this->actingAs($this->admin)
        ->post(route('admin.navigations.store'), [
            'name' => 'About',
            'url' => '/about',
            'is_external' => false,
            'is_active' => true,
        ]);

    $cacheSpy->shouldHaveReceived('flush')->with('navigations')->once();
});
