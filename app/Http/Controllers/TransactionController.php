<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Model\Transaction;
use App\Model\Event;
use App\Model\User;
use Auth;
use Excel;
use App\Model\PaymentMethod;
use App\Exports\TransactionExport;
use ZipArchive;
use File;

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

    public function participants($start_date = null, $end_date = null){
        $userRole = Auth::user()->role->pluck('id')->toArray();

        $paymentMethods = [];

        foreach(PaymentMethod::all() as $paymentMethod){
            $paymentMethods[$paymentMethod->id] =  $paymentMethod->method_name;
        }
        
        
        if($start_date && $end_date){
            $start_date = date_create($start_date);
            $start_date = date_format($start_date,"Y-m-d");
            $end_date = date_create($end_date);
            $end_date = date_format($end_date,"Y-m-d");

            $from = date($start_date);
            $to = date($end_date);

            $transactions = Transaction::with('user.statisticGroupByEvent','user.events','user.ticket','subscription','event','event.delivery','event.category')->whereBetween('created_at', [$from,$to])->orderBy('created_at','desc')->get();

        }else{
            //dd('fsad');
            $transactions = Transaction::with('user.statisticGroupByEvent','user.events','user.ticket','subscription','event','event.delivery','event.category')->where('status', 1)->orderBy('created_at','desc')->get();

        }

        $earlyCount = 0;
        $data['transactions'] = [];
        foreach($transactions as $transaction){
            if(!$transaction->subscription->first() && $transaction->user->first() && $transaction->event->first()){

                $isElearning = $transaction->event->first()->delivery->first() && $transaction->event->first()->delivery->first()->id == 143;


                $category =  $transaction->event->first()->category->first() ? $transaction->event->first()->category->first()->id : -1;

                if(in_array(9,$userRole) &&  ($category !== 46)){
                    continue;
                }

                

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

                if($ticketType == 'Early Bird'){
                    $earlyCount += 1;
                }
                
                $countUsers = count($transaction->user);

                foreach($transaction['user'] as $u){

                    $statistic = $u->statisticGroupByEvent->groupBy('event_id');
                    $videos = isset($statistic[$transaction->event->first()->id]) ?
                    $statistic[$transaction->event->first()->id]->first()->pivot : null;

                    $events = $u->events->groupBy('id');
                    $expiration = isset($events[$transaction->event->first()->id]) ? $events[$transaction->event->first()->id]->first()->pivot->expiration : null;
                    $paymentMethodId = isset($events[$transaction->event->first()->id]) ? $events[$transaction->event->first()->id]->first()->pivot->payment_method : 0;
                    $videos = isset($videos) ? json_decode($videos->videos,true) : null;

                    $paymentMethod = isset($paymentMethods[$paymentMethodId]) ? $paymentMethods[$paymentMethodId] :'Alpha Bank';

                    $data['transactions'][] = ['id' => $transaction['id'], 'user_id' => $u['id'],'name' => $u['firstname'].' '.$u['lastname'],
                                                'event_id' => $transaction->event[0]['id'],'event_title' => $transaction->event[0]['title'].' / '.date('d-m-Y', strtotime($transaction->event[0]['published_at'])),'coupon_code' => $coupon_code, 'type' => trim($ticketType),'ticketName' => $ticketName,
                                                'date' => date_format($transaction['created_at'], 'Y-m-d'), 'amount' => $transaction['amount'] / $countUsers,
                                                'is_elearning' => $isElearning,
                                                'coupon_code' => $transaction['coupon_code'],'videos_seen' => $this->getVideosSeen($videos),'expiration'=>$expiration,
                                                'paymentMethod' => $paymentMethod];
                }
               
            }

        }

        return $data;

    }


    public function participantsNew($start_date = null, $end_date = null){
       
        $userRole = Auth::user()->role->pluck('id')->toArray();

        $paymentMethods = [];
        
        foreach(PaymentMethod::all() as $paymentMethod){
            $paymentMethods[$paymentMethod->id] =  $paymentMethod->method_name;
        }
        
        
        if($start_date && $end_date){
            $start_date = date_create($start_date);
            $start_date = date_format($start_date,"Y-m-d");
            $end_date = date_create($end_date);
            $end_date = date_format($end_date,"Y-m-d");

            $from = date($start_date);
            $to = date($end_date);

            $transactions = Transaction::with('user.statisticGroupByEvent','user.events','user.ticket','subscription','event','event.delivery','event.category','invoice')->whereBetween('created_at', [$from,$to])->orderBy('created_at','desc')->get();

        }else{
            //dd('fsad');
            $transactions = Transaction::with('user.statisticGroupByEvent','user.events','user.ticket','subscription','event','event.delivery','event.category','invoice')->where('status', 1)->orderBy('created_at','desc')->get();

        }

        $earlyCount = 0;
        $data['transactions'] = [];
        $doubleTransactions = [];

        foreach($transactions as $transaction){
            if(!$transaction->subscription->first() && $transaction->user->first() && $transaction->event->first()){

                $isElearning = $transaction->event->first()->delivery->first() && $transaction->event->first()->delivery->first()->id == 143;


                $category =  $transaction->event->first()->category->first() ? $transaction->event->first()->category->first()->id : -1;

                if(in_array(9,$userRole) &&  ($category !== 46)){
                    continue;
                }

                

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

                if($ticketType == 'Early Bird'){
                    $earlyCount += 1;
                }
                
                $countUsers = count($transaction->user);
               
                foreach($transaction['user'] as $u){

                    $statistic = $u->statisticGroupByEvent->groupBy('event_id');
                    $videos = isset($statistic[$transaction->event->first()->id]) ?
                    $statistic[$transaction->event->first()->id]->first()->pivot : null;

                    $events = $u->events->groupBy('id');
                    $expiration = isset($events[$transaction->event->first()->id]) ? $events[$transaction->event->first()->id]->first()->pivot->expiration : null;
                    $paymentMethodId = isset($events[$transaction->event->first()->id]) ? $events[$transaction->event->first()->id]->first()->pivot->payment_method : 0;
                    $videos = isset($videos) ? json_decode($videos->videos,true) : null;

                    $paymentMethod = isset($paymentMethods[$paymentMethodId]) ? $paymentMethods[$paymentMethodId] :'Alpha Bank';
                    
                    if(count($transaction->invoice) > 0 ){
                        
                        foreach($transaction->invoice as $invoice){
                            
                        
                            $data['transactions'][] = ['id' => $transaction['id'], 'user_id' => $u['id'],'name' => $u['firstname'].' '.$u['lastname'],
                            'event_id' => $transaction->event[0]['id'],'event_title' => $transaction->event[0]['title'].' / '.date('d-m-Y', strtotime($transaction->event[0]['published_at'])),'coupon_code' => $coupon_code, 'type' => trim($ticketType),'ticketName' => $ticketName,
                            'date' => date_format($invoice['created_at'], 'Y-m-d'), 'amount' => $invoice->amount,
                            'is_elearning' => $isElearning,
                            'coupon_code' => $transaction['coupon_code'],'videos_seen' => $this->getVideosSeen($videos),'expiration'=>$expiration,
                            'paymentMethod' => $paymentMethod, 'ticket_price' => $transaction['amount']/*(!in_array($transaction['id'], $doubleTransactions) ? $transaction['amount'] : 0*/];

                            $doubleTransactions[] = $transaction['id'];
                        }

                    }else{
                        
                        $data['transactions'][] = ['id' => $transaction['id'], 'user_id' => $u['id'],'name' => $u['firstname'].' '.$u['lastname'],
                        'event_id' => $transaction->event[0]['id'],'event_title' => $transaction->event[0]['title'].' / '.date('d-m-Y', strtotime($transaction->event[0]['published_at'])),'coupon_code' => $coupon_code, 'type' => trim($ticketType),'ticketName' => $ticketName,
                        'date' => date_format($transaction['created_at'], 'Y-m-d'), 'amount' => $transaction['amount'] / $countUsers,
                        'is_elearning' => $isElearning,
                        'coupon_code' => $transaction['coupon_code'],'videos_seen' => $this->getVideosSeen($videos),'expiration'=>$expiration,
                        'paymentMethod' => $paymentMethod, 'ticket_price' => $transaction['amount']];

                    }
                    
                   break;
                }

                
            }

        }

        return $data;

    }
    

    public function participants_inside_revenue()
    {
        $this->authorize('view',User::class,Transaction::class);
        $data['transactions'] = $this->participants()['transactions'];
        return view('admin.transaction.participants', $data);
    }

    public function participants_inside_revenue_new()
    {
        $this->authorize('view',User::class,Transaction::class);
        $data['transactions'] = $this->participantsNew()['transactions'];

        return view('admin.transaction.revenue', $data);
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

        foreach($data['id'] as $key => $id){
            $transaction = Transaction::find($id);

            if($transaction){
                $transaction->status =  $data['status'][$key];
                $transaction->save();

                $us = User::find($request->users[$key]);
                
                if($data['status'][$key] > 0){
                    
                    $us->events()->detach($request->oldevents[$key]);
                    $us->events()->attach($request->newevents[$key]);
        
                    $transaction->event()->detach($request->oldevents[$key]);
                    $transaction->event()->attach($request->newevents[$key]);

                }else{
                    
                    $us->events()->detach($request->oldevents[$key]);
                    $transaction->event()->detach($request->oldevents[$key]);
                    $transaction->user()->detach($us);
                }
    
            }

        }

        return back();
    }

    public function exportExcel(Request $request){
        
        //$fromDate = date('Y-m-d',strtotime($request->fromDate));
        //$toDate = $request->toDate ? date('Y-m-d',strtotime($request->toDate)) : date('Y-m-d');

        Excel::store(new TransactionExport($request), 'TransactionsExport.xlsx', 'export');
        return Excel::download(new TransactionExport($request), 'TransactionsExport.xlsx');
    }

    public function exportInvoices(Request $request){


      
        $transactions = Transaction::whereIn('id', $request->transactions)->
                                with('user.events','user.ticket','subscription','event','event.delivery','event.category')->get();
        $userRole = Auth::user()->role->pluck('id')->toArray();

        $fileName = 'invoices.zip';
        File::deleteDirectory(public_path('invoices_folder'));
        File::makeDirectory(public_path('invoices_folder'));

        if(File::exists(public_path($fileName))){
            unlink(public_path($fileName));
        }

        $invoicesNumber = [];

        $zip = new ZipArchive();
        if ($zip->open(public_path($fileName), ZipArchive::CREATE) === TRUE) {
            
            foreach($transactions as $transaction){
               

                if(in_array(9,$userRole)){
                    continue;
                }



                foreach($transaction->invoice as $invoice){
                    
                    $invoicesNumber = $invoice->getZipOfInvoices($zip,$planDecription = false,$invoicesNumber);

                   // $zip->addFile(public_path('invoices_folder/'.$invoice->getInvoice()), $invoice->getInvoice());

                }
                    
                


            }

        }

        $zip->close();
        File::deleteDirectory(public_path('invoices_folder'));
       
        return response()->json(['zip' => url('/') .'/invoices.zip']);

        //return response()->download(public_path($fileName));
       
    }

   

}
