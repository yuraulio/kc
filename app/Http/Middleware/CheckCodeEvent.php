<?php

namespace PostRider\Http\Middleware;

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
            dd('fdsa');
            if($user->cart){
                
                $event = Content::where('id',$user->cart->event)->with('contentLinksTicket')->first();
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
