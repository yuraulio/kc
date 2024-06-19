<?php

namespace App\Exports;

use App\Model\Abandoned;
use App\Model\Event;
use App\Model\ShoppingCart;
use App\Model\Ticket;
use Maatwebsite\Excel\Concerns\FromArray;

class AbandonedExport implements FromArray
{
    protected $events = [];
    protected $fromDate = [];
    protected $toDate = [];

    public function __construct($events, $fromDate, $toDate)
    {
        $this->events = $events;
        $this->fromDate = date('Y-m-d', strtotime($fromDate));
        $this->toDate = $toDate ? date('Y-m-d', strtotime($toDate)) : date('Y-m-d');
        $this->toDate = date('Y-m-d', strtotime($this->toDate . ' +1 day'));
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function array(): array
    {
        $oldTickets[4756] = 1201;
        $oldTickets[2249] = 1201;
        $oldTickets[1921] = 1201;

        $oldTickets[1150] = 19;
        $oldTickets[1230] = 19;
        $oldTickets[983] = 19;

        $oldTickets[4755] = 20;
        $oldTickets[2248] = 20;
        $oldTickets[1514] = 20;
        $oldTickets[1151] = 20;
        $oldTickets[1232] = 20;
        $oldTickets[984] = 20;

        $oldTickets[4757] = 21;
        $oldTickets[2250] = 21;
        $oldTickets[1513] = 21;
        $oldTickets[1988] = 21;
        $oldTickets[1152] = 21;
        $oldTickets[1231] = 21;
        $oldTickets[985] = 21;

        $this->createDir(base_path('public/uploads/tmp/exports/'));
        $subs = [];

        $data = [];
        $list = [];
        $alist = ShoppingCart::whereBetween('created_at', [$this->fromDate, $this->toDate])->with('user')->get();
        $tickets = [];
        $ticks = Ticket::where('status', 1)->get();
        $evids = [];

        foreach ($alist as $key => $item) {
            $user_id = $item->identifier;
            $cart = unserialize($item->content);

            foreach ($cart as $cartItem) {
                if(!in_array($cartItem->options['event'], $this->events)) {
                    continue;
                }

                $list[$user_id] = $cartItem;
                $evids[] = $cartItem->options['event'];
            }
        }

        $events = Event::whereIn('id', $this->events)->get()->getDictionary();
        //$data['events'] = $events;
        //dd($events);
        $tickets = $ticks->getDictionary();
        $abcart = ShoppingCart::whereBetween('created_at', [$this->fromDate, $this->toDate])->with('user')->get()->keyBy('identifier');

        foreach($list as $user_id => $ucart) :

            if($abcart[$user_id]->user->first() != null) {
                $fn1 = $abcart[$user_id]->user->first()['firstname'];
                $fn2 = $abcart[$user_id]->user->first()['lastname'];

                $evdate = 'No Date';
                if(isset($events[$ucart->options['event']]['customFields'])) {
                    foreach ($events[$ucart->options['event']]['customFields'] as $ckey => $cvalue) {
                        if ($cvalue->name == 'simple_text' && $cvalue->priority == 0) {
                            $evdate = $cvalue->value;
                            break;
                        }
                    }
                }

                if($ucart->id == 'free') {
                    $ticket_title = 'Free';
                } else {
                    $ticket_title = isset($oldTickets[$ucart->id]) ? $oldTickets[$ucart->id] : $tickets[$ucart->id]->title;
                }
                $subs[] = [
                    $abcart[$user_id]->user->first()['email'],
                    $fn1,
                    $fn2,
                    $abcart[$user_id]->user->first()['mobile'],
                    $events[$ucart->options['event']]->title . ' - ' . $evdate,
                    $ticket_title,
                    $ucart->qty,
                    $ucart->qty * $ucart->price,
                    'C:' . $abcart[$user_id]->created_at->format('d/m/Y H:i') . ' | U:' . $abcart[$user_id]->updated_at->format('d/m/Y H:i'),

                ];
            }

        endforeach;

        if(count($subs) > 0) {
            return $subs;
        } else {
            return [];
        }
    }

    public function createDir($dir, $permision = 0755, $recursive = true)
    {
        if (!is_dir($dir)) {
            return mkdir($dir, $permision, $recursive);
        } else {
            return true;
        }
    }
}
