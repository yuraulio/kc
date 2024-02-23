<?php

namespace App\Http\Controllers;

use App\DataTables\Transactions\TransactionParticipantsDataTable;
use App\Exports\TransactionExport;
use App\Helpers\EventHelper;
use App\Model\Event;
use App\Model\PaymentMethod;
use App\Model\Transaction;
use App\Model\User;
use App\Notifications\WelcomeEmail;
use Auth;
use Excel;
use File;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use ZipArchive;

class TransactionController extends Controller
{
    /*public function participants($start_date = null, $end_date = null)
    {
        $userRole = Auth::user()->role->pluck('id')->toArray();

        if($start_date && $end_date){
            $start_date = date_create($start_date);
            $start_date = date_format($start_date,"Y-m-d");
            $end_date = date_create($end_date);
            $end_date = date_format($end_date,"Y-m-d");

            $from = date($start_date);
            $to = date($end_date);

            $transactions = Transaction::with('user.statisticGroupByEvent','user.events','user.ticket','subscription','event','event.delivery','event.category')->whereBetween('created_at', [$from,$to])->orderBy('created_at','desc')->get();

        }else{
            $transactions = Transaction::with('user.statisticGroupByEvent','user.events','user.ticket','subscription','event','event.delivery','event.category')->where('status', 1)->orderBy('created_at','desc')->get();

        }


        $data['transactions'] = [];
        foreach($transactions as $transaction){
            if(!$transaction->subscription->first() && $transaction->user->first() && $transaction->event->first()){

                $isElearning = $transaction->event->first()->delivery->first() && $transaction->event->first()->delivery->first()->id == 143;


                $category =  $transaction->event->first()->category->first() ? $transaction->event->first()->category->first()->id : -1;

                if(in_array(9,$userRole) &&  ($category !== 46)){
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
                                            'event_id' => $transaction->event[0]['id'],'event_title' => $transaction->event[0]['title'].' / '.date('d-m-Y', strtotime($transaction->event[0]['published_at'])),'coupon_code' => $coupon_code, 'type' => $ticketType,'ticketName' => $ticketName,
                                            'date' => date_format($transaction['created_at'], 'Y-m-d'), 'amount' => $transaction['amount'],
                                            'is_elearning' => $isElearning,
                                            'coupon_code' => $transaction['coupon_code'],'videos_seen' => $this->getVideosSeen($videos),'expiration'=>$expiration];
            }

        }
        return $data;

    }*/

