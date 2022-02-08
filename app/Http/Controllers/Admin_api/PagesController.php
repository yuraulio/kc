<?php

namespace App\Http\Controllers\Admin_api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateAdminPageRequest;
use App\Http\Requests\UpdateAdminPageRequest;
use App\Http\Resources\PageResource;
use App\Model\Admin\Category;
use App\Model\Admin\Comment;
use App\Model\Admin\Page;
use App\Model\Admin\Redirect;
use App\Model\Admin\Template;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Ramsey\Uuid\Uuid;

class PagesController extends Controller
{
    /**
     * Get pages
     *
     * @return AnonymousResourceCollection
     */
    public function index(Request $request)
    {
        $this->authorize('viewAny', Page::class, Auth::user());

        try {
            $pages = Page::withoutGlobalScope('published')->lookForOriginal($request->filter)->with('template', 'categories.subcategories');
            if ($request->order) {
                $pages->orderBy("created_at", $request->order);
            } else {
                $pages->orderBy("created_at", "desc");
            }
            if ($request->published !== null) {
                $pages->wherePublished($request->published);
            }
            if ($request->type) {
                $pages->whereType($request->type);
            }
            if ($request->category) {
                $pages->whereHas("categories", function ($q) use ($request) {
                    $q->where("id", $request->category);
                });
            }
            if ($request->subcategory) {
                $pages->whereHas("subcategories", function ($q) use ($request) {
                    $q->where("id", $request->subcategory);
                });
            }
            
            $pages = $pages->paginate(10);
            return PageResource::collection($pages);
        } catch (Exception $e) {
            Log::error("Failed to get pages. " . $e->getMessage());
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }

    /**
     * Add page
     *
     * @return PageResource
     */
    public function store(CreateAdminPageRequest $request)
    {
        $this->authorize('create', Page::class, Auth::user());

        try {
            $page = new Page();
            $page->title = $request->title;
            $page->template_id = $request->template_id;
            $page->content = $request->content;
            $page->published = $request->published;
            $page->user_id = Auth::user()->id;
            $page->published_from = $request->published_from;
            $page->published_to = $request->published_to;
            $page->type = $request->type;
            $page->type_slug = Str::slug($request->type, '-');
            $page->uuid = Uuid::uuid4();
            $page->save();

            $categories = $request->categories ?? [];
            $subcategories = $request->subcategories ?? [];

            $page->categories()->sync(collect(array_merge($categories, $subcategories) ?? [])->pluck('id')->toArray());

            $page->load('template', 'categories.subcategories');

            return new PageResource($page);
        } catch (Exception $e) {
            Log::error("Failed to add new page. " . $e->getMessage());
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }

    /**
     * Get page
     *
     * @return PageResource
     */
    public function show(int $id)
    {
        try {
            $page = Page::withoutGlobalScope('published')->whereId($id)->with('template')->first();

            $this->authorize('view', $page, Auth::user());

            return new PageResource($page);
        } catch (Exception $e) {
            Log::error("Failed to get page. " . $e->getMessage());
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }

    /**
     * Edit page
     *
     * @return PageResource
     */
    public function update(UpdateAdminPageRequest $request, int $id)
    {
        try {
            $page = Page::withoutGlobalScope('published')->find($id);

            $this->authorize('update', $page, Auth::user());

            $old_slug = $page->slug;

            $page->title = $request->title;
            $page->template_id = $request->template_id;
            $page->content = $request->content;
            $page->published = $request->published;
            $page->published_from = $request->published_from;
            $page->published_to = $request->published_to;
            $page->type = $request->type;
            $page->type_slug = Str::slug($request->type, '-');
            $page->slug = $request->slug;
            $page->uuid = $page->uuid ?? Uuid::uuid4();
            $page->save();

            $categories = $request->categories ?? [];
            $subcategories = $request->subcategories ?? [];

            $page->categories()->sync(collect(array_merge($categories, $subcategories) ?? [])->pluck('id')->toArray());

            $page->load('template', 'categories');

            $new_slug = $page->slug;
            if ($old_slug && $new_slug != $old_slug) {
                $redirect = new Redirect();
                $redirect->page_id = $page->id;
                $redirect->old_slug = $old_slug;
                $redirect->save();
            }

            return new PageResource($page);
        } catch (Exception $e) {
            Log::error("Failed to edit page. ", [$e]);
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }

    /**
     * Delete page
     *
     * @return JsonResponse
     */
    public function destroy(int $id): JsonResponse
    {
        try {
            $page = Page::withoutGlobalScope('published')->find($id);

            $this->authorize('delete', $page, Auth::user());

            $page->categories()->detach();
            $page->subcategories()->detach();

            Comment::where("page_id", $page->id)->delete();

            $page->delete();

            return response()->json(['message' => 'success'], 200);
        } catch (Exception $e) {
            Log::error("Failed to delete page. " . $e->getMessage());
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }

    public function updatePublished(int $id): JsonResponse
    {
        try {
            $page = Page::withoutGlobalScope('published')->find($id);

            $this->authorize('publish', $page, Auth::user());

            $page->published = !$page->published;
            $page->save();

            return response()->json(['message' => 'success'], 200);
        } catch (Exception $e) {
            Log::error("Failed to publish page. " . $e->getMessage());
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }

    public function uploadImage(Request $request): JsonResponse
    {
        $this->authorize('imgUpload', Page::class, Auth::user());

        try {
            $path = Storage::disk('public')->putFile('page_files', $request->file('file'), 'public');
            $url = config('app.url'). "/uploads/" . $path;
            return response()->json(['url' => $url], 200);
        } catch (Exception $e) {
            Log::error("Failed update file . " . $e->getMessage());
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }
}
