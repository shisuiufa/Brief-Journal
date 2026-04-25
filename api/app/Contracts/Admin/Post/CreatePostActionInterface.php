<?php

namespace App\Contracts\Admin\Post;

use App\Data\Admin\Post\CreatePostData;
use App\Models\Post;

interface CreatePostActionInterface
{
    public function execute(CreatePostData $data): Post;
}
