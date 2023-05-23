<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use \Cart as Cart;
use Session;

class Billing
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
        
        if(!Session::get('pay_seats_data')){
            return redirect('/registration');
        }

        return $next($request);
    }
}
