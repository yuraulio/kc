<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Auth;

class AuthAuthorsAndAbove
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */

    public function __construct()
    {
        $this->auth = Auth::user();
    }

    public function handle(Request $request, Closure $next)
    {
        if(!$this->auth){
            return redirect()->to('admin')->withErrors(['You must login first.']);
        }
        $roles = $this->auth->role->pluck('name')->toArray();

        if (in_array('Super Administrator',$roles) || in_array('Administrator',$roles) || in_array('Manager',$roles) || in_array('Author',$roles)) {
            return $next($request);
        }
        elseif(in_array('KnowCrunch Partner',$roles)) {
            return $next($request);
            //return redirect()->to('admin/transaction');
        }

        abort(404);
    }
}
