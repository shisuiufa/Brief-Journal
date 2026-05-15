<?php

namespace App\Actions\Admin\Category;

use App\Contracts\Admin\Category\UpdateCategoryActionInterface;
use App\Contracts\Realtime\RealtimePublisherInterface;
use App\Data\Admin\Category\CategoryData;
use App\Enums\Realtime\RealtimeEventEnum;
use App\Models\Category;
use Throwable;

final readonly class UpdateCategoryAction implements UpdateCategoryActionInterface
{
    public function __construct(
        private RealtimePublisherInterface $realtimePublisher,
    ){}
    /**
     * @throws Throwable
     */
    public function execute(Category $category, CategoryData $data): Category
    {
        $category->updateOrFail([
            'name' => $data->name,
            'slug' => $data->slug,
        ]);

        $category = $category->refresh();

        $this->realtimePublisher->publish(RealtimeEventEnum::CategoryUpdated, [
            'id' => $category->id,
        ]);

        return $category;
    }
}
