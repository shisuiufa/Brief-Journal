<?php

namespace App\Actions\Admin\Category;

use App\Contracts\Admin\Category\CreateCategoryActionInterface;
use App\Contracts\Realtime\RealtimePublisherInterface;
use App\Data\Admin\Category\CategoryData;
use App\Enums\Realtime\RealtimeEventEnum;
use App\Models\Category;
use Throwable;

final readonly class CreateCategoryAction implements CreateCategoryActionInterface
{
    public function __construct(
        private RealtimePublisherInterface $realtimePublisher,
    ){}
    /**
     * @throws Throwable
     */
    public function execute(CategoryData $data): Category
    {
        $category = new Category([
            'name' => $data->name,
            'slug' => $data->slug,
        ]);

        $category->saveOrFail();

        $this->realtimePublisher->publish(RealtimeEventEnum::CategoryCreated, [
            'id' => $category->id,
        ]);

        return $category;
    }
}
