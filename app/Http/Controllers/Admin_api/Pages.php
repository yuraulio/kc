<?php

namespace App\Http\Controllers\Admin_api;

use App\Http\Controllers\Controller;
use App\Model\Admin\Category;
use App\Model\Admin\Page;
use App\Model\Admin\Template;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class Pages extends Controller
{
    public function show($id): JsonResponse
    {
        try {
            $page = Page::whereId($id)->with('template')->first();
            return response()->json(['content' => json_encode($page)], 200);
        } catch (Exception $e) {
            Log::error("Failed to get pages. " . $e->getMessage());
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }

    /**
     * Get pages
     *
     * @return JsonResponse
     */
    public function list(Request $request): JsonResponse
    {
        try {
            $pages = Page::lookForOriginal($request->filter)->with('template')->orderBy('created_at', 'desc')->get();
            return response()->json(['data' => $pages], 200);
        } catch (Exception $e) {
            Log::error("Failed to get pages. " . $e->getMessage());
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }

    /**
     * Add page
     *
     * @return JsonResponse
     */
    public function add(Request $request): JsonResponse
    {
        $request->validate([
            'title' => 'required|unique:cms_pages',
        ]);

        try {
            $page = new Page();
            $page->title = $request->title;
            $page->description = $request->description;
            $page->template_id = $request->template_id;
            $page->content = $request->content;
            $page->published = $request->published;
            $page->save();

            $page->categories()->sync(collect($request->category_id ?? [])->pluck('id')->toArray());
            $page->load('template', 'categories');

            return response()->json($page, 200);
        } catch (Exception $e) {
            Log::error("Failed to add new page. " . $e->getMessage());
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }

    /**
     * Get page
     *
     * @return JsonResponse
     */
    public function get(int $id): JsonResponse
    {
        try {
            $page = Page::find($id);
            return response()->json(['data' => $page], 200);
        } catch (Exception $e) {
            Log::error("Failed to get page. " . $e->getMessage());
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }

    /**
     * Edit page
     *
     * @return JsonResponse
     */
    public function edit(Request $request, int $id): JsonResponse
    {
        $request->validate([
            'title' => 'required|unique:cms_pages,title,'. $id,
        ]);

        try {
            $page = Page::find($id);
            $page->title = $request->title;
            $page->description = $request->description;
            $page->template_id = $request->template_id;
            $page->content = $request->content;
            $page->published = $request->published;
            $page->save();

            $page->categories()->sync(collect($request->category_id ?? [])->pluck('id')->toArray());
            $page->load('template', 'categories');

            return response()->json($page, 200);
        } catch (Exception $e) {
            Log::error("Failed to edit page. " , [$e]);
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }

    /**
     * Delete page
     *
     * @return JsonResponse
     */
    public function delete(int $id): JsonResponse
    {
        try {
            $page = Page::find($id);
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
