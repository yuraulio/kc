<?php

namespace App\Http\Controllers\Admin_api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateAdminCategoryRequest;
use App\Http\Requests\UpdateAdminCategoryRequest;
use App\Http\Resources\CategoryResource;
use App\Model\Admin\Category;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class CategoriesController extends Controller
{
    /**
     * Get categories
     *
     * @return AnonymousResourceCollection
     */
    public function index(Request $request): AnonymousResourceCollection
    {
        $this->authorize('viewAny', Category::class, Auth::user());

        try {
            $categories = Category::lookForOriginal($request->filter)->where("parent_id", null)
            ->orderBy('created_at', 'desc')->with(["pages", "subcategories", "user"])->paginate(100);
            return CategoryResource::collection($categories);
        } catch (Exception $e) {
            Log::error("Failed to get categories. " . $e->getMessage());
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }

    /**
     * Add category
     *
     * @return CategoryResource
     */
    public function store(CreateAdminCategoryRequest $request): CategoryResource
    {
        $this->authorize('create', Category::class, Auth::user());

        try {
            $category = new Category();
            $category->title = $request->title;
            $category->parent_id = $request->parent_id ?? null;
            $category->user_id = Auth::user()->id;
            $category->save();

            return new CategoryResource($category);
        } catch (Exception $e) {
            Log::error("Failed to add new category. " . $e->getMessage());
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }

    /**
     * Get catrgory
     *
     * @return CategoryResource
     */
    public function show(Request $request, int $id): CategoryResource
    {
        try {
            $category = Category::find($id)->load("subcategories");

            $this->authorize('view', $category, Auth::user());

            return new CategoryResource($category);
        } catch (Exception $e) {
            Log::error("Failed to get categories. " . $e->getMessage());
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }

    /**
     * Edit category
     *
     * @return CategoryResource
     */
    public function update(UpdateAdminCategoryRequest $request, int $id): CategoryResource
    {
        try {
            $category = Category::find($id);

            $this->authorize('update', $category, Auth::user());

            $category->title = $request->title;
            $category->save();

            return new CategoryResource($category->load(["pages", "subcategories", "user"]));
        } catch (Exception $e) {
            Log::error("Failed to edit category. " . $e->getMessage());
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }

    /**
     * Delete category
     *
     * @return JsonResponse
     */
    public function destroy(Request $request, int $id): JsonResponse
    {
        try {
            $category = Category::find($id);

            $this->authorize('delete', $category, Auth::user());

            $category->delete();

            return response()->json(['message' => 'success'], 200);
        } catch (Exception $e) {
            Log::error("Failed to delete category. " . $e->getMessage());
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }
}
