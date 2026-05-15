<?php

namespace App\Actions\Admin\Tag;

use App\Contracts\Admin\Tag\DestroyTagActionInterface;
use App\Contracts\Realtime\RealtimePublisherInterface;
use App\Enums\Realtime\RealtimeEventEnum;
use App\Models\Tag;
use Throwable;

final readonly class DestroyTagAction implements DestroyTagActionInterface
{
    public function __construct(
        private RealtimePublisherInterface $realtimePublisher,
    ) {}

    /**
     * @throws Throwable
     */
    public function execute(Tag $tag): void
    {
        $tag->delete();

        $this->realtimePublisher->publish(RealtimeEventEnum::TagDeleted, [
            'id' => $tag->id,
        ]);
    }
}
