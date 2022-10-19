<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Auth;
class Preview
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
    
        if(isset($_GET['preview']) && $_GET['preview'] == 'true'){
            //dd(get_status_by_slug($request->path()));
            //dd(Auth::user());   

            if(!Auth::check()){
                abort(404);
            }

            $roles = Auth::user()->role->pluck('name')->toArray();

            if (!(in_array('Super Administrator',$roles) || in_array('Administrator',$roles) || in_array('Manager',$roles) || in_array('Author',$roles))) {
                abort(404);
            
            }


        }else if( !get_status_by_slug($request->path())){
            abort(404);
        }
    
        return $next($request);
    }
}
