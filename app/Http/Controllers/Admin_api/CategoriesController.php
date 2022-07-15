<?php

namespace App\Http\Controllers\Admin_api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateAdminCategoryRequest;
use App\Http\Requests\UpdateAdminCategoryRequest;
use App\Http\Resources\CategoryResource;
use App\Jobs\DeleteMultipleCategories;
use App\Model\Admin\Category;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CategoriesController extends Controller
{
    /**
     * Get categories
     *
     * @return AnonymousResourceCollection
     */
    public function index(Request $request)
    {
        $this->authorize('viewAny', Category::class, Auth::user());

        try {
            $categories = Category::where("parent_id", null);

            $categories = $this->filters($categories, $request);

            $categories->with(["pages", "subcategories", "user", "image"]);
            $categories->tableSort($request);
            $categories = $categories->paginate($request->per_page ?? 50);
            return CategoryResource::collection($categories);
        } catch (Exception $e) {
            Log::error("Failed to get categories. " . $e->getMessage());
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }

    private function filters($categories, $request)
    {
        $categories->lookForOriginal($request->filter);
        return $categories;
    }

    /**
     * Add category
     *
     * @return CategoryResource
     */
    public function store(CreateAdminCategoryRequest $request)
    {
        $this->authorize('create', Category::class, Auth::user());

        try {
            $category = new Category();
            $category->title = $request->title;
            $category->parent_id = $request->parent_id ?? null;
            $category->user_id = Auth::user()->id;
            if ($request->category_image) {
                $category->image_id = $request->category_image["id"];
            }
            $category->save();

            $parent_id = $category->id;

            if ($request->subcategories) {
                foreach ($request->subcategories as $item) {
                    $subcategory = new Category();
                    $subcategory->title = $item["title"];
                    $subcategory->parent_id = $parent_id;
                    $subcategory->user_id = Auth::user()->id;
                    $subcategory->save();
                }
            }

            $category->load("subcategories");

            Category::all()->searchable();

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
    public function show(Request $request, int $id)
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
    public function update(UpdateAdminCategoryRequest $request, int $id)
    {
        try {
            $category = Category::find($id);

            $this->authorize('update', $category, Auth::user());

            $category->title = $request->title;
            $category->image_id = $request->category_image["id"];
            $category->save();

            $this->syncSubcategories($category, $request->subcategories);

            Category::all()->searchable();

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
    public function destroy(int $id): JsonResponse
    {
        try {
            DB::beginTransaction();

            $category = Category::find($id);

            $this->authorize('delete', $category, Auth::user());

            $this->syncSubcategories($category, []);

            $category->pages()->detach();

            $category->delete();

            Category::all()->searchable();

            DB::commit();

            return response()->json(['message' => 'success'], 200);
        } catch (Exception $e) {
            DB::rollBack();
            Log::error("Failed to delete category. " . $e->getMessage());
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }

    /**
     * Sync Subcategories
     *
     * @param Category $category
     * @param Category $subcategories
     * @return void
     */
    public function syncSubcategories($category, $subcategories)
    {
        $currentSubcategories = $category->subcategories()->get();
        foreach ($currentSubcategories ?? [] as $currentSubcategory) {
            if (!in_array($currentSubcategory->id, collect($subcategories)->pluck('id')->toArray())) {
                $currentSubcategory->pages()->detach();
                $currentSubcategory->delete();
            } else {
                $key = array_search($currentSubcategory->id, array_column($subcategories, 'id'));
                $currentSubcategory->title = $subcategories[$key]['title'] ?? $currentSubcategory->title;
                $currentSubcategory->save();
            }
        }
        
        foreach ($subcategories ?? [] as $sub) {
            if (array_key_exists('new', $sub) && $sub['new']) {
                $cat = new Category();
                $cat->title = $sub['title'];
                $cat->parent_id = $category->id ?? null;
                $cat->user_id = Auth::user()->id;
                $cat->save();
            }
        }
    }

    public function deleteMultiple(Request $request)
    {
        try {
            $ids = $request->selected;
        
            // authorize action
            $categories = Category::findOrFail($ids);
            foreach ($categories as $category) {
                $this->authorize('delete', $category, Auth::user());
            }

            // start job
            DeleteMultipleCategories::dispatch($ids, Auth::user());

            return response()->json(['message' => 'success'], 200);
        } catch (Exception $e) {
            Log::error("Failed to bulk delete categories. " . $e->getMessage());
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }

    public function widgets(Request $request)
    {
        return [
            [
                "Categories",
                $this->categoryCount($request),
            ],
            [
                "Subcategories",
                $this->subcategoryCount($request),
            ],
            [
                "Popular category",
                $this->popularCategory($request),
            ],
            [
                "Popular subcategory",
                $this->popularSubcategory($request),
            ]

        ];
    }

    public function categoryCount($request)
    {
        try {
            $categories = Category::where("parent_id", null);
            $categories = $this->filters($categories, $request);
            return $categories->count();
        } catch (Exception $e) {
            Log::warning("(categories widget) Failed to get category count. " . $e->getMessage());
            return "0";
        }
    }

    public function subcategoryCount($request)
    {
        try {
            // this does not work, full text search is being done on categories and subcategories, that messes it up
            $categories = Category::where("parent_id", '!=', null);
            $categories = $this->filters($categories, $request);
            return $categories->count();
        } catch (Exception $e) {
            Log::warning("(categories widget) Failed to get subcategory count. " . $e->getMessage());
            return "0";
        }
    }

    public function popularCategory($request)
    {
        try {
            $categories = Category::where("parent_id", null);
            $categories = $this->filters($categories, $request);
            $categories->where("parent_id", null)->with('pages')->get()->sortByDesc(function ($category) {
                return $category->pages->count();
            });
            return $categories->first()->title;
        } catch (Exception $e) {
            Log::warning("(categories widget) Failed to get most popular category. " . $e->getMessage());
            return "-";
        }
    }

    public function popularSubcategory($request)
    {
        try {
            // this does not work, full text search is being done on categories and subcategories, that messes it up
            $categories = Category::where("parent_id", '!=', null);
            $categories = $this->filters($categories, $request);
            return $categories->where("parent_id", '!=', null)->with('pages')->get()->sortByDesc(function ($category) {
                return $category->pages->count();
            })->first()->title;
        } catch (Exception $e) {
            Log::warning("(categories widget) Failed to get most popular subcategory. " . $e->getMessage());
            return "-";
        }
    }
}
