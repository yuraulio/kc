<?php

namespace App\Http\Middleware;

use Auth;
use Closure;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ClearOldSessionsMiddleware
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
        $user = Auth::user();

        $latestSessions = DB::table('sessions')
            ->where('user_id', $user->id)
            ->orderByDesc('last_activity')
            ->take(2)
            ->pluck('id')
            ->toArray();

        DB::table('sessions')
            ->where('user_id', $user->id)
            ->whereNotIn('id', $latestSessions)
            ->delete();

        // DB::table('sessions')
        // ->where('user_id', $user->id)
        // ->whereNotIn('id', function ($query) use ($user) {
        //     $query->select('id')
        //         ->from('sessions')
        //         ->where('user_id', $user->id)
        //         ->orderByDesc('created_at')
        //         ->limit(2);
        // })
        // ->delete();

        return $next($request);
    }
}
