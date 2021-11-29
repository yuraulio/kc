<?php

namespace App\Exports;

use App\Model\Abandoned;
use Maatwebsite\Excel\Concerns\FromArray;
use App\Model\ShoppingCart;
use App\Model\Event;
use App\Model\Ticket;

class AbandonedExport implements FromArray
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function array(): array
    {
        $this->createDir(base_path('public/uploads/tmp/exports/'));
        $subs = [];


        $data = [];
        $alist = ShoppingCart::with('user')->get();

        $tickets = [];
        $ticks = Ticket::where('status', 1)->get();
        $evids = [];

        foreach ($alist as $key => $item) {
            $user_id = $item->identifier;
            $cart = unserialize($item->content);

            foreach ($cart as $cartItem) {
                $list[$user_id] = $cartItem;
                $evids[] = $cartItem->options['event'];
            }


        }

        $events = Event::whereIn('id', $evids)->get()->getDictionary();
        //$data['events'] = $events;
        //dd($events);
        $tickets = $ticks->getDictionary();
        $abcart = ShoppingCart::with('user')->get()->keyBy('identifier');

        foreach($list as $user_id => $ucart) :
            if($abcart[$user_id]->user->first() != null){
            $fn = $abcart[$user_id]->user->first()['firstname'] . ' ' .$abcart[$user_id]->user->first()['lastname'];

            $evdate = 'No Date';
            if(isset($events[$ucart->options['event']]['customFields'])) {
                foreach ($events[$ucart->options['event']]['customFields'] as $ckey => $cvalue) {
                    if ($cvalue->name == 'simple_text' && $cvalue->priority == 0) {
                        $evdate = $cvalue->value;
                        break;
                    }
                }
            }

            if($ucart->id == 'free'){
                $ticket_title = 'Free';
            }else{
                $ticket_title = $tickets[$ucart->id]->title;
            }

            $subs[] = [
                $abcart[$user_id]->user->first()['email'],
                $fn,
                $events[$ucart->options['event']]->title . ' - ' . $evdate,
                $ticket_title,
                $ucart->qty,
                $ucart->qty*$ucart->price,
                'C:'.$abcart[$user_id]->created_at->format('d/m/Y H:i').' | U:'.$abcart[$user_id]->updated_at->format('d/m/Y H:i')

            ];

        }

        endforeach;

        if(count($subs) > 0){
            return $subs;
        }else{
            return redirect()->route('abandoned.index');
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




