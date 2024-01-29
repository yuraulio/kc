<?php

namespace App\Http\Middleware;

use App\Model\Event;
use Auth;
use Cart as Cart;
use Closure;

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

        $user = Auth::user();
        $carts = Cart::content();
        $event_type = '-1';

        foreach ($carts as $cart) {
            $event_type = $cart->id;
            break;
        }

        if ($event_type == 'free_code') {
            return $next($request);
        } else {
            return redirect('registration');
        }

        abort(404);
    }
}
