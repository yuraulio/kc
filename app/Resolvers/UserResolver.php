<?php

namespace App\Resolvers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;

class UserResolver implements \OwenIt\Auditing\Contracts\UserResolver
{
    /**
     * @return \Illuminate\Contracts\Auth\Authenticatable|null
     */
    public static function resolve()
    {
        $guards = Config::get('audit.user.guards');

        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                return Auth::guard($guard)->user();
            }
        }

    }
}
