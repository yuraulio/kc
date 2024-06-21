<?php

namespace App\Http\Controllers\Api\v1\Exam;

use App\Http\Controllers\Api\v1\ApiBaseController;
use App\Model\Exam;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ExamResultController extends ApiBaseController
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, Exam $exam): JsonResponse
    {
        $query = $this->applyRequestParametersToQuery($exam->results(), $request);

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
}
