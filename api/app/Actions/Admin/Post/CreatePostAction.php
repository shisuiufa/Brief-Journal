<?php

namespace App\Actions\Admin\Post;

use App\Contracts\Admin\Post\CreatePostActionInterface;
use App\Contracts\Media\ImageStorageInterface;
use App\Data\Admin\Post\CreatePostData;
use App\Enums\Post\PostStatusEnum;
use App\Models\Post;
use Illuminate\Support\Facades\DB;
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
            return DB::transaction(function () use ($data, &$imagePath): Post {
                $imagePath = $this->imageStorage->store($data->image, 'posts');

                return Post::query()->create([
                    'user_id' => $data->userId,
                    'title' => $data->title,
                    'slug' => $data->slug,
                    'image' => $imagePath,
                    'excerpt' => $data->excerpt,
                    'content' => $data->content,
                    'status' => $data->status,
                    'published_at' => $data->status === PostStatusEnum::Draft ? null : now(),
                ]);
            });
        } catch (Throwable $exception) {
            $this->imageStorage->delete($imagePath);

            throw $exception;
        }
    }
}
