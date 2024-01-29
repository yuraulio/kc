<?php

namespace App\Http\Middleware;

use App\Model\Event;
use App\Model\User as DPUser;
use Auth;
use Cart as Cart;
use Closure;
use Sentinel;

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
        if ($user) {
            if ($user->cart) {
                $event = Event::where('id', $user->cart->event)->with('ticket')->first();
                if ($event->view_tpl == 'event_free_coupon') {
                    $stock = 1;
                } else {
                    $stock = $event->ticket->where('ticket_id', $user->cart->ticket_id)->first()->pivot->quantity ?? 0;
                }

                if ($stock <= 0) {
                    $user->cart->delete();
                    Cart::instance('default')->destroy();

                    return redirect($event->slugable->slug);
                }
            }
        } else {
            $cart = Cart::content();
            $event_id = -1;
            $ticket_id = -1;
            $type = false;
            $requestQty = 1;
            foreach ($cart as $item) {
                $event_id = $item->options->event;
                $ticket_id = $item->id;
                $type = $item->options->type;
                $requestQty = $item->qty;

                break;
            }
            $event = Event::where('id', $event_id)->with('ticket')->first();
            if ($event) {
                if ($event->view_tpl == 'event_free_coupon' || $event->view_tpl == 'elearning_free' || $ticket_id == 'waiting') {
                    $stock = 1;
                } elseif ($type == 5) {
                    $stock = $event->ticket->where('ticket_id', $ticket_id)->first() && $event->ticket->where('ticket_id', $ticket_id)->first()->pivot->quantity >= $requestQty
                                ? $event->ticket->where('ticket_id', $ticket_id)->first()->pivot->quantity : 0;
                } else {
                    $stock = $event->ticket->where('ticket_id', $ticket_id)->first() ? $event->ticket->where('ticket_id', $ticket_id)->first()->pivot->quantity : 0;
                }

                if ($stock <= 0) {
                    Cart::instance('default')->destroy();

                    return redirect($event->slugable->slug);
                }
            }
        }

        return $next($request);
    }
}
