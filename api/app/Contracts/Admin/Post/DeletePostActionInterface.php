<?php

namespace App\Contracts\Admin\Post;

use App\Models\Post;

interface DeletePostActionInterface
{
    public function execute(Post $post): void;
}
