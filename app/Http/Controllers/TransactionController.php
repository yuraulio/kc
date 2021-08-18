<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Model\Transaction;
use App\Model\Event;
use App\Model\User;

class TransactionController extends Controller
{
    public function participants()
    {
        //$data['transactions'] = Transaction::has('user')->has('event')/*doesntHave('subscription')*/;
        //$data['transactions'] = $data['transactions']/*->has('user')*/->doesntHave('subscription');
        //$data['transactions'] = $data['transactions']->get();

        $transactions = Transaction::with('user.statisticGroupByEvent','user.events','user.ticket','subscription','event','event.delivery')->orderBy('created_at','desc')->get();
        //dd($transactions[0]);
        $data['transactions'] = [];
        foreach($transactions as $transaction){
            if(!$transaction->subscription->first() && $transaction->user->first() && $transaction->event->first()){

                $statistic = $transaction->user->first()->statisticGroupByEvent->groupBy('event_id');

                $tickets = $transaction->user->first()['ticket']->groupBy('event_id');
                $ticketType = isset($tickets[$transaction->event->first()->id]) ? $tickets[$transaction->event->first()->id]->first()->type : '-';

                if(isset($tickets[$transaction->event->first()->id])){
                    $ticketType = $tickets[$transaction->event->first()->id]->first()->type;
                    $ticketName = $tickets[$transaction->event->first()->id]->first()->title;

                }else{
                    $ticketType = '-';
                    $ticketName = '-';
                }

                if($transaction['coupon_code'] != ''){
                    $coupon_code = $transaction['coupon_code'];
                }else{
                    $coupon_code = '-';
                }

                $videos = isset($statistic[$transaction->event->first()->id]) ?
                    $statistic[$transaction->event->first()->id]->first()->pivot : null;

                $events = $transaction->user->first()->events->groupBy('id');
                $expiration = isset($events[$transaction->event->first()->id]) ? $events[$transaction->event->first()->id]->first()->pivot->expiration : null;
                $videos = isset($videos) ? json_decode($videos->videos,true) : null;

                $isElearning = $transaction->event->first()->delivery->first() && $transaction->event->first()->delivery->first()->id == 143;

                $data['transactions'][] = ['id' => $transaction['id'], 'user_id' => $transaction->user[0]['id'],'name' => $transaction->user[0]['firstname'].' '.$transaction->user[0]['lastname'],
                                            'event_id' => $transaction->event[0]['id'],'event_title' => $transaction->event[0]['title'],'coupon_code' => $coupon_code, 'type' => $ticketType,'ticketName' => $ticketName,
                                            'date' => date_format($transaction['created_at'], 'm/d/Y'), 'amount' => $transaction['amount'],
                                            'is_elearning' => $isElearning,
                                            'coupon_code' => $transaction['coupon_code'],'videos_seen' => $this->getVideosSeen($videos),'expiration'=>$expiration];
            }

        }
        //dd($data['transactions'][0]);
        return $data;

        //dd($data['transactions']);
        //return view('admin.transaction.participants', $data);
    }

