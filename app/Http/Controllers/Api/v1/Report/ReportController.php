<?php

namespace App\Http\Controllers\Api\v1\Report;

use App\Http\Controllers\Api\v1\ApiBaseController;
use App\Http\Resources\Api\v1\Report\ReportResource;
use App\Model\Report;
use App\Services\Report\ReportService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ReportController extends ApiBaseController
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): JsonResponse
    {
        $query = $this->applyRequestParametersToQuery(Report::query(), $request);

        return new JsonResponse(
            $this->paginateByRequestParameters($query, $request)
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, ReportService $reportService): ReportResource
    {
        return ReportResource::make(
            $reportService->createOrUpdate($request),
        );
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, Report $report): JsonResponse
    {
        return new JsonResponse(
            $this->applyRequestParametersToModel($report, $request)
        );
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Report $report, ReportService $reportService): Response
    {
        $reportService->delete($report);

        return response()->noContent();
    }

    public function exportReportResults(Request $request, Report $report, ReportService $reportService)
    {
        return $reportService->exportReportResults($request, $report);
    }

    public function getLiveCount(Request $request, ReportService $reportService)
    {
        return $reportService->getLiveCount($request);
    }
}
