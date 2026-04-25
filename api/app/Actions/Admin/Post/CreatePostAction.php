<?php

namespace App\Actions\Admin\Post;

use App\Contracts\Admin\Post\CreatePostActionInterface;
use App\Data\Admin\Post\CreatePostData;
use App\Models\Post;

final readonly class CreatePostAction implements CreatePostActionInterface
{

    public function execute(CreatePostData $data): Post
    {
        // TODO: Implement execute() method.
    }
}
