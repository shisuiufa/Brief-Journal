<?php

use App\Contracts\Admin\Post\UpdatePostActionInterface;
use App\Data\Admin\Post\UpdatePostData;
use App\Enums\Post\PostStatusEnum;
use App\Models\Post;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Validation\ValidationException;

uses(RefreshDatabase::class);


$updatePost = function (Post $post, UpdatePostData $data): Post {
    return app(UpdatePostActionInterface::class)->execute($post, $data);
};

$createUpdateData = function (
    array $overrides = [],
): UpdatePostData {
    return new UpdatePostData(
        title: $overrides['title'] ?? 'Test post',
        slug: $overrides['slug'] ?? 'test-post',
        image: $overrides['image'] ?? null,
        excerpt: $overrides['excerpt'] ?? 'Short excerpt',
        content: $overrides['content'] ?? 'Post content',
        status: $overrides['status'] ?? null,
    );
};


it('updates a draft post', function () use ($createUpdateData, $updatePost) {
    $post = Post::factory()->create([
        'status' => PostStatusEnum::Draft,
        'published_at' => null,
        'title' => 'Old title',
        'slug' => 'old-title',
    ]);

    $updated = $updatePost($post, $createUpdateData([
        'title' => 'New title',
        'slug' => 'new-title',
        'excerpt' => 'New excerpt',
        'content' => 'New content',
        'status' => PostStatusEnum::Published,
    ]));

    expect($updated->title)->toBe('New title')
        ->and($updated->slug)->toBe('new-title')
        ->and($updated->excerpt)->toBe('New excerpt')
        ->and($updated->content)->toBe('New content')
        ->and($updated->status)->toBe(PostStatusEnum::Published)
        ->and($updated->published_at)->not->toBeNull();

    $this->assertDatabaseHas('posts', [
        'id' => $post->id,
        'title' => 'New title',
        'slug' => 'new-title',
        'status' => PostStatusEnum::Published->value,
    ]);
});

it('updates a published post without changing its publication timestamp', function () use ($createUpdateData, $updatePost) {
    $post = Post::factory()->published()->create();

    $originalPublishedAt = $post->published_at->copy();

    $post = $updatePost($post, $createUpdateData([
        'status' => PostStatusEnum::Published,
        'title' => 'new title',
    ]));

    expect($post->title)->toBe('new title')
        ->and($post->published_at->toDateTimeString())
        ->toBe($originalPublishedAt->toDateTimeString());
});

it('replaces the current image when a new image is provided', function () use ($createUpdateData, $updatePost) {
    $post = Post::factory()->create([
        'image' => 'posts/old-image.jpg',
    ]);

    $newImage = UploadedFile::fake()->create('new-image.jpg', 100, 'image/jpeg');

    $storage = bindMockImageStorage();

    $storage->shouldReceive('store')
        ->once()
        ->with($newImage, 'posts')
        ->andReturn('posts/new-image.jpg');
    $storage->shouldReceive('delete')
        ->once()
        ->with('posts/old-image.jpg')
        ->andReturnNull();

    $post = $updatePost($post, $createUpdateData([
        'status' => PostStatusEnum::Published,
        'image' => $newImage,
    ]));

    expect($post->image)->toBe('posts/new-image.jpg');
});

it('keeps the current image when no new image is provided', function () use ($createUpdateData, $updatePost) {
    $post = Post::factory()->create([
        'image' => 'posts/old-image.jpg',
    ]);

    $storage = bindMockImageStorage();

    $storage->shouldNotReceive('store');
    $storage->shouldNotReceive('delete');

    $post = $updatePost(
        $post,
        $createUpdateData(
            [
                'status' => PostStatusEnum::Published,
                'image' => null,
                'title' => 'Updated title',
            ]
        )
    );

    expect($post->image)->toBe('posts/old-image.jpg');
});

it('prevents changing the status of a published post', function () use ($createUpdateData, $updatePost) {
    $post = Post::factory()->published()->create();

    $storage = bindMockImageStorage();

    $storage->shouldNotReceive('store');
    $storage->shouldNotReceive('delete');

    expect(fn() => $updatePost($post, $createUpdateData([
        'status' => PostStatusEnum::Draft,
    ])))->toThrow(ValidationException::class);
});

it('reports cleanup failure and rethrows the original update exception', function () use ($updatePost, $createUpdateData) {
    $post = Post::factory()->create([
        'image' => 'posts/old-image.jpg',
    ]);

    $newImage = UploadedFile::fake()->create('new-image.jpg', 100, 'image/jpeg');
    $originalException = new RuntimeException('update failed');

    $storage = bindMockImageStorage();

    $storage->shouldReceive('store')
        ->once()
        ->with($newImage, 'posts')
        ->andReturn('posts/new-image.jpg');
    $storage->shouldReceive('delete')
        ->once()
        ->with('posts/new-image.jpg')
        ->andThrow(new RuntimeException('cleanup failed'));

    Post::saving(function (Post $model) use ($post, $originalException): void {
        if ($model->is($post)) {
            throw $originalException;
        }
    });

    expect(fn() => $updatePost($post, $createUpdateData([
        'status' => PostStatusEnum::Published,
        'image' => $newImage,
    ])))->toThrow(fn(RuntimeException $exception) => $exception->getMessage() === 'update failed');
});

it('deletes only the newly stored image when update fails and keeps old image untouched', function () use ($createUpdateData, $updatePost) {
    $post = Post::factory()->create([
        'title' => 'Old title',
        'slug' => 'old-title',
        'image' => 'posts/old-image.jpg',
        'status' => PostStatusEnum::Draft,
        'published_at' => null,
    ]);

    $newImage = UploadedFile::fake()->create('new-image.jpg', 100, 'image/jpeg');

    $storage = bindMockImageStorage();

    $storage->shouldReceive('store')
        ->once()
        ->with($newImage, 'posts')
        ->andReturn('posts/new-image.jpg');

    $storage->shouldReceive('delete')
        ->once()
        ->with('posts/new-image.jpg');

    $storage->shouldNotReceive('delete', 'posts/old-image.jpg');

    Post::saving(function (Post $model) use ($post): void {
        if ($model->is($post)) {
            throw new RuntimeException('update failed');
        }
    });

    expect(fn() => $updatePost($post, $createUpdateData([
        'title' => 'New title',
        'slug' => 'new-title',
        'status' => PostStatusEnum::Published,
        'image' => $newImage,
    ])))->toThrow('update failed');

    $this->assertDatabaseHas('posts', [
        'id' => $post->id,
        'title' => 'Old title',
        'slug' => 'old-title',
        'image' => 'posts/old-image.jpg',
        'status' => PostStatusEnum::Draft->value,
    ]);
});
