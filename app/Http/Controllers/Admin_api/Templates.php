<?php

namespace App\Http\Controllers\Admin_api;

use App\Http\Controllers\Controller;
use App\Model\Admin\Category;
use App\Model\Admin\Template;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class Templates extends Controller
{

    /**
     * Get templates
     *
     * @return JsonResponse
     */
    public function list(Request $request): JsonResponse
    {
        try {
            $templates = Template::lookForOriginal($request->filter)->get()->load("pages");
            return response()->json(['data' => $templates], 200);
        } catch (Exception $e) {
            Log::error("Failed to get templates. " . $e->getMessage());
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }

    /**
     * Add template
     *
     * @return JsonResponse
     */
    public function add(Request $request): JsonResponse
    {
        $request->validate([
            'title' => 'required|unique:cms_templates',
            'rows' => 'required',
        ]);

        try {
            $template = new Template();
            $template->title = $request->title;
            $template->description = $request->description;
            $template->rows = $request->rows;
            $template->save();

            return response()->json($template, 200);
        } catch (Exception $e) {
            Log::error("Failed to add new template. " . $e->getMessage());
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }

    /**
     * Get template
     *
     * @return JsonResponse
     */
    public function get(int $id): JsonResponse
    {
        try {
            $template = Template::find($id);
            return response()->json(['data' => $template], 200);
        } catch (Exception $e) {
            Log::error("Failed to get template. " . $e->getMessage());
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }

    /**
     * Edit template
     *
     * @return JsonResponse
     */
    public function edit(Request $request, int $id): JsonResponse
    {
        $request->validate([
            'title' => 'required|unique:cms_templates,title,'. $id,
            'rows' => 'required',
        ]);

        try {
            $template = Template::find($id);
            $template->title = $request->title;
            $template->description = $request->description;
            $template->rows = $request->rows;
            $template->save();

            return response()->json($template, 200);
        } catch (Exception $e) {
            Log::error("Failed to edit template. " . $e->getMessage());
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }

    /**
     * Delete template
     *
     * @return JsonResponse
     */
    public function delete(int $id): JsonResponse
    {
        try {
            $template = Template::find($id);
            $template->delete();

            return response()->json(['message' => 'success'], 200);
        } catch (Exception $e) {
            Log::error("Failed to delete template. " . $e->getMessage());
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }
}
