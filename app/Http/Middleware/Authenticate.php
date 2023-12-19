<?php

namespace App\Http\Middleware;

use Illuminate\Auth\AuthenticationException;
use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Session;

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
            $url = isset($url[1]) ? explode('?',$url[1]) : $url;

            if(isset($url[0]) && $url[0] == 'myaccount'){

                if($request->login){
                    return route('homepage',['login'=>true]);

                }else{
                    //dd('gsdf');
                    return route('homepage', ['login'=>true]);

                }
            }

        }



    }

    protected function authenticate($request, array $guards)
    {
        if (empty($guards)) {
            $guards = [null];
        }

        foreach ($guards as $guard) {
            if ($this->auth->guard($guard)->check()) {
                $this->auth->shouldUse($guard);

                if(!$this->auth->user()->statusAccount || !$this->auth->user()->statusAccount->completed){
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

    protected function unauthenticated($request, $guards): JsonResponse
    {
        if ($request->expectsJson() || $request->acceptsJson()) {
            abort(new JsonResponse([
                'message' => 'Unauthenticated.'
            ], 401));
        }

        throw new AuthenticationException(
            'Unauthenticated.', $guards, $this->redirectTo($request)
        );
    }

}
