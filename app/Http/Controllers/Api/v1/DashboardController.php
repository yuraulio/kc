<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Services\DashboardService;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class DashboardController extends Controller
{
    public function __construct(private DashboardService $service)
    {
    }

    public function statistic(): JsonResponse
    {
        return response()->json($this->service->getStatistic(), Response::HTTP_OK);
    }

    public function sales(): JsonResponse
    {
        return response()->json($this->service->getSales(), Response::HTTP_OK);
    }
}
