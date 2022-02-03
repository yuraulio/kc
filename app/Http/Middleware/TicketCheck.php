<?php

namespace App\Http\Middleware;

use Closure;
use Sentinel;
use App\Model\User as DPUser;
use App\Model\Event;
use \Cart as Cart;
use Auth;

class TicketCheck
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
               
                $event = Event::where('id',$user->cart->event)->with('ticket')->first();
                if($event->view_tpl == 'event_free_coupon'){
                    $stock = 1;
                }else{
                    $stock = $event->ticket->where('ticket_id',$user->cart->ticket_id)->first()->pivot->quantity;

                }

                if($stock <= 0){
                    $user->cart->delete();
                    Cart::instance('default')->destroy();
                    return redirect($event->slugable->slug);
                }
            }
        }else{

            $cart = Cart::content();
            $event_id = -1;
            $ticket_id = -1;
            
            foreach ($cart as $item){

                $event_id = $item->options->event;
                $ticket_id = $item->id;
                            
                break;
            }
            //dd($event_id);
            $event = Event::where('id',$event_id)->with('ticket')->first();

            if($event){
                if($event->view_tpl == 'event_free_coupon'){
                    $stock = 1;
                }else{
                    $stock = $event->ticket->where('ticket_id',$ticket_id)->first() ? $event->ticket->where('ticket_id',$ticket_id)->first()->pivot->quantity : 0;
    
                }
    
                if($stock <= 0){
                    Cart::instance('default')->destroy();
                    return redirect($event->slugable->slug);
                }
            }

            


        }
        
      //  dd('edw');
        return $next($request);
    }
}
