<?php

namespace App\Contracts\Admin\Tag;

use App\Models\Tag;

interface DestroyTagActionInterface
{
    public function execute(Tag $tag): void;
}
