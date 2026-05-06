<?php

namespace App\Contracts\Post;

use App\Models\Post;

interface IncrementPostViewsActionInterface
{
    public function execute(Post $post): Post;
}
