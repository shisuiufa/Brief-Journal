<?php

namespace App\Http\Controllers;

use App\Contracts\Post\IncrementPostViewsActionInterface;
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
            ->category($request->string('category')->toString())
            ->tag($request->string('tag')->toString())
            ->with(['author', 'categories', 'tags'])
            ->latest('published_at')
            ->paginate(15);

        return PostResource::collection($posts);
    }

    public function show(string $slug, IncrementPostViewsActionInterface $incrementViews): PostResource
    {
        $post = Post::query()
            ->published()
            ->where('slug', $slug)
            ->with(['author', 'categories', 'tags'])
            ->firstOrFail();

        $post = $incrementViews->execute($post);

        return new PostResource($post);
    }
}
