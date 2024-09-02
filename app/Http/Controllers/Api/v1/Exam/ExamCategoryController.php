<?php

namespace App\Http\Controllers\Api\v1\Exam;

use App\Http\Controllers\Api\v1\ApiBaseController;
use App\Http\Resources\Api\v1\Exam\ExamCategory\ExamCategoryResource;
use App\Model\ExamCategory;
use App\Services\Exam\ExamCategoryService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ExamCategoryController extends ApiBaseController
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): JsonResponse
    {
        $query = $this->applyRequestParametersToQuery(ExamCategory::query(), $request);

        return new JsonResponse(
            $this->paginateByRequestParameters($query, $request)
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, ExamCategoryService $examCategoryService): ExamCategoryResource
    {
        return ExamCategoryResource::make(
            $examCategoryService->createOrUpdate($request),
        );
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, ExamCategory $examCategory): JsonResponse
    {
        return new JsonResponse(
            $this->applyRequestParametersToModel($examCategory, $request)
        );
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ExamCategory $examCategory, ExamCategoryService $examCategoryService): Response
    {
        $examCategoryService->delete($examCategory);

        return response()->noContent();
    }
}
