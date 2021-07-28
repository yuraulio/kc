<?php

namespace PostRider\Http\Middleware;

use Closure;
use Session;
use Auth;

class CheckUserLogeinForElearning
{

     /**
     * The Sentinel instance.
     *
     * @var \Cartalyst\Sentinel\Sentinel
     */
    protected $auth;

    /**
     * Create a new filter instance.
     *
     * @param  \Cartalyst\Sentinel\Sentinel  $auth
     * @return void
     */
  

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {

        if (!Auth::check()) {

            return response()->json([
                'loged_in' => false,
                'message' => 'it seems you try connect from a new device, all other devices will be disconnected.',
                'redirect' => '/'
            ]);
            
        }

       /* return response()->json([
            'loged_in' => true,
        ]);*/

        return $next($request);
    }
}
