<?php

namespace App\Actions\Admin\Tag;

use App\Contracts\Admin\Tag\CreateTagActionInterface;
use App\Contracts\Realtime\RealtimePublisherInterface;
use App\Data\Admin\Tag\TagData;
use App\Enums\Realtime\RealtimeEventEnum;
use App\Models\Tag;
use Throwable;

final readonly class CreateTagAction implements CreateTagActionInterface
{
    public function __construct(
        private RealtimePublisherInterface $realtimePublisher,
    ){}

    /**
     * @throws Throwable
     */
    public function execute(TagData $data): Tag
    {
        $tag = new Tag([
            'name' => $data->name,
            'slug' => $data->slug,
        ]);

        $tag->saveOrFail();

        $this->realtimePublisher->publish(RealtimeEventEnum::TagCreated, [
            'id' => $tag->id,
        ]);

        return $tag;
    }
}
