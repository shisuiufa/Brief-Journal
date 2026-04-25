<?php

use App\Contracts\Admin\Post\UpdatePostActionInterface;
use App\Data\Admin\Post\UpdatePostData;
use App\Enums\Post\PostStatusEnum;
use App\Models\Post;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

function updatePost(Post $post, UpdatePostData $data): Post
{
    return app(UpdatePostActionInterface::class)->execute($post, $data);
}

it('clears excerpt and published at when they are explicitly set to null', function () {
    $post = Post::factory()->published()->create([
        'excerpt' => 'Existing excerpt',
    ]);

    $updatedPost = updatePost($post, UpdatePostData::fromArray([
        'excerpt' => null,
        'published_at' => null,
        'status' => PostStatusEnum::Draft->value,
    ]));

    expect($updatedPost->excerpt)->toBeNull()
        ->and($updatedPost->published_at)->toBeNull()
        ->and($updatedPost->status)->toBe(PostStatusEnum::Draft);
});

it('keeps excerpt and published at when they are omitted', function () {
    $post = Post::factory()->published()->create([
        'excerpt' => 'Existing excerpt',
    ]);

    $updatedPost = updatePost($post, UpdatePostData::fromArray([
        'title' => 'Updated title',
    ]));

    expect($updatedPost->title)->toBe('Updated title')
        ->and($updatedPost->excerpt)->toBe('Existing excerpt')
        ->and($updatedPost->published_at)->not->toBeNull();
});