    public function participants($start_date = null, $end_date = null, $homepage = false)
    {
        $userRole = Auth::user()->role->pluck('id')->toArray();

        $paymentMethods = [];

        foreach (PaymentMethod::all() as $paymentMethod) {
            $paymentMethods[$paymentMethod->id] = $paymentMethod->method_name;
        }

        if ($start_date && $end_date) {
            $start_date = date_create($start_date);
            $start_date = date_format($start_date, 'Y-m-d');
            $end_date = date_create($end_date);
            $end_date = date_format($end_date, 'Y-m-d');

            $from = date($start_date);
            $to = date($end_date);

            if (!$homepage) {
                $transactions = Transaction::with('user.statisticGroupByEvent', 'user.events_for_user_list', 'user.ticket', 'subscription', 'event', 'event.delivery', 'event.category', 'event.city')->whereBetween('created_at', [$from, $to])->where('status', 1)->orderBy('created_at', 'desc')->get();
            } else {
                $transactions = Transaction::with('user.ticket', 'subscription', 'event', 'event.delivery', 'event.category', 'event.city')->whereBetween('created_at', [$from, $to])->where('status', 1)->orderBy('created_at', 'desc')->get();
            }
        } else {
            if (!$homepage) {
                $transactions = Transaction::with('user.statisticGroupByEvent', 'user.events_for_user_list', 'user.ticket', 'subscription', 'event', 'event.delivery', 'event.category', 'event.city')->where('status', 1)->orderBy('created_at', 'desc')->get();
            } else {
                $transactions = Transaction::with('user.ticket', 'subscription', 'event', 'event.delivery', 'event.category', 'event.city')->where('status', 1)->orderBy('created_at', 'desc')->get();
            }
        }

        $earlyCount = 0;
        $data['transactions'] = [];
        $data['total_users'] = 0;
        $data['usersElearningAll'] = 0;
        $data['usersInClassAll'] = 0;
        $data['usersElearningIncomeAll'] = 0;
        $data['usersInClassIncomeAll'] = 0;
        $total_users = [];
        $usersElearningAll = [];
        $usersInClassAll = [];
        // $income['special'] = 0.0;
        // $income['alumni'] = 0.0;
        // $income['early'] = 0.0;
        // $income['regular'] = 0.0;
        // $income['other'] = 0.0;
        // $income['total'] = 0.0;

        foreach ($transactions as $key => $transaction) {
            if (!$transaction->subscription->first() && $transaction->user->first() && $transaction->event->first()) {
                $isElearning = $transaction->event->first()->delivery->first() && $transaction->event->first()->delivery->first()->id == 143;

                $category = $transaction->event->first()->category->first() ? $transaction->event->first()->category->first()->id : -1;

                if (in_array(9, $userRole) && ($category !== 46)) {
                    continue;
                }

                $tickets = $transaction->user->first()['ticket']->groupBy('event_id');
                $ticketType = isset($tickets[$transaction->event->first()->id]) ? $tickets[$transaction->event->first()->id]->first()->type : '-';

                if (isset($tickets[$transaction->event->first()->id])) {
                    $ticketType = $tickets[$transaction->event->first()->id]->first()->type;
                    $ticketName = $tickets[$transaction->event->first()->id]->first()->title;
                } else {
                    $ticketType = '-';
                    $ticketName = '-';
                }
                if ($ticketType == 'Early Bird') {
                    $earlyCount += 1;
                }

                $countUsers = count($transaction->user);
                $amount = $transaction['amount'] / $countUsers;

                foreach ($transaction['user'] as $u) {
                    // if($isElearning){

                    //     //$data['usersElearningAll']++;
                    //     $data['usersElearningIncomeAll'] = $data['usersElearningIncomeAll'] + $amount;

                    // }else{

                    //     //$data['usersInClassAll']++;
                    //     $data['usersInClassIncomeAll'] = $data['usersInClassIncomeAll'] + $amount;

                    // }

                    if ($isElearning) {
                        $usersElearningAll[$u['firstname'] . '_' . $u['lastname']] = $u['firstname'] . '_' . $u['lastname'];
                    } else {
                        $usersInClassAll[$u['firstname'] . '_' . $u['lastname']] = $u['firstname'] . '_' . $u['lastname'];
                    }

                    $total_users[$u['firstname'] . '_' . $u['lastname']] = $u['firstname'] . '_' . $u['lastname'];

                    if (!$homepage) {
                        $statistic = $u->statisticGroupByEvent->groupBy('event_id');

                        $videos = isset($statistic[$transaction->event->first()->id]) ?
                        $statistic[$transaction->event->first()->id]->first()->pivot : null;
                        $videos = isset($videos) ? json_decode($videos->videos, true) : null;
                        $events = $u->events_for_user_list->groupBy('id');
                        $expiration = isset($events[$transaction->event->first()->id]) ? $events[$transaction->event->first()->id]->first()->pivot->expiration : null;
                        $paymentMethodId = isset($events[$transaction->event->first()->id]) ? $events[$transaction->event->first()->id]->first()->pivot->payment_method : 0;
                        $paymentMethod = isset($paymentMethods[$paymentMethodId]) ? $paymentMethods[$paymentMethodId] : 'Alpha Bank';
                    }

                    $city = !empty($transaction->event[0]['city']) && isset($transaction->event[0]['city'][0]) ? $transaction->event[0]['city'][0]['name'] : '';

                    if (!$homepage) {
                        $data['transactions'][] = ['id' => $transaction['id'], 'user_id' => $u['id'], 'name' => $u['firstname'] . ' ' . $u['lastname'],
                            'event_id' => $transaction->event[0]['id'],
                            'event_title' => $transaction->event[0]['title'] . ' / ' . date('d-m-Y', strtotime($transaction->event[0]['published_at'])),
                            'type' => trim($ticketType),
                            'ticketName' => $ticketName,
                            'date' => date_format($transaction['created_at'], 'Y-m-d'), 'amount' => $amount,
                            'is_elearning' => $isElearning,
                            'coupon_code' => empty($transaction['coupon_code']) ? '-' : $transaction['coupon_code'],
                            'videos_seen' => $this->getVideosSeen($videos),
                            'expiration'=>$expiration,
                            'paymentMethod' => $paymentMethod,
                            'city' => $city,
                            'category' => isset($transaction->event[0]['category'][0]['name']) ? $transaction->event[0]['category'][0]['name'] : ''];
                    } else {
                        $data['transactions'][] = [
                            'id' => $transaction['id'],
                            'user_id' => $u['id'],
                            'name' => $u['firstname'] . ' ' . $u['lastname'],
                            'event_id' => $transaction->event[0]['id'], 'event_title' => $transaction->event[0]['title'] . ' / ' . date('d-m-Y', strtotime($transaction->event[0]['published_at'])),
                            'type' => trim($ticketType),
                            'ticketName' => $ticketName,
                            'date' => date_format($transaction['created_at'], 'Y-m-d'), 'amount' => $amount,
                            'is_elearning' => $isElearning,
                            'coupon_code' => empty($transaction['coupon_code']) ? '-' : $transaction['coupon_code'],
                            'city' => $city,
                            'category' => isset($transaction->event[0]['category'][0]['name']) ? $transaction->event[0]['category'][0]['name'] : '',
                        ];
                    }
                }
            }
        }

        $data['usersElearningAll'] = count($usersElearningAll);
        $data['usersInClassAll'] = count($usersInClassAll);

        $data['total_users'] = count($total_users);

        return $data;
    }

