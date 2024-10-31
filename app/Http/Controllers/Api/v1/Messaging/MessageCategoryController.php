<?php

namespace App\Http\Controllers\Api\v1\Messaging;

use App\Http\Controllers\Api\v1\ApiBaseController;
use App\Http\Resources\Api\v1\Messaging\MessageCategoryResource;
use App\Model\MessageCategory;
use App\Services\Messaging\MessageCategoryService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class MessageCategoryController extends ApiBaseController
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): JsonResponse
    {
        $query = $this->applyRequestParametersToQuery(MessageCategory::query(), $request);

        return new JsonResponse(
            $this->paginateByRequestParameters($query, $request)
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, MessageCategoryService $messageCategoryService): MessageCategoryResource
    {
        return MessageCategoryResource::make(
            $messageCategoryService->createOrUpdate($request),
        );
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, MessageCategory $category): JsonResponse
    {
        return new JsonResponse(
            $this->applyRequestParametersToModel($category, $request)
        );
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(MessageCategory $category, MessageCategoryService $messageCategoryService)
    {
        $messageCategoryService->delete($category);

        return response()->noContent();
    }
}
