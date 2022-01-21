<?php

namespace App\Http\Controllers\Admin_api;

use App\Http\Controllers\Controller;
use App\Model\Admin\Category;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class Categories extends Controller
{
    /**
     * Get categories
     *
     * @return JsonResponse
     */
    public function list(Request $request): JsonResponse
    {
        try {
            $categories = Category::lookForOriginal($request->filter)->where("parent_id", null)
            ->orderBy('created_at', 'desc')->get()->load(["pages", "subcategories", "user"]);
            return response()->json(['data' => $categories], 200);
        } catch (Exception $e) {
            Log::error("Failed to get categories. " . $e->getMessage());
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }

    /**
     * Add category
     *
     * @return JsonResponse
     */
    public function add(Request $request): JsonResponse
    {
        Log::debug($request->user());
        $request->validate([
            'title' => 'required|unique:cms_categories',
        ]);

        try {
            $category = new Category();
            $category->title = $request->title;
            $category->parent_id = $request->parent_id ?? null;
            $category->user_id = Auth::user()->id;
            $category->save();

            return response()->json($category, 200);
        } catch (Exception $e) {
            Log::error("Failed to add new category. " . $e->getMessage());
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }

    /**
     * Get catrgory
     *
     * @return JsonResponse
     */
    public function get(Request $request, int $id): JsonResponse
    {
        try {
            $category = Category::find($id)->load("subcategories");
            return response()->json(['data' => $category], 200);
        } catch (Exception $e) {
            Log::error("Failed to get categories. " . $e->getMessage());
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }

    /**
     * Edit category
     *
     * @return JsonResponse
     */
    public function edit(Request $request, int $id): JsonResponse
    {
        $request->validate([
            'title' => 'required|unique:cms_categories,title,' . $id,
        ]);

        try {
            $category = Category::find($id);
            $category->title = $request->title;
            $category->save();

            return response()->json($category, 200);
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
    public function delete(Request $request, int $id): JsonResponse
    {
        try {
            $category = Category::find($id);
            $category->delete();

            return response()->json(['message' => 'success'], 200);
        } catch (Exception $e) {
            Log::error("Failed to delete category. " . $e->getMessage());
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }
}