    public function participantsNew($start_date = null, $end_date = null)
    {
        $userRole = Auth::user()->role->pluck('id')->toArray();

        $paymentMethods = [];

        foreach (PaymentMethod::all() as $paymentMethod) {
            $paymentMethods[$paymentMethod->id] = $paymentMethod->method_name;
        }

        if ($start_date && $end_date) {
            $start_date = date_create($start_date);
            $start_date = date_format($start_date, 'Y-m-d');
            $end_date = date_create($end_date);
            $end_date = date_format($end_date, 'Y-m-d');

            $from = date($start_date);
            $to = date($end_date);

            $transactions = Transaction::with('user.statisticGroupByEvent', 'user.events_for_user_list', 'user.ticket', 'subscription', 'event', 'event.delivery', 'event.category', 'invoice')->whereBetween('created_at', [$from, $to])->orderBy('created_at', 'desc')->get();
        } else {
            //dd('fsad');
            $transactions = Transaction::with('user.statisticGroupByEvent', 'user.events_for_user_list', 'user.ticket', 'subscription', 'event', 'event.delivery', 'event.category', 'invoice')->where('status', 1)->orderBy('created_at', 'desc')->get();
        }

        $earlyCount = 0;
        $data['transactions'] = [];
        $doubleTransactions = [];
        $data['total_users'] = 0;
        $total_users = [];
        $total_users_elearning = [];
        $total_users_inclass = [];

        // $data['paid_installments_inclass'] = 0;
        // $data['paid_installments_elearning'] = 0;

        // $data['usersElearningIncomeAll'] = 0;
        // $data['usersInClassIncomeAll'] = 0;

        // $income['special'] = 0;
        // $income['early'] = 0;
        // $income['regular'] = 0;
        // $income['alumni'] = 0;
        // $income['other'] = 0;
        // $income['total'] = 0;

        foreach ($transactions as $transaction) {
            // if(count($transaction['invoice']) != 0 && $transaction['total_amount'] != 0){
            //     $test[] =$transaction;
            // }
            $event = $transaction->event->first();
            $eventTickets = [];
            if (isset($event->ticket)) {
                foreach ($event->ticket as $ticket) {
                    $eventTickets[$ticket->id] = $ticket['pivot']['price'] != null ? $ticket['pivot']['price'] : 0;
                }
            }

            $countUsers = count($transaction->user);

            $amount = ($transaction['amount'] != null && $transaction['amount'] != 0 && $countUsers != 0) ? $transaction['amount'] / $countUsers : 0;

            if (!$transaction->subscription->first() && $transaction->user->first() && $event) {
                $isElearning = $event->delivery->first() && $event->delivery->first()->id == 143;

                // if($isElearning){

                //     $data['usersElearningIncomeAll'] = $data['usersElearningIncomeAll'] + $amount;

                // }else{

                //     $data['usersInClassIncomeAll'] = $data['usersInClassIncomeAll'] + $amount;

                // }

                $category = $event->category->first() ? $event->category->first()->id : -1;

                if (in_array(9, $userRole) && ($category !== 46)) {
                    continue;
                }

                $tickets = $transaction->user->first()['ticket']->groupBy('event_id');
                $ticketType = isset($tickets[$event->id]) ? $tickets[$event->id]->first()->type : '-';

                if (isset($tickets[$event->id])) {
                    $ticketType = $tickets[$event->id]->first()->type;
                    $ticketName = $tickets[$event->id]->first()->title;
                } else {
                    $ticketType = '-';
                    $ticketName = '-';
                }

                if ($transaction['coupon_code'] != '') {
                    $coupon_code = $transaction['coupon_code'];
                } else {
                    $coupon_code = '-';
                }

                if ($ticketType == 'Early Bird') {
                    $earlyCount += 1;
                }

                foreach ($transaction['user'] as $u) {
                    $userTickets = $u['ticket'];

                    if ($isElearning) {
                        $total_users_elearning[$u['firstname'] . '_' . $u['lastname']] = $u['firstname'] . '_' . $u['lastname'];
                    } else {
                        $total_users_inclass[$u['firstname'] . '_' . $u['lastname']] = $u['firstname'] . '_' . $u['lastname'];
                    }

                    $total_users[$u['firstname'] . '_' . $u['lastname']] = $u['firstname'] . '_' . $u['lastname'];

                    $statistic = $u->statisticGroupByEvent->groupBy('event_id');
                    $videos = isset($statistic[$event->id]) ?
                    $statistic[$event->id]->first()->pivot : null;

                    $events = $u->events_for_user_list->groupBy('id');
                    $expiration = isset($events[$event->id]) ? $events[$event->id]->first()->pivot->expiration : null;
                    $paymentMethodId = isset($events[$event->id]) ? $events[$event->id]->first()->pivot->payment_method : 0;
                    $videos = isset($videos) ? json_decode($videos->videos, true) : null;

                    $paymentMethod = isset($paymentMethods[$paymentMethodId]) ? $paymentMethods[$paymentMethodId] : 'Alpha Bank';

                    if (count($transaction->invoice) > 0) {
                        foreach ($transaction->invoice as $invoice) {
                            $amount = ($invoice->amount != null && $invoice->amount != 0 && $countUsers != 0) ? ($invoice->amount / $countUsers) : 0;

                            if ($invoice->amount == null) {
                                $amount = $transaction->amount / $countUsers;
                            }

                            //$transaction[$key]['paid_from_installments'] += ($amount);

                            // if($ticketType == 'Special'){
                            //     $income['special'] = $income['special'] + $amount;
                            // }else if($ticketType == 'Early Bird'){
                            //     $income['early'] = $income['early'] + $amount;
                            // }else if($ticketType == 'Regular'){
                            //     $income['regular'] = $income['regular'] + $amount;

                            // }else if($ticketType == 'Alumni'){
                            //     $income['alumni'] = $income['alumni'] + $amount;

                            // }else{
                            //     $income['other'] = $income['other'] + $amount;
                            // }

                            // if($isElearning){
                            //     $data['paid_installments_elearning'] = $data['paid_installments_elearning'] + $amount;
                            // }else{
                            //     $data['paid_installments_inclass'] = $data['paid_installments_inclass'] + $amount;
                            // }

                            $data['transactions'][] = ['id' => $transaction['id'], 'user_id' => $u['id'], 'name' => $u['firstname'] . ' ' . $u['lastname'],
                                'event_id' => $transaction->event[0]['id'], 'event_title' => $transaction->event[0]['title'] . ' / ' . date('d-m-Y', strtotime($transaction->event[0]['published_at'])), 'coupon_code' => $coupon_code, 'type' => trim($ticketType), 'ticketName' => $ticketName,
                                'date' => date_format($invoice['created_at'], 'Y-m-d'), 'amount' => $invoice->amount,
                                'is_elearning' => $isElearning,
                                'coupon_code' => $transaction['coupon_code'], 'videos_seen' => $this->getVideosSeen($videos), 'expiration'=>$expiration,
                                'paymentMethod' => $paymentMethod, 'ticket_price' => $transaction['amount']/*(!in_array($transaction['id'], $doubleTransactions) ? $transaction['amount'] : 0*/];

                            $doubleTransactions[] = $transaction['id'];
                        }
                    } else {
                        // if has transaction without invoice and set installments -> without paid first installment
                        if (!isset($transaction->status_history[0]['installments'])) {
                            // if($isElearning){
                            //     $data['paid_installments_elearning'] = $data['paid_installments_elearning'] + $amount;
                            // }else{
                            //     $data['paid_installments_inclass'] = $data['paid_installments_inclass'] + $amount;
                            // }

                            // if($ticketType == 'Special'){

                            //     $income['special'] = $income['special'] + $amount;

                            // }else if($ticketType == 'Early Bird'){

                            //     $income['early'] = $income['early'] + $amount;

                            // }else if($ticketType == 'Regular'){

                            //     $income['regular'] = $income['regular'] + $amount;

                            // }else if($ticketType == 'Alumni'){

                            //     $income['alumni'] = $income['alumni'] + $amount;

                            // }else{

                            //     $income['other'] = $income['other'] + $amount;
                            // }
                        }

                        $data['transactions'][] = ['id' => $transaction['id'], 'user_id' => $u['id'], 'name' => $u['firstname'] . ' ' . $u['lastname'],
                            'event_id' => $transaction->event[0]['id'], 'event_title' => $transaction->event[0]['title'] . ' / ' . date('d-m-Y', strtotime($transaction->event[0]['published_at'])), 'coupon_code' => $coupon_code, 'type' => trim($ticketType), 'ticketName' => $ticketName,
                            'date' => date_format($transaction['created_at'], 'Y-m-d'), 'amount' => $transaction['amount'] / $countUsers,
                            'is_elearning' => $isElearning,
                            'coupon_code' => $transaction['coupon_code'], 'videos_seen' => $this->getVideosSeen($videos), 'expiration'=>$expiration,
                            'paymentMethod' => $paymentMethod, 'ticket_price' => $transaction['amount']];
                    }

                    break;
                }
            }
        }

        $data['total_users_elearning'] = count($total_users_elearning);
        $data['total_users_inclass'] = count($total_users_inclass);
        $data['total_users'] = count($total_users);

        // $data['income'] = $income;
        // $data['income']['total'] = array_sum($income);

        return $data;
    }

