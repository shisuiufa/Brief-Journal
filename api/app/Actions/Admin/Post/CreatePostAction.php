<?php

namespace App\Actions\Admin\Post;

use App\Contracts\Admin\Post\CreatePostActionInterface;
use App\Contracts\Media\ImageStorageInterface;
use App\Data\Admin\Post\CreatePostData;
use App\Enums\Post\PostStatusEnum;
use App\Models\Post;
use Throwable;

final readonly class CreatePostAction implements CreatePostActionInterface
{
    public function __construct(
        private ImageStorageInterface $imageStorage,
    ) {}

    /**
     * @throws Throwable
     */
    public function execute(CreatePostData $data): Post
    {
        $imagePath = null;

        try {
            $imagePath = $this->imageStorage->store($data->image, 'posts');

            $post = new Post([
                'user_id' => $data->userId,
                'title' => $data->title,
                'slug' => $data->slug,
                'image' => $imagePath,
                'excerpt' => $data->excerpt,
                'content' => $data->content,
                'status' => $data->status,
                'published_at' => $data->status === PostStatusEnum::Draft ? null : now(),
            ]);

            $post->saveOrFail();

            return $post;
        } catch (Throwable $exception) {
            $this->cleanupStoredImage($imagePath);

            throw $exception;
        }
    }

    private function cleanupStoredImage(?string $imagePath): void
    {
        if ($imagePath === null) {
            return;
        }

        try {
            $this->imageStorage->delete($imagePath);
        } catch (Throwable $exception) {
            report($exception);
        }
    }
}
