<?php

namespace App\Http\Controllers\Admin;

use App\Contracts\Admin\Category\CreateCategoryActionInterface;
use App\Contracts\Admin\Category\UpdateCategoryActionInterface;
use App\Data\Admin\Category\CategoryData;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Category\StoreCategoryRequest;
use App\Http\Requests\Admin\Category\UpdateCategoryRequest;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Routing\Attributes\Controllers\Authorize;

class CategoryController extends Controller
{
    #[Authorize('viewAny', Category::class)]
    public function index(): ResourceCollection
    {
        $categories = Category::query()
            ->latest()
            ->paginate(15);

        return CategoryResource::collection($categories);
    }

    #[Authorize('create', Category::class)]
    public function store(StoreCategoryRequest $request, CreateCategoryActionInterface $action): JsonResponse
    {
        $validated = $request->validated();

        $category = $action->execute(
            new CategoryData(
                name: $validated['name'],
                slug: $validated['slug'],
            )
        );

        return response()->json([
            'message' => 'Category created successfully.',
            'data' => new CategoryResource($category),
        ], 201);
    }

    #[Authorize('view', 'category')]
    public function show(Category $category): CategoryResource
    {
        return new CategoryResource($category);
    }

    #[Authorize('update', 'category')]
    public function update(UpdateCategoryRequest $request, Category $category, UpdateCategoryActionInterface $action): JsonResponse
    {
        $validated = $request->validated();

        $category = $action->execute(
            $category,
            new CategoryData(
                name: $validated['name'],
                slug: $validated['slug'],
            )
        );

        return response()->json([
            'message' => 'Category updated successfully.',
            'data' => new CategoryResource($category),
        ]);
    }

    #[Authorize('delete', 'category')]
    public function destroy(Category $category): JsonResponse
    {
        $category->delete();

        return response()->json([
            'message' => 'Category deleted successfully.',
        ]);
    }
}
