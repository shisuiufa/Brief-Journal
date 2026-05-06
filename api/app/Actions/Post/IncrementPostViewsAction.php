<?php

namespace App\Actions\Post;

use App\Contracts\Post\IncrementPostViewsActionInterface;
use App\Models\Post;

final readonly class IncrementPostViewsAction implements IncrementPostViewsActionInterface
{
    public function execute(Post $post): Post
    {
        Post::query()
            ->whereKey($post->getKey())
            ->increment('views_count');

        return $post->refresh();
    }
}
