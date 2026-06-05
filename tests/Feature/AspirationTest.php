<?php

use App\Models\Aspiration;
use App\Models\AspirationCategory;
use App\Models\User;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

uses(RefreshDatabase::class);

it('allows anonymous aspiration submission and strips user id', function () {
    $category = AspirationCategory::factory()->create();
    $user = User::factory()->create();

    $data = [
        'category_id' => $category->id,
        'subject' => 'Wi-Fi issues in library',
        'payload' => 'The connection drops constantly.',
        'is_anonymous' => true,
    ];

    $this->actingAs($user)
        ->post(route('public.aspirations.store'), $data)
        ->assertRedirect();

    $this->assertDatabaseHas('aspirations', [
        'subject' => 'Wi-Fi issues in library',
        'user_id' => null, // Strict privacy check
        'is_anonymous' => 1,
    ]);
});

it('allows authenticated aspiration submission with user id', function () {
    $category = AspirationCategory::factory()->create();
    $user = User::factory()->create();

    $data = [
        'category_id' => $category->id,
        'subject' => 'Event suggestion',
        'payload' => 'We should host a hackathon.',
        'is_anonymous' => false,
    ];

    $this->actingAs($user)
        ->post(route('public.aspirations.store'), $data)
        ->assertRedirect();

    $this->assertDatabaseHas('aspirations', [
        'subject' => 'Event suggestion',
        'user_id' => $user->id,
        'is_anonymous' => 0,
    ]);
});

it('enforces rate limiting on anonymous submissions', function () {
    $category = AspirationCategory::factory()->create();
    
    // Simulate hitting rate limit by calling the route multiple times
    for ($i = 0; $i < 3; $i++) {
        $this->post(route('public.aspirations.store'), [
            'category_id' => $category->id,
            'subject' => "Test $i",
            'payload' => 'Test content',
            'is_anonymous' => true,
        ]);
    }

    $response = $this->post(route('public.aspirations.store'), [
        'category_id' => $category->id,
        'subject' => "Test Blocked",
        'payload' => 'Test content',
        'is_anonymous' => true,
    ]);

    $response->assertStatus(429); // Too Many Requests
});
