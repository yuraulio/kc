<?php

namespace App\Http\Middleware;

use Closure;
use Auth;
use App\Model\Event;
use \Cart as Cart;

class CheckCodeEvent
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {

        $user = Auth::user();
        if($user){
            
            if($user->cart){
                
                $event = Event::where('id',$user->cart->event)->first();
                if( $event->view_tpl === 'event_free_coupon' ){
                    return $next($request);
                }
            }
        }else{
            return redirect('/cart');
        }
        
        abort(404);
    }
}
