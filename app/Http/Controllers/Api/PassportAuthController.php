<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Model\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class PassportAuthController extends Controller
{
    /**
     * Login.
     */
    public function login(Request $request): JsonResponse
    {
        if (Auth::attempt($request->only('email', 'password'))) {
            $user = Auth::user()->load('statusAccount');

            if (!$user->statusAccount?->completed) {
                return new JsonResponse(['error' => 'Account is not activated.'], Response::HTTP_FORBIDDEN);
            }

            $token = $user->createToken('LaravelAuthApp');

            return new JsonResponse([
                'token' => $token->accessToken,
                'expire' => $token->token->expires_at->diffForHumans(),
                'sms' => encrypt($user->id . '-' . date('H:i:s')),
            ]);
        }

        if (User::firstWhere('email', $request->get('email'))) {
            return new JsonResponse(['error' => 'Incorrect password, please try again.'], Response::HTTP_FORBIDDEN);
        }

        return new JsonResponse(['error' => 'Incorrect email, please try again.'], Response::HTTP_FORBIDDEN);
    }

    /**
     * Logout.
     */
    public function logout(Request $request): JsonResponse
    {
        Auth::user()->token()->delete();

        return response()->json(['message' => 'Logged out successfully.']);
    }
}
