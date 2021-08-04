<?php

namespace App\Exports;

use App\Model\Abandoned;
use Maatwebsite\Excel\Concerns\FromArray;
use App\Model\Shoppingcart;
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
        $alist = Shoppingcart::with('user')->get();

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
        $abcart = Shoppingcart::with('user')->get()->keyBy('identifier');

        foreach($list as $user_id => $ucart) :
            if($abcart[$user_id]->user != null){
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
//dd($ucart->options['event']);
            $subs[] = [
                $abcart[$user_id]->user->first()['email'],
                $fn,
                $events[$ucart->options['event']]->title . ' - ' . $evdate,
                //$tickets[$ucart->id]->title,
                $ucart->qty,
                $ucart->qty*$ucart->price,
                'C:'.$abcart[$user_id]->created_at->format('d/m/Y H:i').' | U:'.$abcart[$user_id]->updated_at->format('d/m/Y H:i')

            ];

        }

        endforeach;

        dd($subs);
        return $subs;

        // if (count($subs) > 0) {
        //     //$subs = $subs->toArray();
        //     Excel::create('Knowcrunch Abandonded Cart list', function($excel) use ($subs) {
        //         $excel->setTitle('AbandonedCart');
        //         $excel->setCreator('Darkpony')->setCompany('Darkpony');
        //         $excel->setDescription('AbandondedCart Exported');
        //         $excel->sheet('Sheetname', function($sheet) use ($subs) {
        //             $sheet->fromArray($subs, null, 'A1', false, false);
        //         });
        //     })->download('csv');
        // } else {
        //     Flash::error('No data found matching your criteria');
        //     return redirect()->route('listAbandoned');
        // }
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




