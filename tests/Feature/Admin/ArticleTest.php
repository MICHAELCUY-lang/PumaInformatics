<?php

use App\Models\Article;
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

it('lists articles for admin', function () {
    Article::factory()->count(3)->create(['author_id' => $this->admin->id]);

    $this->actingAs($this->admin)
        ->get(route('admin.articles.index'))
        ->assertOk()
        ->assertViewIs('admin.articles.index')
        ->assertViewHas('articles');
});

it('can store a new article', function () {
    $data = [
        'title' => 'New Exhibition Opening',
        'content' => '<p>The exhibition is opening soon.</p>',
        'status' => 'draft',
        'is_featured' => true,
    ];

    $this->actingAs($this->admin)
        ->post(route('admin.articles.store'), $data)
        ->assertRedirect(route('admin.articles.index'));

    $this->assertDatabaseHas('articles', [
        'title' => 'New Exhibition Opening',
        'slug' => 'new-exhibition-opening',
        'status' => 'draft',
        'author_id' => $this->admin->id,
    ]);
});

it('calculates reading time on save', function () {
    $content = str_repeat('word ', 500); // 500 words, ~2 mins at 250 wpm

    $data = [
        'title' => 'Long Article',
        'content' => $content,
        'status' => 'published',
    ];

    $this->actingAs($this->admin)
        ->post(route('admin.articles.store'), $data);

    $this->assertDatabaseHas('articles', [
        'title' => 'Long Article',
        'reading_time_minutes' => 2,
    ]);
});

it('soft deletes articles', function () {
    $article = Article::factory()->create(['author_id' => $this->admin->id]);

    $this->actingAs($this->admin)
        ->delete(route('admin.articles.destroy', $article))
        ->assertRedirect(route('admin.articles.index'));

    $this->assertSoftDeleted('articles', ['id' => $article->id]);
});
