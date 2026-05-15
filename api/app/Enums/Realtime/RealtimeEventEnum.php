<?php

namespace App\Enums\Realtime;

enum RealtimeEventEnum: string
{
    case PostPublished = 'post.published';
    case PostUpdated = 'post.updated';
    case PostDeleted = 'post.deleted';
    case TagCreated = 'tag.created';
    case TagDeleted = 'tag.deleted';
    case TagUpdated = 'tag.updated';
    case CategoryCreated = 'category.created';
    case CategoryDeleted = 'category.deleted';
    case CategoryUpdated = 'category.updated';
    case UserCreated = 'user.created';
    case UserDeleted = 'user.deleted';
    case UserUpdated = 'user.updated';
}
