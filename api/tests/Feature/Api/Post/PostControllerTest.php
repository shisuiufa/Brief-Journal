<?php

use App\Models\Post;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('returns only published posts', function () {
    Post::factory()->published()->create([
        'title' => 'Published post',
    ]);

    Post::factory()->create([
        'title' => 'Draft post',
    ]);

    $this->getJson('/api/posts')
        ->assertOk()
        ->assertJsonCount(1, 'data')
        ->assertJsonPath('data.0.title', 'Published post');
});

it('shows published post by slug', function () {
    $post = Post::factory()->published()->create([
        'slug' => 'published-post',
    ]);

    $this->getJson('/api/posts/published-post')
        ->assertOk()
        ->assertJsonPath('data.slug', $post->slug);
});

it('does not show draft post by slug', function () {
    Post::factory()->create([
        'slug' => 'draft-post',
    ]);

    $this->getJson('/api/posts/draft-post')
        ->assertNotFound();
});

it('searches only published posts', function () {
    Post::factory()->published()->create([
        'title' => 'Laravel Testing Guide',
        'excerpt' => 'Public article',
    ]);

    Post::factory()->published()->create([
        'title' => 'Vue Components',
        'excerpt' => 'Public article',
    ]);

    Post::factory()->create([
        'title' => 'Laravel Draft Secret',
        'excerpt' => 'Draft article',
    ]);

    $this->getJson('/api/posts?search=Laravel')
        ->assertOk()
        ->assertJsonCount(1, 'data')
        ->assertJsonPath('data.0.title', 'Laravel Testing Guide');
});
