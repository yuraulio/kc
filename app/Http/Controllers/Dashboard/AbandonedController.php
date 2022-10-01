<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\Abandoned;
use App\Model\ShoppingCart;
use App\Model\Event;
use App\Model\Ticket;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\AbandonedExport;



class AbandonedController extends Controller
{
    public function index(){
        $data = [];
        $list = ShoppingCart::with('user')->get();

        $tickets = [];
        $ticks = Ticket::where('status', 1)->get();
        $evids = [];

        $freeEvents = Event::where('view_tpl','elearning_free')->pluck('id');
        $freeEvents = $freeEvents->toArray();


        //dd($freeEvents);
        //dd($list);
        foreach ($list as $key => $item) {
            $user_id = $item->identifier;
            $cart = unserialize($item->content);

            $userEvents = isset($item->user[0]) ? $item->user[0]->events()->pluck('event_id')->toArray() : [];


            foreach ($cart as $cartItem) {

                if(in_array($cartItem->options['event'],$userEvents)){
                    $item->delete();
                    continue;
                }

                //dd(in_array($cartItem->options['event'],$freeEvents));
                if(!in_array($cartItem->options['event'],$freeEvents)){
                    //dd($cartItem->options['event']);
                    $data['list'][$user_id] = $cartItem;
                    //dd($cartItem->options['event']);
                    $evids[] = $cartItem->options['event'];
                }

            }


        }
        //dd($evids);
        $events = Event::whereIn('id', $evids)->get()->getDictionary();
        $data['events'] = $events;
        $data['tickets'] = $ticks->getDictionary();
        $data['abcart'] = ShoppingCart::with('user')->get()->keyBy('identifier');
        //dd($data['tickets']);

        //dd($data);
        return view('admin.abandoned.index', $data);
    }

    public function remove($id){
        $item = ShoppingCart::find(intval($id));
        $item->delete();

        return redirect('admin/abandoned');
    }

    public function exportCsv(Request $request)
    {   
        
        Excel::store(new AbandonedExport($request->events,$request->fromDate,$request->toDate), 'AbandonedCart.xlsx', 'export');
        return Excel::download(new AbandonedExport($request->events,$request->fromDate,$request->toDate), 'AbandonedCart.xlsx');

        return redirect()->route('abandoned.index');

    }

}
