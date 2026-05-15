<?php

namespace App\Http\Controllers\Api;

use App\Contracts\Post\IncrementPostViewsActionInterface;
use App\Http\Controllers\Controller;
use App\Http\Resources\PostResource;
use App\Models\Post;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class PostController extends Controller
{
    public function index(Request $request): ResourceCollection
    {
        $perPage = $request->integer('per_page', 15);
        $perPage = min(max($perPage, 1), 100);

        $posts = Post::query()
            ->published()
            ->search($request->string('search')->toString())
            ->category($request->string('category')->toString())
            ->tag($request->string('tag')->toString())
            ->with(['author', 'categories', 'tags'])
            ->latest('published_at')
            ->paginate($perPage);

        return PostResource::collection($posts);
    }

    public function featured(): PostResource|JsonResponse
    {
        $post = Post::query()
            ->published()
            ->featured()
            ->with(['author', 'categories', 'tags'])
            ->latest('featured_at')
            ->first();

        if (! $post) {
            return response()->json([
                'data' => null,
            ]);
        }

        return new PostResource($post);
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

    public function populars(): ResourceCollection
    {
        $popularPosts = Post::query()
            ->published()
            ->with(['author', 'categories', 'tags'])
            ->orderByDesc('views_count')
            ->latest('published_at')
            ->limit(4)
            ->get();

        return PostResource::collection($popularPosts);
    }
}