    // public function statistics($transactions)
    // {
    //     $data = [];

    //     $data['usersInclassAll'] = User::whereHas('events_for_user_list1', function ($q) {
    //         $q->wherePublished(true)->where('event_user.paid', true)->where(function ($q1) {
    //             $q1->doesntHave('delivery')->OrWhereHas('delivery', function ($q2) {
    //                 return $q2->where('deliveries.id', '<>', 143);
    //             });
    //         });
    //     })->count();

    //     foreach($transactions){

    //     }

    //     $data['usersElearningAll'] = User::whereHas('events_for_user_list1', function ($q) {

    //         $q->wherePublished(true)->where('event_user.paid', true)->whereHas('delivery', function ($q1) {
    //             return $q1->where('deliveries.id', 143);
    //         });
    //     })->count();

    //     $data['inClassIncome'] = Transaction::whereHas('event', function($q){

    //         $q->doesntHave('delivery')->OrWhereHas('delivery', function ($q1) {
    //             return $q1->where('deliveries.id', '<>', 143);
    //         });
    //     })->sum('total_amount');

    //     dd($data['inClassIncome']);
    //     //$data['elearningIncomeAll']

    //     return $data;

    // }

    public function participants_inside_revenue()
    {
        $this->authorize('view', [User::class, Transaction::class]);
        $data = $this->participants();

        return view('admin.transaction.participants', $data);
    }

