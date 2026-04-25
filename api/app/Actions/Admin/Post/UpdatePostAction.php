<?php

namespace App\Actions\Admin\Post;

use App\Contracts\Admin\Post\UpdatePostActionInterface;
use App\Data\Admin\Post\UpdatePostData;
use App\Models\Post;

final readonly class UpdatePostAction implements  UpdatePostActionInterface
{

    public function execute(Post $post, UpdatePostData $data): Post
    {
        // TODO: Implement execute() method.
    }
}
