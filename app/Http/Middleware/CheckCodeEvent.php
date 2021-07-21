<?php

namespace PostRider\Http\Middleware;

use Closure;
use Sentinel;
use PostRider\User as DPUser;
use PostRider\Content;
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
        $user = Sentinel::check();
        if($user){
            $user = DPUser::find($user->id);
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
