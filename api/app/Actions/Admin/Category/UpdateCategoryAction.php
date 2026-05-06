<?php

namespace App\Actions\Admin\Category;

use App\Contracts\Admin\Category\UpdateCategoryActionInterface;
use App\Data\Admin\Category\CategoryData;
use App\Models\Category;
use Throwable;

final readonly class UpdateCategoryAction implements UpdateCategoryActionInterface
{
    /**
     * @throws Throwable
     */
    public function execute(Category $category, CategoryData $data): Category
    {
        $category->updateOrFail([
            'name' => $data->name,
            'slug' => $data->slug,
        ]);

        return $category->refresh();
    }
}
