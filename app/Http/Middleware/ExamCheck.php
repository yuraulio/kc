<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Model\Exam;

class ExamCheck
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
        /*if(!isset($request->route()->parameters['ex_id'])){
            abort(404);
        }

        $exam = Exam::find($request->route()->parameters['ex_id']);
        dd($exam->event);*/

        return $next($request);
    }
}
