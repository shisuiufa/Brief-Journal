<?php

namespace App\Actions\Admin\Category;

use App\Contracts\Admin\Category\DestroyCategoryActionInterface;
use App\Contracts\Realtime\RealtimePublisherInterface;
use App\Enums\Realtime\RealtimeEventEnum;
use App\Models\Category;
use Throwable;

final readonly class DestroyCategoryAction implements DestroyCategoryActionInterface
{
    public function __construct(
        private RealtimePublisherInterface $realtimePublisher,
    ) {}

    /**
     * @throws Throwable
     */
    public function execute(Category $category): void
    {
        $category->delete();

        $this->realtimePublisher->publish(RealtimeEventEnum::CategoryDeleted, [
            'id' => $category->id,
        ]);
    }
}
