<?php

namespace App\Http\Controllers\Admin_api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateAdminPageRequest;
use App\Http\Resources\PageResource;
use App\Model\Admin\Category;
use App\Model\Admin\Page;
use App\Model\Admin\Template;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

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
            $pages = Page::lookForOriginal($request->filter)->with('template', 'categories.subcategories')->orderBy('created_at', 'desc')->paginate(100);
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
            $page->save();

            $page->categories()->sync(collect($request->category_id ?? [])->pluck('id')->toArray());
            $page->subcategories()->sync(collect($request->subcategories ?? [])->pluck('id')->toArray());

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
            $page = Page::whereId($id)->with('template')->first();

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
    public function update(Request $request, int $id)
    {
        $request->validate([
            'title' => 'required|unique:cms_pages,title,'. $id,
        ]);

        try {
            $page = Page::find($id);

            $this->authorize('update', $page, Auth::user());

            $page->title = $request->title;
            $page->template_id = $request->template_id;
            $page->content = $request->content;
            $page->published = $request->published;
            $page->published_from = $request->published_from;
            $page->published_to = $request->published_to;
            $page->type = $request->type;
            $page->save();

            $page->categories()->sync(collect($request->category_id ?? [])->pluck('id')->toArray());
            $page->subcategories()->sync(collect($request->subcategories ?? [])->pluck('id')->toArray());

            $page->load('template', 'categories');

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
            $page = Page::find($id);

            $this->authorize('delete', $page, Auth::user());

            $page->categories()->detach();
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
            $page = Page::find($id);

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
