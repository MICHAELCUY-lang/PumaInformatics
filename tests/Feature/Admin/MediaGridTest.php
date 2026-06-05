<?php

use App\Models\User;
use App\Models\GlobalMedia;
use Spatie\Permission\Models\Role;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

uses(RefreshDatabase::class);

beforeEach(function () {
    Storage::fake('public');
    app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
    
    $this->adminRole = Role::firstOrCreate(['name' => 'Super Admin']);
    $this->admin = User::factory()->create(['status' => 'active']);
    $this->admin->assignRole($this->adminRole);
});

it('can list media in the admin grid', function () {
    $globalMedia = GlobalMedia::create([
        'user_id' => $this->admin->id,
        'status' => 'temporary'
    ]);

    $file = UploadedFile::fake()->image('test-image.jpg');
    $media = $globalMedia->addMedia($file)->toMediaCollection('editor_uploads');

    $response = $this->actingAs($this->admin)->get(route('admin.media.index'));
    
    $response->assertStatus(200);
    $response->assertSee('test-image.jpg');
});

it('allows deleting orphaned media from the grid', function () {
    $globalMedia = GlobalMedia::create([
        'user_id' => $this->admin->id,
        'status' => 'temporary'
    ]);

    $file = UploadedFile::fake()->image('orphaned.jpg');
    $media = $globalMedia->addMedia($file)->toMediaCollection('editor_uploads');

    $response = $this->actingAs($this->admin)
        ->delete(route('admin.media.destroy', $media));

    $response->assertRedirect(route('admin.media.index'));
    $response->assertSessionHas('success');
    
    $this->assertDatabaseMissing('media', ['id' => $media->id]);
    $this->assertDatabaseMissing('global_media', ['id' => $globalMedia->id]);
});

it('prevents deleting actively bound media from the grid', function () {
    // For this test, we mock a bound media by attaching it directly to a User
    $file = UploadedFile::fake()->image('avatar.jpg');
    $media = $this->admin->addMedia($file)->toMediaCollection('avatars');

    $response = $this->actingAs($this->admin)
        ->delete(route('admin.media.destroy', $media));

    $response->assertRedirect(route('admin.media.index'));
    $response->assertSessionHasErrors(['error']);
    
    $this->assertDatabaseHas('media', ['id' => $media->id]);
});
