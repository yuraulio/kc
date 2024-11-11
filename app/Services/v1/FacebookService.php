<?php

declare(strict_types=1);

namespace App\Services\v1;

use App\Model\Review;
use Exception;
use GuzzleHttp\Promise\PromiseInterface;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Laravel\Socialite\Facades\Socialite;

class FacebookService
{
    public function callback(): bool
    {
        try {
            $facebookUser = Socialite::driver('facebook')->stateless()->user();

            $response = $this->getAccessToken($facebookUser->token);

            if ($response->successful()) {
                $longTermToken = $response->json()['access_token'];
            } else {
                return false;
            }

            $user = Auth::user();

            $response = $this->getPageAccessToken($longTermToken);

            if ($response->successful()) {
                $pages = $response->json()['data'];
                $pageAccessToken = $pages[0]['access_token'] ?? null;
            } else {
                return false;
            }

            $user->update(
                [
                    'facebook_page_token'   => $pageAccessToken,
                    'facebook_access_token' => $longTermToken,
                    'facebook_id'           => $facebookUser->id,
                ]
            );

            return true;
        } catch (Exception $e) {
            Log::error($e->getMessage(), $e->getCode());

            return false;
        }
    }

    public function getPageAccessToken($longTermToken): PromiseInterface|Response
    {
        return Http::withToken($longTermToken)->get('https://graph.facebook.com/v12.0/me/accounts');
    }

    public function getAccessToken($token): Response
    {
        return Http::get('https://graph.facebook.com/oauth/access_token', [
            'grant_type'        => 'fb_exchange_token',
            'client_id'         => config('services.facebook.client_id'),
            'client_secret'     => config('services.facebook.client_secret'),
            'fb_exchange_token' => $token,
        ]);
    }

    public function postReview(Review $review)
    {
    }
}
