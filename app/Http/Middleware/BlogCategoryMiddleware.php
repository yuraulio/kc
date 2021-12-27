<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class BlogCategoryMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $input = $request->all();

        if (isset($input['parent_id']) and $input['parent_id'] == 0) {
            $input['parent_id'] = null;
            $request->replace($input);
        }

        return $next($request);
    }
}
