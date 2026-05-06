<?php

namespace App\Data\Admin\Category;

final readonly class CategoryData
{
    public function __construct(
        public string $name,
        public string $slug,
    ) {}
}
