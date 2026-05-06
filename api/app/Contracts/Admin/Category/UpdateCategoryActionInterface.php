<?php

namespace App\Contracts\Admin\Category;

use App\Data\Admin\Category\CategoryData;
use App\Models\Category;

interface UpdateCategoryActionInterface
{
    public function execute(Category $category, CategoryData $data): Category;
}
