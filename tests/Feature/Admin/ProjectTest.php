<?php

use App\Models\Project;
use App\Models\ProjectCategory;
use App\Models\Technology;
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

it('can store a new project with technologies', function () {
    $category = ProjectCategory::factory()->create();
    $tech1 = Technology::factory()->create();
    $tech2 = Technology::factory()->create();

    $data = [
        'title' => 'PUMA IT Website',
        'category_id' => $category->id,
        'status' => 'published',
        'start_date' => '2026-05-01',
        'github_url' => 'https://github.com/president-university',
        'technologies' => [$tech1->id, $tech2->id],
    ];

    $this->actingAs($this->admin)
        ->post(route('admin.projects.store'), $data)
        ->assertRedirect();

    $this->assertDatabaseHas('projects', [
        'title' => 'PUMA IT Website',
        'slug' => 'puma-it-website',
    ]);

    $project = Project::where('slug', 'puma-it-website')->first();
    $this->assertCount(2, $project->technologies);
});

it('invalidates project cache on save', function () {
    $cacheSpy = $this->spy(\App\Services\CacheService::class);

    $category = ProjectCategory::factory()->create();

    $this->actingAs($this->admin)
        ->post(route('admin.projects.store'), [
            'title' => 'Cached Project',
            'category_id' => $category->id,
            'status' => 'draft',
        ]);

    $cacheSpy->shouldHaveReceived('flush')->with('projects')->once();
});
