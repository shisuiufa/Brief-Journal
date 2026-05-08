<?php

use App\Models\Post;
use App\Models\Tag;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('returns trending tags ordered by published posts count', function () {
    $popularTag = Tag::factory()->create([
        'name' => 'Vue',
        'slug' => 'vue',
    ]);

    $quietTag = Tag::factory()->create([
        'name' => 'Laravel',
        'slug' => 'laravel',
    ]);

    $draftOnlyTag = Tag::factory()->create([
        'name' => 'Drafts',
        'slug' => 'drafts',
    ]);

    Post::factory()
        ->published()
        ->count(2)
        ->create()
        ->each(fn (Post $post) => $post->tags()->sync([$popularTag->id]));

    Post::factory()
        ->published()
        ->create()
        ->tags()
        ->sync([$quietTag->id]);

    Post::factory()
        ->create()
        ->tags()
        ->sync([$draftOnlyTag->id]);

    $this->getJson('/api/tags')
        ->assertOk()
        ->assertJsonPath('data.0.slug', 'vue')
        ->assertJsonPath('data.0.posts_count', 2)
        ->assertJsonPath('data.1.slug', 'laravel')
        ->assertJsonPath('data.1.posts_count', 1)
        ->assertJsonPath('data.2.slug', 'drafts')
        ->assertJsonPath('data.2.posts_count', 0);
});
