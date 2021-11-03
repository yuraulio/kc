<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Auth;
use App\Model\Pages;

class CheckInstructorTermsPage
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
        $page = Pages::find(4753);
        $pageSlug = $page->slugable->slug;
        
        if(Auth::user() && $request->is($pageSlug) && !Auth::user()->instructor->first()) {
            abort(404);
        }

        return $next($request);
    }
}
