<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Services\v1\FacebookService;
use Illuminate\Http\JsonResponse;
use Laravel\Socialite\Facades\Socialite;

class FacebookAuthController extends Controller
{
    public function __construct(private FacebookService $service)
    {
    }

    public function redirect()
    {
        return Socialite::driver('facebook')->stateless()->redirect();
    }

    public function callback(): JsonResponse
    {
        if ($this->service->callback()) {
            return response()->json(['token' => 'success'], 200);
        } else {
            return response()->json(['error' => 'Authentication Failed'], 401);
        }
    }
}
