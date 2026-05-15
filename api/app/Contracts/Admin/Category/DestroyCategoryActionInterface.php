<?php

namespace App\Contracts\Admin\Category;

use App\Models\Category;

interface DestroyCategoryActionInterface
{
    public function execute(Category $category): void;
}
