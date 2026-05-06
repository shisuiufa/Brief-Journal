<?php

namespace App\Contracts\Admin\Category;

use App\Data\Admin\Category\CategoryData;
use App\Models\Category;

interface CreateCategoryActionInterface
{
    public function execute(CategoryData $data): Category;
}
