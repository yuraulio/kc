<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\Abandoned;
use App\Model\Shoppingcart;
use App\Model\Event;
use App\Model\Ticket;

class AbandonedController extends Controller
{
    public function index(){
        $data = [];
        $list = Shoppingcart::with('user')->get();

        $tickets = [];
        $ticks = Ticket::where('status', 1)->get();
        $evids = [];

        $freeEvents = Event::where('view_tpl','elearning_free')->pluck('id');
        $freeEvents = $freeEvents->toArray();

        foreach ($list as $key => $item) {
            $user_id = $item->identifier;
            $cart = unserialize($item->content);
            //dd($cart);

            foreach ($cart as $cartItem) {
                if(!in_array($cartItem->options['event'],$freeEvents)){
                    //dd($cartItem);
                    $data['list'][$user_id] = $cartItem;
                    $evids[] = $cartItem->options['event'];
                }

            }


        }

        $events = Event::whereIn('id', $evids)->get()->getDictionary();
        $data['events'] = $events;
        $data['tickets'] = $ticks->getDictionary();
        $data['abcart'] = Shoppingcart::with('user')->get()->keyBy('identifier');
        //dd($data['abcart']);

        //dd($data);
        return view('admin.abandoned.index', $data);
    }

    public function remove($id){
        $item = Shoppingcart::find(intval($id));
        $item->delete();

        return redirect('admin/abandoned');
    }
}
