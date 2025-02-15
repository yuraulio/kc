<?php

namespace App\Http\Middleware;

use App\Model\Certificate;
use Auth;
use Closure;
use Illuminate\Http\Request;

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
        if (!isset($request->route()->parameters['certificate'])) {
            abort(404);
        }

        /*$certificate = $request->route()->parameters['certificate']->user()->pluck('id')->toArray();

        if(!in_array(Auth::user()->id,$certificate)){
            abort(404);
        }*/

        //$certificate = decrypt($request->route()->parameters['certificate']);
        //dd($request->route()->parameters['certificate']);
        $certificate = base64_decode($request->route()->parameters['certificate']);

        $certificate = explode('--', $certificate);
        //dd($certificate);
        if (!isset($certificate[1])) {
            abort(404);
        }

        if (!Certificate::find($certificate[1])) {
            abort(404);
        }

        if (!Certificate::find($certificate[1])->show_certificate) {
            abort(404);
        }

        return $next($request);
    }
}
