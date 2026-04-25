<?php

use App\Contracts\Admin\Post\CreatePostActionInterface;
use App\Data\Admin\Post\CreatePostData;
use App\Enums\Post\PostStatusEnum;
use App\Models\Post;
use App\Models\User;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;

uses(RefreshDatabase::class);

function createPost(CreatePostData $data): Post
{
    return app(CreatePostActionInterface::class)->execute($data);
}

function createPostData(
    User $user,
    PostStatusEnum $status = PostStatusEnum::Draft,
    array $overrides = [],
): CreatePostData {
    return new CreatePostData(
        userId: $user->id,
        title: $overrides['title'] ?? 'Test post',
        slug: $overrides['slug'] ?? 'test-post',
        image: array_key_exists('image', $overrides)
            ? $overrides['image']
            : UploadedFile::fake()->create('post.jpg', 100, 'image/jpeg'),
        excerpt: $overrides['excerpt'] ?? 'Short excerpt',
        content: $overrides['content'] ?? 'Post content',
        status: $overrides['status'] ?? $status,
    );
}

describe('CreatePostAction', function () {
    it('creates a draft post and keeps published_at null', function () {
        $user = User::factory()->create();

        $storage = bindMockImageStorage();

        $storage->shouldReceive('store')
            ->once()
            ->andReturn('posts/test.jpg');

        $post = createPost(createPostData($user));

        expect($post)
            ->toBeInstanceOf(Post::class)
            ->and($post->user_id)->toBe($user->id)
            ->and($post->image)->toBe('posts/test.jpg')
            ->and($post->published_at)->toBeNull();

        $this->assertDatabaseHas('posts', [
            'id' => $post->id,
            'user_id' => $user->id,
            'title' => 'Test post',
            'slug' => 'test-post',
            'image' => 'posts/test.jpg',
            'status' => PostStatusEnum::Draft->value,
        ]);
    });

    it('creates a published post and sets published_at', function () {
        $user = User::factory()->create();

        $storage = bindMockImageStorage();

        $storage->shouldReceive('store')
            ->once()
            ->andReturn('posts/test.jpg');

        $post = createPost(createPostData($user, PostStatusEnum::Published));

        expect($post->published_at)->not()->toBeNull();

        $this->assertDatabaseHas('posts', [
            'id' => $post->id,
            'status' => PostStatusEnum::Published->value,
        ]);
    });

    it('deletes stored image when post creation fails', function () {
        $user = User::factory()->create();

        Post::factory()->create([
            'slug' => 'test-post',
        ]);

        $storage = bindMockImageStorage();

        $storage->shouldReceive('store')
            ->once()
            ->andReturn('posts/test.jpg');
        $storage->shouldReceive('delete')
            ->once()
            ->with('posts/test.jpg');

        expect(fn () => createPost(createPostData($user)))
            ->toThrow(QueryException::class);

        $this->assertDatabaseCount('posts', 1);
        $this->assertDatabaseMissing('posts', [
            'user_id' => $user->id,
            'image' => 'posts/test.jpg',
        ]);
    });
});
