<?php

namespace App\Http\Controllers\Admin_api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateAdminTemplateRequest;
use App\Http\Requests\UpdateAdminTemplateRequest;
use App\Http\Resources\TemplateResource;
use App\Model\Admin\Template;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class TemplatesController extends Controller
{

    /**
     * Get templates
     *
     * @return AnonymousResourceCollection
     */
    public function index(Request $request, $paginate = 10)
    {
        $this->authorize('viewAny', Template::class, Auth::user());

        try {
            $templates = Template::lookForOriginal($request->filter)->with(["pages", "user"])->tableSort($request->sort)->paginate($paginate);
            return TemplateResource::collection($templates);
        } catch (Exception $e) {
            Log::error("Failed to get templates. " . $e->getMessage());
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }

    /**
     * Add template
     *
     * @return TemplateResource
     */
    public function store(CreateAdminTemplateRequest $request)
    {
        $this->authorize('create', Template::class, Auth::user());

        try {
            $template = new Template();
            $template->title = $request->title;
            $template->description = $request->description;
            $template->rows = $request->rows;
            $template->user_id = Auth::user()->id;
            $template->save();

            return new TemplateResource($template);
        } catch (Exception $e) {
            Log::error("Failed to add new template. " . $e->getMessage());
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }

    /**
     * Get template
     *
     * @return TemplateResource
     */
    public function show(int $id)
    {
        try {
            $template = Template::find($id);

            $this->authorize('view', $template, Auth::user());

            return new TemplateResource($template);
        } catch (Exception $e) {
            Log::error("Failed to get template. " . $e->getMessage());
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }

    /**
     * Edit template
     *
     * @return TemplateResource
     */
    public function update(UpdateAdminTemplateRequest $request, int $id)
    {
        try {
            $template = Template::find($id);

            $this->authorize('update', $template, Auth::user());

            $template->title = $request->title;
            $template->description = $request->description;
            $template->rows = $request->rows;
            $template->save();

            return new TemplateResource($template);
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
    public function destroy(int $id): JsonResponse
    {
        try {
            $template = Template::find($id);

            $this->authorize('delete', $template, Auth::user());

            $template->delete();

            return response()->json(['message' => 'success'], 200);
        } catch (Exception $e) {
            Log::error("Failed to delete template. " . $e->getMessage());
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }

    public function templatesAll(Request $request)
    {
        return $this->index($request, 99999);
    }
}
