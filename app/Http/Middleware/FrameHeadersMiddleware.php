<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class FrameHeadersMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        $frontendDomain = config('app.frontend_url', 'https://admin-rose-eta.vercel.app');

        $response->headers->set('X-Frame-Options', "ALLOW-FROM $frontendDomain");
        $response->headers->set('Content-Security-Policy', "frame-ancestors 'self' $frontendDomain");

        return $response;
    }
}
