<?php

namespace App\Actions\Admin\Tag;

use App\Contracts\Admin\Tag\UpdateTagActionInterface;
use App\Contracts\Realtime\RealtimePublisherInterface;
use App\Data\Admin\Tag\TagData;
use App\Enums\Realtime\RealtimeEventEnum;
use App\Models\Tag;
use Throwable;

final readonly class UpdateTagAction implements UpdateTagActionInterface
{
    public function __construct(
        private RealtimePublisherInterface $realtimePublisher,
    ){}
    /**
     * @throws Throwable
     */
    public function execute(Tag $tag, TagData $data): Tag
    {
        $tag->updateOrFail([
            'name' => $data->name,
            'slug' => $data->slug,
        ]);

        $tag = $tag->refresh();

        $this->realtimePublisher->publish(RealtimeEventEnum::TagUpdated, [
            'id' => $tag->id,
        ]);

        return $tag;
    }
}
