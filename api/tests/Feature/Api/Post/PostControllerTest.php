<?php

use App\Models\Category;
use App\Models\Post;
use App\Models\Tag;
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

it('filters published posts by category slug', function () {
    $category = Category::factory()->create([
        'slug' => 'laravel',
    ]);

    $matchingPost = Post::factory()->published()->create([
        'title' => 'Laravel post',
    ]);

    $otherPost = Post::factory()->published()->create([
        'title' => 'Other post',
    ]);

    $matchingPost->categories()->sync([$category->id]);

    $this->getJson('/api/posts?category=laravel')
        ->assertOk()
        ->assertJsonCount(1, 'data')
        ->assertJsonPath('data.0.id', $matchingPost->id)
        ->assertJsonMissing([
            'id' => $otherPost->id,
        ]);
});

it('filters published posts by tag slug', function () {
    $tag = Tag::factory()->create([
        'slug' => 'passport',
    ]);

    $matchingPost = Post::factory()->published()->create([
        'title' => 'Passport post',
    ]);

    $otherPost = Post::factory()->published()->create([
        'title' => 'Other post',
    ]);

    $matchingPost->tags()->sync([$tag->id]);

    $this->getJson('/api/posts?tag=passport')
        ->assertOk()
        ->assertJsonCount(1, 'data')
        ->assertJsonPath('data.0.id', $matchingPost->id)
        ->assertJsonMissing([
            'id' => $otherPost->id,
        ]);
});

it('shows published post with categories and tags', function () {
    $category = Category::factory()->create([
        'name' => 'Laravel',
        'slug' => 'laravel',
    ]);

    $tag = Tag::factory()->create([
        'name' => 'Passport',
        'slug' => 'passport',
    ]);

    $post = Post::factory()->published()->create([
        'slug' => 'published-post',
    ]);

    $post->categories()->sync([$category->id]);
    $post->tags()->sync([$tag->id]);

    $this->getJson('/api/posts/published-post')
        ->assertOk()
        ->assertJsonPath('data.slug', $post->slug)
        ->assertJsonPath('data.categories.0.slug', 'laravel')
        ->assertJsonPath('data.tags.0.slug', 'passport');
});
