<?php

use App\Models\Partner;
use App\Models\PartnerCategory;
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

it('can store a new partner', function () {
    $category = PartnerCategory::factory()->create();

    $data = [
        'category_id' => $category->id,
        'name' => 'Tech Corp',
        'website_url' => 'https://example.com',
        'is_featured' => true,
        'order' => 1,
        'is_active' => true,
    ];

    $this->actingAs($this->admin)
        ->post(route('admin.partners.store'), $data)
        ->assertRedirect();

    $this->assertDatabaseHas('partners', [
        'name' => 'Tech Corp',
        'slug' => 'tech-corp',
    ]);
});

it('invalidates partner cache on save', function () {
    $cacheSpy = $this->spy(\App\Services\CacheService::class);

    $category = PartnerCategory::factory()->create();

    $this->actingAs($this->admin)
        ->post(route('admin.partners.store'), [
            'category_id' => $category->id,
            'name' => 'Cache Partner',
            'is_active' => true,
        ]);

    $cacheSpy->shouldHaveReceived('flush')->with('partners')->once();
});
