<?php

namespace App\Actions\Admin\Post;

use App\Contracts\Admin\Post\DeletePostActionInterface;
use App\Contracts\Realtime\RealtimePublisherInterface;
use App\Enums\Post\PostStatusEnum;
use App\Enums\Realtime\RealtimeEventEnum;
use App\Models\Post;

final readonly class DeletePostAction implements DeletePostActionInterface
{
    public function __construct(
        private RealtimePublisherInterface $realtimePublisher,
    ) {}

    public function execute(Post $post): void
    {
        $wasPublished = $post->status === PostStatusEnum::Published;

        $post->delete();

        if ($wasPublished) {
            $this->realtimePublisher->publish(RealtimeEventEnum::PostDeleted, [
                'id' => $post->id,
                'slug' => $post->slug,
            ]);
        }
    }
}
