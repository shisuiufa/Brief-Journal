<?php

namespace App\Enums\Realtime;

enum RealtimeEventEnum: string
{
    case PostPublished = 'post.published';
    case PostUpdated = 'post.updated';
    case PostDeleted = 'post.deleted';
}
