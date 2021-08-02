<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Session;
use Illuminate\Support\Facades\Auth;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string
     */
    protected function redirectTo($request)
    {
        /*if (! $request->expectsJson()) {
            return route('login');
        }*/


        if (! $request->expectsJson()) {

            $url = explode('/',$request->getRequestUri());
            if(isset($url[1]) && $url[1] == 'myaccount'){
                return route('homepage');
            }

        }



    }


    protected function authenticate($request, array $guards)
    {
        //dd($this->auth->user());
        if (empty($guards)) {
            $guards = [null];
        }

        foreach ($guards as $guard) {
            if ($this->auth->guard($guard)->check()) {
               $this->auth->shouldUse($guard);

                if(!$this->auth->user()->statusAccount->completed){
                    $this->auth->user()->AauthAcessToken()->delete();
                    Session::invalidate();
                    Session::regenerateToken();

                    return redirect()->back()->with('message',
                        'Account is not activated!'
                    );
                }
                return $this->auth->shouldUse($guard);
            }
        }

        $this->unauthenticated($request, $guards);
    }

}
