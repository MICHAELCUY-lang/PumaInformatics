<?php

use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    Storage::fake('public');
    
    app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
    $this->adminRole = Role::firstOrCreate(['name' => 'Admin']);
    
    $this->admin = User::factory()->create(['status' => 'active']);
    $this->admin->assignRole($this->adminRole);
});

it('allows authenticated users to upload media via API', function () {
    $file = UploadedFile::fake()->image('test-image.jpg', 600, 600);

    $response = $this->actingAs($this->admin)
        ->postJson(route('api.admin.media.upload'), [
            'image' => $file,
        ]);

    $response->assertStatus(201)
             ->assertJsonStructure(['url', 'uuid']);

    $this->assertDatabaseHas('global_media', [
        'user_id' => $this->admin->id,
        'status' => 'temporary',
    ]);
});

it('rejects invalid file types for security', function () {
    // Fake an SVG which might contain XSS
    $file = UploadedFile::fake()->create('malicious.svg', 100, 'image/svg+xml');

    $response = $this->actingAs($this->admin)
        ->postJson(route('api.admin.media.upload'), [
            'image' => $file,
        ]);

    $response->assertStatus(422)
             ->assertJsonValidationErrors(['image']);
});
