<?php

namespace App\Contracts\Admin\Tag;

use App\Data\Admin\Tag\TagData;
use App\Models\Tag;

interface UpdateTagActionInterface
{
    public function execute(Tag $tag, TagData $data): Tag;
}