    public function participants_inside_revenue_new()
    {
        $this->authorize('view', User::class, Transaction::class);

        $data = $this->participantsNew();

        $data['events'] = Event::select('title', 'published_at')->orderBy('published_at', 'desc')->get()->toJson();

        return view('admin.transaction.revenue', $data);
    }

    public function participants_for_select_date($start_date, $end_date)
    {
        return $this->participants($start_date, $end_date);
    }

    public function updateExpirationDate(Request $request)
    {
        $transaction_id = $request->id;
        $date = date('Y-m-d', strtotime($request->date));

        $user = User::find($request->user_id);

        if (!$user) {
            return response()->json([
                'message' => 'User not found!',
            ]);
        }

        $event = $user->events_for_user_list->where('id', $request->event_id)->first();
        if (!$event) {
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
            'data' => $data,
        ]);
    }

    public function getVideosSeen($videos)
    {
        return EventHelper::getVideosSeen($videos);
    }

    public function update(Request $request)
    {
        //dd($request->all());

        $data['id'] = $request->input('transaction');
        $data['status'] = $request->input('statusTr');
        $data['newevent'] = $request->input('newevent');

        foreach ($data['id'] as $key => $id) {
            $transaction = Transaction::find($id);

            if ($transaction) {
                $transaction->status = $data['status'][$key];
                $transaction->save();

                $us = User::find($request->users[$key]);

                if ($data['status'][$key] > 0) {
                    //dd($us->events_for_user_list()->wherePivot('event_id', $request->oldevents[$key])->first());

                    if ($us->events_for_user_list()->wherePivot('event_id', $request->oldevents[$key])->first()) {
                        $oldEventPivotData = $us->events_for_user_list()->wherePivot('event_id', $request->oldevents[$key])->first()->pivot;
                        $us->events_for_user_list()->detach($request->oldevents[$key]);

                        $us->events_for_user_list()->attach($request->newevents[$key], [
                            'paid' => $data['status'][$key] == 1 ? true : false,
                            'expiration' => $oldEventPivotData['expiration'],
                            'payment_method' => $oldEventPivotData['payment_method'],
                            'expiration_email' => $oldEventPivotData['expiration_email'] ? $oldEventPivotData['expiration_email'] : 0,
                            'comment' => $oldEventPivotData['comment'],
                        ]);
                    }

                    $transaction->event()->detach($request->oldevents[$key]);
                    $transaction->event()->attach($request->newevents[$key]);
                } else {
                    $us->events_for_user_list()->detach($request->oldevents[$key]);
                    $transaction->event()->detach($request->oldevents[$key]);
                    $transaction->user()->detach($us);
                }
            }
        }

        return back();
    }

    public function exportExcel(Request $request)
    {
        //$fromDate = date('Y-m-d',strtotime($request->fromDate));
        //$toDate = $request->toDate ? date('Y-m-d',strtotime($request->toDate)) : date('Y-m-d');

        $data = new TransactionExport($request);

        Excel::store($data, 'TransactionsExport.xlsx', 'export');

        return response()->json(['data' => ['success' => true]]);
        //return Excel::download($data, 'TransactionsExport.xlsx');
    }

    public function exportInvoices(Request $request)
    {
        $userRole = Auth::user()->role->pluck('id')->toArray();
        if (in_array(9, $userRole)) {
            return response()->json(['message' => 'You don\'t have access'], 401);
        }

        $transactions = Transaction::query()
            ->when($request->transactions, function (Builder $query, $value) {
                $query->whereIn('id', $value);
            })
            ->when($request->has('filter'), function (Builder $query) use ($request) {
                $dataTable = new TransactionParticipantsDataTable();
                $subQuery = $dataTable->applyFilters(
                    $dataTable->query(new Transaction)->select([
                        'transactions.id',
                    ]),
                    $request
                );
                $query->whereIn('id', $subQuery);
            })
            ->with('user.events_for_user_list', 'user.ticket', 'subscription', 'event', 'event.delivery', 'event.category')
            ->get();

        $fileName = 'invoices.zip';
        File::deleteDirectory(public_path('invoices_folder'));
        File::makeDirectory(public_path('invoices_folder'));

        if (File::exists(public_path($fileName))) {
            unlink(public_path($fileName));
        }

        $invoicesNumber = [];

        $zip = new ZipArchive();
        if ($zip->open(public_path($fileName), ZipArchive::CREATE) === true) {
            foreach ($transactions as $transaction) {
                foreach ($transaction->invoice as $invoice) {
                    $invoicesNumber = $invoice->getZipOfInvoices($zip, $planDecription = false, $invoicesNumber);

                    // $zip->addFile(public_path('invoices_folder/'.$invoice->getInvoice()), $invoice->getInvoice());
                }
            }
        }

        $zip->close();
        File::deleteDirectory(public_path('invoices_folder'));

        return response()->json(['zip' => url('/') . '/invoices.zip']);

        //return response()->download(public_path($fileName));
    }
}
