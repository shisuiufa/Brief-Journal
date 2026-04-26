<?php

namespace App\Http\Controllers;

use App\Http\Resources\PostResource;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class PostController extends Controller
{
    public function index(Request $request): ResourceCollection
    {
        $posts = Post::query()
            ->published()
            ->search($request->string('search')->toString())
            ->with('author')
            ->latest('published_at')
            ->paginate(15);

        return PostResource::collection($posts);
    }

    public function show(string $slug): PostResource
    {
        $post = Post::query()
            ->published()
            ->where('slug', $slug)
            ->with('author')
            ->firstOrFail();

        return new PostResource($post);
    }
}
