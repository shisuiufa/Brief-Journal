<?php

namespace App\Actions\Admin\Post;

use App\Contracts\Admin\Post\UpdatePostActionInterface;
use App\Contracts\Media\ImageStorageInterface;
use App\Data\Admin\Post\UpdatePostData;
use App\Enums\Post\PostStatusEnum;
use App\Models\Post;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

final readonly class UpdatePostAction implements UpdatePostActionInterface
{
    public function __construct(
        private ImageStorageInterface $imageStorage,
    ) {}

    /**
     * @throws \Throwable
     */
    public function execute(Post $post, UpdatePostData $data): Post
    {
        if ($post->status !== PostStatusEnum::Draft && $post->status !== $data->status) {
            throw ValidationException::withMessages([
                'status' => ['You cannot change the status after publication.'],
            ]);
        }

        return DB::transaction(function () use ($post, $data): Post {
            $attributes = [
                'title' => $data->title,
                'slug' => $data->slug,
                'excerpt' => $data->excerpt,
                'content' => $data->content,
                'status' => $data->status,
                'published_at' => $data->status === PostStatusEnum::Draft
                    ? null
                    : ($post->published_at ?? now()),
            ];

            if ($data->image !== null) {
                $attributes['image'] = $this->imageStorage->replace(
                    $post->image,
                    $data->image,
                    'posts',
                );
            }

            $post->update($attributes);

            return $post->refresh();
        });
    }
}
