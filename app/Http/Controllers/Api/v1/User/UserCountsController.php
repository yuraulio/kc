<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\v1\User;

use App\Http\Controllers\Controller;
use App\Services\v1\UserService;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class UserCountsController extends Controller
{
    public function __construct(private UserService $service)
    {
    }

    public function adminsCounts(): JsonResponse
    {
        return \response()->json($this->service->adminsCounts(), Response::HTTP_OK);
    }

    public function studentsCounts(): JsonResponse
    {
        return \response()->json(['data' => $this->service->studentsCounts()], Response::HTTP_OK);
    }

    public function instructorsCounts(): JsonResponse
    {
        return \response()->json(['data' => $this->service->getInstructorsByCourse()], Response::HTTP_OK);
    }
}
