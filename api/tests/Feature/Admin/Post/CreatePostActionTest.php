<?php

use App\Contracts\Admin\Post\CreatePostActionInterface;
use App\Data\Admin\Post\CreatePostData;
use App\Enums\Post\PostStatusEnum;
use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

uses(RefreshDatabase::class);

beforeEach(function () {
    Storage::fake('public');

    config([
        'filesystems.default' => 'public',
    ]);
});

function createPost(CreatePostData $data): Post
{
    return app(CreatePostActionInterface::class)->execute($data);
}

function createPostData(array $overrides = []): CreatePostData
{
    $user = $overrides['user'] ?? User::factory()->create();

    return new CreatePostData(
        userId: $user->id,
        title: $overrides['title'] ?? 'Test post',
        slug: $overrides['slug'] ?? 'test-post',
        image: $overrides['image'] ?? UploadedFile::fake()->create('cover.jpg', 100, 'image/jpeg'),
        excerpt: $overrides['excerpt'] ?? 'Test excerpt',
        content: $overrides['content'] ?? 'Test content',
        status: $overrides['status'] ?? PostStatusEnum::Draft,
        publishedAt: $overrides['publishedAt'] ?? null,
    );
}

it('creates a post with stored image path', function () {
    $post = createPost(createPostData());

    expect($post->title)->toBe('Test post')
        ->and($post->slug)->toBe('test-post')
        ->and($post->status)->toBe(PostStatusEnum::Draft)
        ->and($post->image)->toStartWith('posts/');

    $this->assertDatabaseHas('posts', [
        'id' => $post->id,
        'title' => 'Test post',
        'slug' => 'test-post',
    ]);

    Storage::disk('public')->assertExists($post->image);
});
