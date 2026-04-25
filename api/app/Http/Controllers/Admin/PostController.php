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

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): ResourceCollection
    {
        $this->authorize('viewAny', Post::class);

        $posts = Post::query()
            ->search($request->string('search')->toString())
            ->with('author')
            ->latest()
            ->paginate(15);

        return PostResource::collection($posts);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePostRequest $request, CreatePostActionInterface $action): JsonResponse
    {
        $this->authorize('create', Post::class);

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

    /**
     * Display the specified resource.
     */
    public function show(Post $post): PostResource
    {
        $this->authorize('view', $post);

        return new PostResource($post);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePostRequest $request, Post $post, UpdatePostActionInterface $action): JsonResponse
    {
        $this->authorize('update', $post);

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

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post): JsonResponse
    {
        $this->authorize('delete', $post);

        $post->delete();

        return response()->json([
            'message' => 'Post deleted successfully.',
        ]);
    }
}
