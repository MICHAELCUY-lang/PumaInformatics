<?php

use App\Models\Event;
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

it('can store a new event', function () {
    $category = EventCategory::factory()->create();

    $data = [
        'title' => 'Tech Summit 2026',
        'category_id' => $category->id,
        'status' => 'published',
        'start_date' => '2026-08-15 09:00:00',
        'end_date' => '2026-08-15 17:00:00',
        'timezone' => 'Asia/Jakarta',
        'location_name' => 'President University Auditorium',
        'internal_rsvp_enabled' => true,
    ];

    $this->actingAs($this->admin)
        ->post(route('admin.events.store'), $data)
        ->assertRedirect(route('admin.events.index'));

    $this->assertDatabaseHas('events', [
        'title' => 'Tech Summit 2026',
        'slug' => 'tech-summit-2026',
        'internal_rsvp_enabled' => 1,
    ]);
});

it('invalidates event cache on save', function () {
    $cacheSpy = $this->spy(\App\Services\CacheService::class);

    $category = EventCategory::factory()->create();

    $this->actingAs($this->admin)
        ->post(route('admin.events.store'), [
            'title' => 'Cached Event',
            'category_id' => $category->id,
            'status' => 'draft',
            'start_date' => '2026-08-15 09:00:00',
            'end_date' => '2026-08-15 17:00:00',
            'timezone' => 'Asia/Jakarta',
        ]);

    $cacheSpy->shouldHaveReceived('flush')->with('events')->once();
});

it('resolves slug collisions automatically', function () {
    $category = EventCategory::factory()->create();

    $data = [
        'title' => 'Duplicate Title',
        'category_id' => $category->id,
        'status' => 'draft',
        'start_date' => '2026-08-15 09:00:00',
        'timezone' => 'Asia/Jakarta',
    ];

    // Create first
    $this->actingAs($this->admin)->post(route('admin.events.store'), $data);
    $this->assertDatabaseHas('events', ['slug' => 'duplicate-title']);

    // Create second with same title
    $this->actingAs($this->admin)->post(route('admin.events.store'), $data);
    $this->assertDatabaseHas('events', ['slug' => 'duplicate-title-1']);

    // Create third
    $this->actingAs($this->admin)->post(route('admin.events.store'), $data);
    $this->assertDatabaseHas('events', ['slug' => 'duplicate-title-2']);
});

it('does not change slug when updating other fields', function () {
    $event = Event::factory()->create([
        'title' => 'Original Title',
        'slug' => 'original-title'
    ]);

    $data = [
        'title' => 'Original Title', // Unchanged
        'status' => 'published',
        'start_date' => '2026-09-01 00:00:00',
        'timezone' => 'Asia/Jakarta',
    ];

    $this->actingAs($this->admin)->put(route('admin.events.update', $event), $data);

    $this->assertDatabaseHas('events', [
        'id' => $event->id,
        'slug' => 'original-title', // Still the same
        'status' => 'published',
    ]);
});

it('updates slug when title changes', function () {
    $event = Event::factory()->create([
        'title' => 'Old Title',
        'slug' => 'old-title'
    ]);

    $data = [
        'title' => 'New Awesome Title',
        'status' => 'published',
        'start_date' => '2026-09-01 00:00:00',
        'timezone' => 'Asia/Jakarta',
    ];

    $this->actingAs($this->admin)->put(route('admin.events.update', $event), $data);

    $this->assertDatabaseHas('events', [
        'id' => $event->id,
        'title' => 'New Awesome Title',
        'slug' => 'new-awesome-title',
    ]);
});

it('prevents manual slug collision on model creation', function () {
    $event1 = Event::factory()->create([
        'title' => 'Manual',
        'slug' => 'manual-slug'
    ]);

    // Force a collision at the model layer
    $event2 = new Event([
        'title' => 'Another Manual',
        'slug' => 'manual-slug', // Duplicate
        'status' => 'draft',
        'start_date' => now(),
        'timezone' => 'Asia/Jakarta',
    ]);
    
    // In Event::boot(), if slug is present but duplicates exist, we should resolve it.
    // Let's see how we handle this. Wait, our logic says if empty(slug). 
    // We need to fix Event::boot to resolve even if not empty.
    $event2->save();

    $this->assertDatabaseHas('events', [
        'id' => $event2->id,
        'slug' => 'manual-slug-1', // Automatically resolved
    ]);
});
