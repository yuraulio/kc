<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Model\Transaction;
use App\Model\Event;
use App\Model\User;
use Auth;

class TransactionController extends Controller
{

    public function participants($start_date = null, $end_date = null)
    {
        $this->authorize('view',User::class,Transaction::class);
        $userRole = Auth::user()->role->pluck('id')->toArray();

        if($start_date && $end_date){
            $start_date = date_create($start_date);
            $start_date = date_format($start_date,"Y-m-d");
            $end_date = date_create($end_date);
            $end_date = date_format($end_date,"Y-m-d");

            $from = date($start_date);
            $to = date($end_date);

            $transactions = Transaction::with('user.statisticGroupByEvent','user.events','user.ticket','subscription','event','event.delivery')->whereBetween('created_at', [$from,$to])->orderBy('created_at','desc')->get();

        }else{
            $transactions = Transaction::with('user.statisticGroupByEvent','user.events','user.ticket','subscription','event','event.delivery')->where('status', 1)->orderBy('created_at','desc')->get();

        }


        $data['transactions'] = [];
        foreach($transactions as $transaction){
            if(!$transaction->subscription->first() && $transaction->user->first() && $transaction->event->first()){

                $isElearning = $transaction->event->first()->delivery->first() && $transaction->event->first()->delivery->first()->id == 143;

                if(in_array(9,$userRole) &&  $isElearning){
                    continue;
                }

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

                $data['transactions'][] = ['id' => $transaction['id'], 'user_id' => $transaction->user[0]['id'],'name' => $transaction->user[0]['firstname'].' '.$transaction->user[0]['lastname'],
                                            'event_id' => $transaction->event[0]['id'],'event_title' => $transaction->event[0]['title'].'-'.$transaction->event[0]['id'],'coupon_code' => $coupon_code, 'type' => $ticketType,'ticketName' => $ticketName,
                                            'date' => date_format($transaction['created_at'], 'Y-m-d'), 'amount' => $transaction['amount'],
                                            'is_elearning' => $isElearning,
                                            'coupon_code' => $transaction['coupon_code'],'videos_seen' => $this->getVideosSeen($videos),'expiration'=>$expiration];
            }

        }
        return $data;

    }

    public function participants_inside_revenue()
    {

        $data['transactions'] = $this->participants()['transactions'];
        return view('admin.transaction.participants', $data);
    }

    public function participants_for_select_date($start_date, $end_date)
    {


        return $this->participants($start_date, $end_date);

    }

    public function updateExpirationDate(Request $request)
    {


        $transaction_id = $request->id;
        $date = date('Y-m-d',strtotime($request->date));

        $user = User::find($request->user_id);

        if(!$user){
            return response()->json([
                'message' => 'User not found!',
            ]);
        }

        $event = $user->events->where('id',$request->event_id)->first();
        if(!$event){
            return response()->json([
                'message' => 'User has not be assigned to event!',
            ]);
        }



        $event->pivot->expiration = $date;
        $event->pivot->save();

        $data['id'] = $request->event_id;
        $data['date'] = $date;

        return response()->json([
            'message' => 'Expiration date has updated!',
            'data' => $data
        ]);




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
