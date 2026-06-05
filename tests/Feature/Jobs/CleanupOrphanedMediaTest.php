<?php

use App\Models\GlobalMedia;
use App\Models\User;
use App\Jobs\CleanupOrphanedMedia;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->user = User::factory()->create();
});

it('deletes expired temporary global media', function () {
    $expiredMedia = GlobalMedia::create([
        'user_id' => $this->user->id,
        'status' => 'temporary',
        'expires_at' => now()->subDay(), // Expired yesterday
    ]);

    $validMedia = GlobalMedia::create([
        'user_id' => $this->user->id,
        'status' => 'temporary',
        'expires_at' => now()->addDay(), // Expires tomorrow
    ]);

    $permanentMedia = GlobalMedia::create([
        'user_id' => $this->user->id,
        'status' => 'permanent',
        'expires_at' => now()->subDay(), // Status trumps expiration in theory, though we shouldn't have expires_at here
    ]);

    (new CleanupOrphanedMedia())->handle();

    $this->assertDatabaseMissing('global_media', ['id' => $expiredMedia->id]);
    $this->assertDatabaseHas('global_media', ['id' => $validMedia->id]);
    $this->assertDatabaseHas('global_media', ['id' => $permanentMedia->id]);
});
