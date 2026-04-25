<?php

namespace App\Http\Controllers\Admin;

use App\Contracts\Admin\Post\CreatePostActionInterface;
use App\Contracts\Admin\Post\UpdatePostActionInterface;
use App\Data\Admin\Post\CreatePostData;
use App\Data\Admin\Post\UpdatePostData;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Post\StorePostRequest;
use App\Http\Requests\Admin\Post\UpdatePostRequest;
use App\Http\Resources\PostResource;
use App\Models\Post;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Routing\Attributes\Controllers\Authorize;

class PostController extends Controller
{
    #[Authorize('viewAny', Post::class)]
    public function index(Request $request): ResourceCollection
    {
        $posts = Post::query()
            ->search($request->string('search')->toString())
            ->with('author')
            ->latest()
            ->paginate(15);

        return PostResource::collection($posts);
    }

    #[Authorize('create', Post::class)]
    public function store(StorePostRequest $request, CreatePostActionInterface $action): JsonResponse
    {
        $post = $action->execute(CreatePostData::fromArray([
            ...$request->validated(),
            'user_id' => $request->user()->id,
            'image' => $request->file('image'),
        ]));

        return response()->json([
            'message' => 'Post created successfully.',
            'data' => new PostResource($post),
        ], 201);
    }

    #[Authorize('view', 'post')]
    public function show(Post $post): PostResource
    {
        return new PostResource($post);
    }

    #[Authorize('update', 'post')]
    public function update(UpdatePostRequest $request, Post $post, UpdatePostActionInterface $action): JsonResponse
    {
        $post = $action->execute(
            $post,
            UpdatePostData::fromArray([
                ...$request->validated(),
                'user_id' => $request->user()->id,
                'image' => $request->file('image'),
            ]));

        return response()->json([
            'message' => 'Post updated successfully.',
            'data' => new PostResource($post->load('author')),
        ]);
    }

    #[Authorize('delete', 'post')]
    public function destroy(Post $post): JsonResponse
    {
        $post->delete();

        return response()->json([
            'message' => 'Post deleted successfully.',
        ]);
    }
}
