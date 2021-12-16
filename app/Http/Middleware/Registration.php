<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use \Cart as Cart; 
use App\Model\CartCache;
use App\Model\Ticket;

class Registration
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
    
        if(request()->has('cart')){
            Cart::instance('default')->destroy();
            $cartCache = CartCache::where('slug', request('cart'))->first();
            
            if($cartCache){
               Cart::add($cartCache->ticket_id, $cartCache->product_title, $cartCache->quantity, $cartCache->price, 
                ['type' => $cartCache->type, 'event' => $cartCache->event])->associate(Ticket::class);
            }

          

            return redirect('/cart');
        }

        if(Cart::content()->count() <= 0){
            return redirect('/');
        }

        return $next($request);
    }
}