    public function participants_for_select_date($start_date, $end_date)
    {
        $start_date = date_create($start_date);
        $start_date = date_format($start_date,"Y-m-d");
        $end_date = date_create($end_date);
        $end_date = date_format($end_date,"Y-m-d");

        $from = date($start_date);
        $to = date($end_date);

        //dd('for participants selected date');
        //$data['transactions'] = Transaction::has('user')->has('event')/*doesntHave('subscription')*/;
        //$data['transactions'] = $data['transactions']/*->has('user')*/->doesntHave('subscription');
        //$data['transactions'] = $data['transactions']->get();

        $transactions = Transaction::with('user.statisticGroupByEvent','user.events','user.ticket','subscription','event','event.delivery')->whereBetween('created_at', [$from,$to])->orderBy('created_at','desc')->get();
        //dd($transactions[0]);
        $data['transactions'] = [];
        foreach($transactions as $transaction){
            if(!$transaction->subscription->first() && $transaction->user->first() && $transaction->event->first()){

                $statistic = $transaction->user->first()->statisticGroupByEvent->groupBy('event_id');

                $tickets = $transaction->user->first()['ticket']->groupBy('event_id');
                $ticketType = isset($tickets[$transaction->event->first()->id]) ? $tickets[$transaction->event->first()->id]->first()->type : '-';

                if(isset($tickets[$transaction->event->first()->id])){
                    $ticketType = $tickets[$transaction->event->first()->id]->first()->type;
                    $ticketName = $tickets[$transaction->event->first()->id]->first()->title;

                }else{
                    $ticketType = '-';
                    $ticketName = '-';
                }

                if($transaction['coupon_code'] != ''){
                    $coupon_code = $transaction['coupon_code'];
                }else{
                    $coupon_code = '-';
                }

                $videos = isset($statistic[$transaction->event->first()->id]) ?
                    $statistic[$transaction->event->first()->id]->first()->pivot : null;

                $events = $transaction->user->first()->events->groupBy('id');
                $expiration = isset($events[$transaction->event->first()->id]) ? $events[$transaction->event->first()->id]->first()->pivot->expiration : null;
                $videos = isset($videos) ? json_decode($videos->videos,true) : null;

                $isElearning = $transaction->event->first()->delivery->first() && $transaction->event->first()->delivery->first()->id == 143;

                $data['transactions'][] = ['id' => $transaction['id'], 'user_id' => $transaction->user[0]['id'],'name' => $transaction->user[0]['firstname'].' '.$transaction->user[0]['lastname'],
                                            'event_id' => $transaction->event[0]['id'],'event_title' => $transaction->event[0]['title'],'coupon_code' => $coupon_code, 'type' => $ticketType,'ticketName' => $ticketName,
                                            'date' => date_format($transaction['created_at'], 'm/d/Y'), 'amount' => $transaction['amount'],
                                            'is_elearning' => $isElearning,
                                            'coupon_code' => $transaction['coupon_code'],'videos_seen' => $this->getVideosSeen($videos),'expiration'=>$expiration];
            }

        }
        //dd($data['transactions'][0]);
        return $data;

        //dd($data['transactions']);
        //return view('admin.transaction.participants', $data);
    }

    public function updateExpirationDate(Request $request)
    {
        $transaction_id = $request->id;
        $date = $request->date;

        $transaction = Transaction::with('user', 'event')->find($transaction_id);
        //dd($transaction);
        if(isset($transaction->user[0])){
            $user = $transaction->user[0]['id'];
            $event = $transaction->event[0]['id'];

            $event = Event::find($event);

            //dd($event->users()->wherePivot('user_id',$user)->first()->pivot->expiration);

            $old_date = explode(" ",$event->users()->wherePivot('user_id',$user)->first()->pivot->expiration);

            if(isset($old_date[1])){
                $old_date = $old_date[1];
            }else{
                $old_date = $old_date[0];
            }

            $new_date = $date.' '.$old_date;
            //dd($new_date);
            $new_date = date('Y-m-d', strtotime($new_date));
            $res_new_date = date('d/m/Y', strtotime($new_date));
            //dd($res_new_date);

            $event->users()->wherePivot('user_id',$user)->updateExistingPivot($user,[
                'expiration' => $new_date
            ], false);


            $data['id'] = $transaction_id;
            $data['date'] = $res_new_date;

            return response()->json([
                'message' => 'Expiration date has updated!',
                'data' => $data
            ]);
        }else{
            return response()->json([
                'message' => 'Transaction has not user'
            ]);
        }



    }

    public function getVideosSeen($videos){
        if(!$videos){
            return' 0 of 0';
        }

        $sum = 0;
        foreach($videos as $video){
            if($video['seen'] == 1 || $video['seen'] == '1'){
                $sum++;
            }

        }
        return $sum.' of '.count($videos);
    }

    public function update(Request $request){

        //dd($request->all());

        $data['id'] = $request->input('transaction');
        $data['status'] = $request->input('statusTr');
        $data['newevent'] = $request->input('newevent');

        $transaction = Transaction::find($data['id']);

        if($transaction){
            $transaction->status =  $data['status'];
            $transaction->save();
        }

        foreach($request->users as $key => $user){
            $us = User::find($user);

            if($data['status'] > 0){
                $us->events()->detach($request->oldevents[$key]);
                $us->events()->attach($request->newevents[$key]);
    
                $transaction->event()->detach($request->oldevents[$key]);
                $transaction->event()->attach($request->newevents[$key]);
            }else{
               //dd($transaction);
                $us->events()->detach($request->oldevents[$key]);
                $transaction->event()->detach($request->oldevents[$key]);
                $transaction->user()->detach($us);
            }
           
        }

        return back();
    }

}
