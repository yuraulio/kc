<?php

namespace App\Http\Controllers\Api\v1\Exam;

use App\Http\Controllers\Api\v1\ApiBaseController;
use App\Model\Exam;
use App\Model\ExamSyncData;
use App\Services\Exam\ExamResultService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ExamResultController extends ApiBaseController
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, Exam $exam): JsonResponse
    {
        $query = $this->applyRequestParametersToQuery($exam->results()->with('relatedResults', function ($q) use ($exam) {
            $q->where('exam_id', $exam->id);
        })->groupBy('user_id'), $request);

        return new JsonResponse(
            $this->paginateByRequestParameters($query, $request)
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
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
    public function destroy(string $id)
    {
        //
    }

    public function getLiveResults(Exam $exam, ExamResultService $resultService)
    {
        return response()->json([
            'data' => $resultService->fetchLiveResults($exam),
        ]);
    }
}
