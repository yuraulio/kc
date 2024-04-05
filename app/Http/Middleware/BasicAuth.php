<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

/**
 * TODO: Important - this middleware is needed for dev environments delete it before deploying to the production.
 */
class BasicAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Allow making the requests to the API.
        if ($request->wantsJson()) {
            return $next($request);
        }

        // Allow see the images by the direct url.
        if (Str::startsWith($request->path(), 'uploads/')) {
            return $next($request);
        }

        // Check if BasicAuth is enabled.
        if (!env('BASIC_AUTH', false)) {
            return $next($request);
        }

        header('Cache-Control: no-cache, must-revalidate, max-age=0');
        $has_supplied_credentials = !(empty($_SERVER['PHP_AUTH_USER']) && empty($_SERVER['PHP_AUTH_PW']));
        $is_not_authenticated = (
            !$has_supplied_credentials ||
            $_SERVER['PHP_AUTH_USER'] != env('BASIC_AUTH_USER') ||
            $_SERVER['PHP_AUTH_PW'] != env('BASIC_AUTH_PASSWORD')
        );

        if ($is_not_authenticated) {
            header('HTTP/1.1 401 Authorization Required');
            header('WWW-Authenticate: Basic realm="Access denied"');
            exit;
        }

        return $next($request);
    }
}
