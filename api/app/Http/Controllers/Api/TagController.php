<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\TagResource;
use App\Models\Tag;
use Illuminate\Http\Resources\Json\ResourceCollection;

class TagController extends Controller
{
    public function index(): ResourceCollection
    {
        $tags = Tag::query()
            ->withPublishedPostsCount()
            ->orderByDesc('posts_count')
            ->orderBy('name')
            ->limit(12)
            ->get();

        return TagResource::collection($tags);
    }
}
