<?php

namespace App\Http\Controllers\Admin_api;

use App\Http\Controllers\Controller;
use App\Model\Admin\Category;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
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
            $categories = Category::all();
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
        try {
            $category = new Category();
            $category->title = $request->title;
            $category->description = $request->description;
            $category->save();

            return response()->json(['message' => 'success'], 200);
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
            $category = Category::find($id);
            return response()->json(['data' => $category], 200);
        } catch (Exception $e) {
            Log::error("Failed to get categories. " . $e->getMessage());
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }
}
