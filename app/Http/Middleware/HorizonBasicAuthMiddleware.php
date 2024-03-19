<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

class HorizonBasicAuthMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (!$this->hasValidCredentials($request->getUser(), $request->getPassword())) {
            throw new UnauthorizedHttpException('Basic');
        }

        return $next($request);
    }

    /**
     * Verify entered credentials.
     *
     * @param string|null $user
     * @param string|null $password
     * @return bool
     */
    private function hasValidCredentials(?string $user, ?string $password): bool
    {
        if (app()->environment(['local', 'development'])) {
            return true;
        }

        return $user === config('horizon.basic_auth.username') && config('horizon.basic_auth.password') && Hash::check($password, config('horizon.basic_auth.password'));
    }
}
