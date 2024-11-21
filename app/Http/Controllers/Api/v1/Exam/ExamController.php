<?php

namespace App\Http\Controllers\Api\v1\Exam;

use App\Http\Controllers\Api\v1\ApiBaseController;
use App\Model\Exam;
use App\Services\Exam\ExamService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ExamController extends ApiBaseController
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): JsonResponse
    {
        $query = $this->applyRequestParametersToQuery(Exam::with('event'), $request);

        return new JsonResponse(
            $this->paginateByRequestParameters($query, $request)
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, ExamService $examService): JsonResponse
    {
        return new JsonResponse(
            $examService->createOrUpdate($request)
        );
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, Exam $exam, ExamService $examService): JsonResponse
    {
        return new JsonResponse(
            $this->applyRequestParametersToModel($examService->getExamWithRelations($exam), $request)
        );
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ExamRequest $request, Exam $exam)
    {
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Exam $exam, ExamService $examService): Response
    {
        $examService->delete($exam);

        return response()->noContent();
    }

    public function duplicateExam(Exam $exam, ExamService $examService): Response
    {
        $examService->duplicate($exam);

        return response()->noContent();
    }

    public function updateQuestions(Request $request, Exam $exam, ExamService $examService)
    {
        return $examService->updateQuestions($request, $exam);
    }
}
