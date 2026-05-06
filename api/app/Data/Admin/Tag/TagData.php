<?php

namespace App\Data\Admin\Tag;

final readonly class TagData
{
    public function __construct(
        public string $name,
        public string $slug,
    ) {}
}
