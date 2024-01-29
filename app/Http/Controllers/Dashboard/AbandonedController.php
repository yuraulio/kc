<?php

namespace App\Http\Controllers\Dashboard;

use App\Exports\AbandonedExport;
use App\Http\Controllers\Controller;
use App\Model\Abandoned;
use App\Model\Event;
use App\Model\ShoppingCart;
use App\Model\Ticket;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Maatwebsite\Excel\Facades\Excel;

class AbandonedController extends Controller
{
    public function index()
    {
        // Before showing the abandoned cart, we need to check if the items inside the cart was a free event, or if it was finally booked by the user.
        // We need to check since the last time we entered in this page. So, we can save this timestamp in the cache.
        $timestamp = Cache::get('timestamp-last-time-check-abandoned-cart', Carbon::now()->subYears(10)->format('Y-m-d H:i:s'));
        $list = ShoppingCart::with('user')->where('created_at', '>', $timestamp)->get();
        $evids = [];
        $data = [];
        $tickets = [];
        $ticks = Ticket::where('status', 1)->get();
        if (count($list) > 0) {
            $lastShoppingCartChecked = $timestamp;

            $freeEvents = Event::where('view_tpl', 'elearning_free')->pluck('id');
            $freeEvents = $freeEvents->toArray();

            foreach ($list as $key => $item) {
                if ($lastShoppingCartChecked < $item->created_at) {
                    $lastShoppingCartChecked = $item->created_at;
                }
                $user_id = $item->identifier;
                $cart = unserialize($item->content);

                $userEvents = isset($item->user[0]) ? $item->user[0]->events()->pluck('event_id')->toArray() : [];

                foreach ($cart as $cartItem) {
                    if (in_array($cartItem->options['event'], $userEvents)) {
                        $item->delete();
                        continue;
                    }

                    if (!in_array($cartItem->options['event'], $freeEvents)) {
                        $data['list'][$user_id] = $cartItem;
                        $evids[] = $cartItem->options['event'];
                    }
                }
            }
            Cache::set('timestamp-last-time-check-abandoned-cart', $lastShoppingCartChecked);
        }
        $events = Event::whereIn('id', $evids)->get()->getDictionary();
        $data['events'] = $events;
        $data['tickets'] = $ticks->getDictionary();
        // $data['abcart'] = ShoppingCart::with('user')->get()->keyBy('identifier');

        unset($data['list']); // We don't want to show the previous table
        $data['list_by_shopping_cart'] = ShoppingCart::with('user')->get();

        return view('admin.abandoned.index', $data);
    }

    public function remove($id)
    {
        $item = ShoppingCart::find(intval($id));
        $item->delete();

        return redirect('admin/abandoned');
    }

    public function exportCsv(Request $request)
    {
        Excel::store(new AbandonedExport($request->events, $request->fromDate, $request->toDate), 'AbandonedCart.xlsx', 'export');

        return Excel::download(new AbandonedExport($request->events, $request->fromDate, $request->toDate), 'AbandonedCart.xlsx');

        return redirect()->route('abandoned.index');
    }
}
