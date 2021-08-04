<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Auth;

class CertificateOwner
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
        if(!isset($request->route()->parameters['certificate'])){
            abort(404);
        }

        $certificate = $request->route()->parameters['certificate']->user()->pluck('id')->toArray();
        
        if(!in_array(Auth::user()->id,$certificate)){
            abort(404);
        }

        return $next($request);
    }
}
