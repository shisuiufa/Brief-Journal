<?php

namespace App\Http\Controllers\Admin;

use App\Contracts\Admin\Tag\CreateTagActionInterface;
use App\Contracts\Admin\Tag\DestroyTagActionInterface;
use App\Contracts\Admin\Tag\UpdateTagActionInterface;
use App\Data\Admin\Tag\TagData;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Tag\StoreTagRequest;
use App\Http\Requests\Admin\Tag\UpdateTagRequest;
use App\Http\Resources\TagResource;
use App\Models\Tag;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Routing\Attributes\Controllers\Authorize;

class TagController extends Controller
{
    #[Authorize('viewAny', Tag::class)]
    public function index(): ResourceCollection
    {
        $tags = Tag::query()
            ->latest()
            ->paginate(15);

        return TagResource::collection($tags);
    }

    #[Authorize('create', Tag::class)]
    public function store(StoreTagRequest $request, CreateTagActionInterface $action): JsonResponse
    {
        $validated = $request->validated();

        $tag = $action->execute(
            new TagData(
                name: $validated['name'],
                slug: $validated['slug'],
            )
        );

        return response()->json([
            'message' => 'Tag created successfully.',
            'data' => new TagResource($tag),
        ], 201);
    }

    #[Authorize('view', 'tag')]
    public function show(Tag $tag): TagResource
    {
        return new TagResource($tag);
    }

    #[Authorize('update', 'tag')]
    public function update(UpdateTagRequest $request, Tag $tag, UpdateTagActionInterface $action): JsonResponse
    {
        $validated = $request->validated();

        $tag = $action->execute(
            $tag,
            new TagData(
                name: $validated['name'],
                slug: $validated['slug'],
            )
        );

        return response()->json([
            'message' => 'Tag updated successfully.',
            'data' => new TagResource($tag),
        ]);
    }

    #[Authorize('delete', 'tag')]
    public function destroy(Tag $tag, DestroyTagActionInterface $action): JsonResponse
    {
        $action->execute($tag);

        return response()->json([
            'message' => 'Tag deleted successfully.',
        ]);
    }
}
