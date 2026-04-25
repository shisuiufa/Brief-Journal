<?php

namespace App\Actions\Admin\Post;

use App\Contracts\Admin\Post\UpdatePostActionInterface;
use App\Contracts\Media\ImageStorageInterface;
use App\Data\Admin\Post\UpdatePostData;
use App\Enums\Post\PostStatusEnum;
use App\Models\Post;
use Carbon\CarbonInterface;
use Illuminate\Validation\ValidationException;
use Throwable;

final readonly class UpdatePostAction implements UpdatePostActionInterface
{
    public function __construct(
        private ImageStorageInterface $imageStorage,
    ) {}

    /**
     * @throws Throwable
     */
    public function execute(Post $post, UpdatePostData $data): Post
    {
        $this->ensureStatusCanBeUpdated($post, $data);

        $oldImage = $post->image;
        $newImage = null;

        try {
            $newImage = $this->storeReplacementImage($data);

            $post->updateOrFail($this->buildAttributes($post, $data, $newImage));
        } catch (Throwable $exception) {
            $this->cleanupStoredImage($newImage);

            throw $exception;
        }

        if($newImage !== null) {
            $this->cleanupStoredImage($oldImage);
        }

        return $post->refresh();
    }

    private function buildAttributes(Post $post, UpdatePostData $data, ?string $newImage): array
    {
        $attributes = [
            'title' => $data->title,
            'slug' => $data->slug,
            'excerpt' => $data->excerpt,
            'content' => $data->content,
            'status' => $data->status,
            'published_at' => $this->determinePublishedAtForUpdate($post, $data),
        ];

        if ($newImage !== null) {
            $attributes['image'] = $newImage;
        }

        return $attributes;
    }

    private function ensureStatusCanBeUpdated(Post $post, UpdatePostData $data): void
    {
        if ($post->status === PostStatusEnum::Draft || $post->status === $data->status) {
            return;
        }

        throw ValidationException::withMessages([
            'status' => ['You cannot change the status after publication.'],
        ]);
    }

    private function storeReplacementImage(UpdatePostData $data): ?string
    {
        if ($data->image === null) {
            return null;
        }

        return $this->imageStorage->store($data->image, 'posts');
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

    private function determinePublishedAtForUpdate(Post $post, UpdatePostData $data): ?CarbonInterface
    {
        if ($post->published_at !== null) {
            return $post->published_at;
        }

        if ($data->status === PostStatusEnum::Published) {
            return now();
        }

        return null;
    }
}
