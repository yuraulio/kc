<?php

namespace App\Http\Middleware;

use Closure;
use Auth;
use App\Model\Event;
use \Cart as Cart;

class CheckForFreeEvent
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
                //$stock = $event->contentLinksTicket->where('ticket_id',$user->cart->ticket_id)->first()->stock;
                if($event->view_tpl == 'elearning_free'|| $event->view_tpl == 'event_free'){
                    $user->cart->delete();
                    Cart::instance('default')->destroy();
                    return redirect($event->slugable->slug);
                    
                }
            }
        }
       
        return $next($request);
    }
}
