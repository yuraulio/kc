<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Auth;
use Session;

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
        
        if(!$this->auth->statusAccount->completed){
            Auth::logout();
            Session::invalidate();
            Session::regenerateToken();

            return redirect('/');
        }

        $roles = $this->auth->role->pluck('name')->toArray();

        if (in_array('Super Administrator',$roles) || in_array('Administrator',$roles) || in_array('Manager',$roles) || in_array('Author',$roles)) {
            return $next($request);
        }
        elseif(in_array('Knowcrunch Partner',$roles)) {

            if($request->route()->uri != 'admin/transaction/export-excel'){
                $request->route()->action['uses'] = 'App\Http\Controllers\TransactionController@participants_inside_revenue';
                $request->route()->action['controller'] = 'App\Http\Controllers\TransactionController@participants_inside_revenue';
                $request->route()->action['as'] = 'transaction.participants';
                $request->route()->action['prefix'] = 'transaction.participants';
                $request->route()->controller = (new \App\Http\Controllers\TransactionController);
                $request->route()->controller->uri = 'admin/transaction/participants';
            }
           
            return $next($request);
           
        }

        abort(404);
    }
}
