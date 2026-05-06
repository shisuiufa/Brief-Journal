<?php

namespace App\Actions\Admin\Category;

use App\Contracts\Admin\Category\CreateCategoryActionInterface;
use App\Data\Admin\Category\CategoryData;
use App\Models\Category;
use Throwable;

final readonly class CreateCategoryAction implements CreateCategoryActionInterface
{
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

        return $category;
    }
}
