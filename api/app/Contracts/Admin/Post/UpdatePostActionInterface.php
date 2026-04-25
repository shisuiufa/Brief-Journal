<?php

namespace App\Contracts\Admin\Post;

use App\Data\Admin\Post\UpdatePostData;
use App\Models\Post;

interface UpdatePostActionInterface
{
    public function execute(Post $post, UpdatePostData $data): Post;
}
