<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use \Cart as Cart; 

class Registration
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
    
        if(Cart::content()->count() <= 0){
            return redirect('/');
        }

        return $next($request);
    }
}
