<?php

namespace App\Contracts\Admin\Tag;

use App\Data\Admin\Tag\TagData;
use App\Models\Tag;

interface CreateTagActionInterface
{
    public function execute(TagData $data): Tag;
}
