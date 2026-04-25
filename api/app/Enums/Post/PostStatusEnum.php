<?php

namespace App\Enums\Post;

enum PostStatusEnum: string
{
    case Draft = 'draft';
    case Published = 'published';
}
