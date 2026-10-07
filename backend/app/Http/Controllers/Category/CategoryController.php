<?php

namespace App\Http\Controllers\Category;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\Category\IndexCategoryRequest;
use App\Http\Requests\Category\StoreCategoryRequest;
use App\Http\Requests\Category\UpdateCategoryRequest;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use App\Services\Category\CategoryService;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class CategoryController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct(
        private readonly CategoryService $categoryService
    ) {}

    /**
     * Display a listing of categories.
     */
    public function index(IndexCategoryRequest $request): JsonResponse
    {
        $categories = $this->categoryService->index(
            $request->validated()
        );

        return ApiResponse::success(
            message: 'Categories retrieved successfully.',
            data: CategoryResource::collection($categories)
        );
    }

    /**
     * Store a newly created category.
     */
    public function store(StoreCategoryRequest $request): JsonResponse
    {
        $category = $this->categoryService->store(
            $request->validated()
        );

        return ApiResponse::success(
            message: 'Category created successfully.',
            data: new CategoryResource($category),
            status: Response::HTTP_CREATED
        );
    }

    /**
     * Display the specified category.
     */
    public function show(Category $category): JsonResponse
    {
        return ApiResponse::success(
            message: 'Category retrieved successfully.',
            data: new CategoryResource(
                $this->categoryService->show($category)
            )
        );
    }

    /**
     * Update the specified category.
     */
    public function update(
        UpdateCategoryRequest $request,
        Category $category
    ): JsonResponse {
        $category = $this->categoryService->update(
            $category,
            $request->validated()
        );

        return ApiResponse::success(
            message: 'Category updated successfully.',
            data: new CategoryResource($category)
        );
    }

    /**
     * Remove the specified category.
     */
    public function destroy(Category $category): JsonResponse
    {
        $this->categoryService->destroy($category);

        return ApiResponse::success(
            message: 'Category deleted successfully.'
        );
    }
}
