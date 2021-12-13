<?php
/*

=========================================================
* Argon Dashboard PRO - v1.0.0
=========================================================

* Product Page: https://www.creative-tim.com/product/argon-dashboard-pro-laravel
* Copyright 2018 Creative Tim (https://www.creative-tim.com) & UPDIVISION (https://www.updivision.com)

* Coded by www.creative-tim.com & www.updivision.com

=========================================================

* The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.

*/
namespace App\Http\Controllers;

use App\Model\Role;
use App\Model\User;
use App\Model\Event;
use App\Model\Ticket;
use App\Model\Activation;
use App\Model\Transaction;
use \Cart as Cart;
use Carbon\Carbon;
use App\Model\Media;
use App\Model\Certificate;
use Session;
use App\Http\Requests\UserRequest;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\EventController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\Dashboard\CouponController;
use App\Notifications\userActivationLink;
use Mail;
use App\Model\Option;

use Illuminate\Http\Request;

class UserController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(User::class);
    }

    /**
     * Display a listing of the users
     *
     * @param  \App\Model\User  $model
     * @return \Illuminate\View\View
     */
    public function index(User $model)
    {

        $user = Auth::user();

        $data['all_users'] = $model::count();
        $data['total_graduates'] = total_graduate();


        $data['events'] = (new EventController)->fetchAllEvents();
        $data['transactions'] = (new TransactionController)->participants();
        $data['coupons'] = (new CouponController)->fetchAllCoupons();

        //groupby user_id(level1)
        $data['transactions'] = group_by('user_id', $data['transactions']['transactions']);

        //groupby event_id(level2)
        foreach($data['transactions'] as $key => $item){
            $data['transactions'][$key] = group_by('event_id', $item);
        }

        //dd($model->with('role', 'image','statusAccount', 'events_for_user_list')->get()[0]);
        //dd($data['transactions']);
        //dd($model->with('role', 'image')->get()[0]);

        //dd($model->with('role', 'image')->get()->toArray()[0]['image']);
        //dd($model->with('role', 'image','statusAccount', 'events_for_user_list')->get()->toArray()[10]);

        return view('users.index', ['users' => $model->with('role', 'image','statusAccount', 'events_for_user_list')->get(), 'data' => $data]);
    }


    /*
    public function index(User $model)
    {
        //$this->authorize('manage-users', User::class);
        //$user = Auth::user();

        $data['all_users'] = $model::count();
        $data['total_graduates'] = total_graduate();

        $data['users'] = $model->with('role', 'image','statusAccount', 'events_for_user_list','statisticGroupByEvent','events','ticket','transactionss')->get();

        //$this->getAllTransactions($data['users']);


        $data['events'] = (new EventController)->fetchAllEvents();
        $data['transactions'] = $this->getAllTransactions($data['users']);
        $data['coupons'] = (new CouponController)->fetchAllCoupons();


        //groupby user_id(level1)
        $data['transactions'] = group_by('user_id', $data['transactions']);

        //groupby event_id(level2)
        foreach($data['transactions'] as $key => $item){
            $data['transactions'][$key] = group_by('event_id', $item);
        }


        //dd($data['transactions']);
        //dd($model->with('role', 'image')->get()[0]);

        //dd($model->with('role', 'image')->get()->toArray()[0]['image']);
        //dd($model->with('role', 'image','statusAccount', 'events_for_user_list')->get()->toArray()[10]);
		dd('fsa');
        return view('users.index', ['users'=>$data['users'],'data' => $data]);
    }


    private function getAllTransactions($users){
        $data['transactions'] = [];
        foreach($users as $user){
            $events = $user['events']->groupBy('id');
            foreach($user['transactionss'] as $transaction){
                //dd(empty($transaction['subscription']));

                    $event = $events[$transaction['event']->first()->id]->first();
                    if(!$event){
                        continue;
                    }

                    $statistic = $user['statisticGroupByEvent']->groupBy('event_id');

                    $tickets = $user['ticket']->groupBy('event_id');
                    $ticketType = isset($tickets[$event->id]) ? $tickets[$event->id]->first()->type : '-';
                    if(isset($tickets[$event->id])){
                        $ticketType = $tickets[$event->id]->first()->type;
                        $ticketName = $tickets[$event->id]->first()->title;

                    }else{
                        $ticketType = '-';
                        $ticketName = '-';
                    }
                    if($transaction['coupon_code'] != ''){
                        $coupon_code = $transaction['coupon_code'];
                    }else{
                        $coupon_code = '-';
                    }

                    $videos = isset($statistic[$event->id]) ?
                        $statistic[$event->id]->first()->pivot : null;

                    //$events = $transaction->user->first()->events->groupBy('id');
                    //$events = $user->events->groupBy('id');
                    $expiration = 'fd';//isset($events[$transaction->event->first()->id]) ? $events[$transaction->event->first()->id]->first()->pivot->expiration : null;
                    $videos = 'fdw';//isset($videos) ? json_decode($videos->videos,true) : null;

                    $isElearning = $event->delivery->first() && $event->delivery->first()->id == 143;


                    $data['transactions'][] = ['id' => $transaction['id'], 'user_id' => $user['id'],'name' => $user['firstname'].' '.$transaction->user[0]['lastname'],
                    'event_id' => $event->id,'event_title' =>$event['title'],'coupon_code' => $coupon_code, 'type' => $ticketType,'ticketName' => $ticketName,
                    'date' => date_format($transaction['created_at'], 'm/d/Y'), 'amount' => $transaction['amount'],
                    'is_elearning' => $isElearning,
                    'coupon_code' => $transaction['coupon_code'],'videos_seen' => '0','expiration'=>$expiration];

            }
        }
        //dd($data['transactions']);
        return $data['transactions'];
    }


    */

    /**
     * Show the form for creating a new user
     *
     * @param  \App\Model\Role  $model
     * @return \Illuminate\View\View
     */
    public function create(Role $model)
    {
        return view('users.create', ['roles' => $model->get(['id', 'name'])]);
    }

    public function assignEventToUserCreate(Request $request)
    {
        //dd($request->all());
       $user_id = $request->user_id;
       $event_id = $request->event_id;
       $ticket_id = $request->ticket_id;

       $user = User::find($user_id);
       //dd($user);
       $user->ticket()->attach($ticket_id, ['event_id' => $event_id]);

       $data['event'] = Event::with('delivery', 'ticket')->find($event_id);
       //dd($data['event']['ticket']);
       foreach($data['event']->ticket as $ticket){
           //dd($ticket);
           if($ticket->pivot->ticket_id == $ticket_id)
           {
               $data['event']['ticket_title'] = $ticket['title'];
                $quantity = $ticket->pivot->quantity - 1;
           }


       }
       $extra_month = $data['event']['expiration'];

        $ticket = Ticket::find($ticket_id);

        $ticket->events()->updateExistingPivot($event_id,['quantity' => $quantity], false);



       if($data['event']['expiration'] != null){
            $exp_date = date('Y-m-d', strtotime("+".$extra_month." months", strtotime('now')));
       }else{
           $exp_date = null;
       }

       $user->events()->attach($event_id, ['paid' => 1, 'expiration' => $exp_date]);

       $payment_method = $request->cardtype;
       $billing = $request->billing;

       $event = Event::find($event_id);
       $price = $event->ticket()->wherePivot('ticket_id',$ticket_id)->first()->pivot->price;




       //fetch user information
       /*$person_details = [];
       $person_details['billing'] = $billing;
       $person_details['billname'] = $user['name'];
       $person_details['billsurname'] = $user['surname'];
       //dd($user);
        if($user['address'] != null){
            $person_details['billaddress'] = $user['address'];
        }
        if($user['address_num'] != null){
            $person_details['billaddressnum'] = $user['address_num'];
        }
        if($user['postcode'] != null){
            $person_details['billpostcode'] = $user['postcode'];
        }
        if($user['city'] != null){
            $person_details['billcity'] = $user['city'];
        }

        $person_details = json_encode($person_details);*/
        
       //Create Transaction

       $billingDetails = [];

       if($request->billing == 1){
        $billingDetails = json_decode($user['receipt_details'],true);
        $billingDetails['billing'] = 1;
       }else{
        $billingDetails = json_decode($user['invoice_details'],true);
        $billingDetails['billing'] = 2;
       }
       
       $transaction = new Transaction;
       $transaction->placement_date = Carbon::now();
       $transaction->ip_address = \Request::ip();
       $transaction->type = $ticket['type'];
       $transaction->billing_details = json_encode($billingDetails);
       $transaction->total_amount = $price;
       $transaction->amount = $price;
       $transaction->status = 1;
       $transaction->trial = 0;

       $cart_data = ["manualtransaction" => [
           "rowId" => "manualtransaction",
           //"id" => 'coupon code ' . $content->id,
           "name" => $data['event']['title'],"qty" => "1",
           "price" => $price,
           //"options" => ["type" => "9","event"=> $content->id],
           "tax" => 0,"subtotal" => $price
           ]];

       $status_history[] = [
        'datetime' => Carbon::now()->toDateTimeString(),
        'status' => 1,
        'user' => [
            'id' => $user->id, //0, $this->current_user->id,
            'email' => $user->email,//$this->current_user->email
            ],
        //'pay_seats_data' => $pay_seats_data,//$data['pay_seats_data'],
        'pay_bill_data' => [],
        'cardtype' => $request->cardtype,
        //'installments' => 1,
        //'deree_user_data' => $deree_user_data, //$data['deree_user_data'],
        'cart_data' => $cart_data //$cart
        ];
        $transaction->status_history = json_encode($status_history);

       $transaction->save();

        //attach transaction with user
       $transaction->user()->attach($user['id']);

       //attach transaction with event
       $transaction->event()->attach($event_id);


       $response_data['ticket']['event'] = $data['event']['title'];
       $response_data['ticket']['ticket_title'] = $ticket['title'];
       $response_data['ticket']['exp'] = $exp_date;
       $response_data['ticket']['event_id'] = $data['event']['id'];
       $response_data['user_id'] = $user['id'];
       $response_data['ticket']['ticket_id'] = $ticket['id'];;
       $response_data['ticket']['type'] = $ticket['type'];;


       $this->sendEmails($transaction,$event,$response_data);

       return response()->json([
            'success' => __('Ticket assigned succesfully.'),
            'data' => $response_data
        ]);
    }

    public function edit_ticket(Request $request)
    {
        $user = Auth::user();

        $event = Event::with('ticket')->find($request->event_id);

        return view('users.courses.edit_ticket', ['events' => $event ,'user' => $user]);
    }

    public function remove_ticket_user(Request $request)
    {

        $user = User::find($request->user_id);
        $event = Event::find($request->event_id);
        //dd($event);
        $user->ticket()->wherePivot('event_id', '=', $request->event_id)->wherePivot('ticket_id', '=', $request->ticket_id)->detach($request->ticket_id);
        $user->events()->wherePivot('event_id', '=', $request->event_id)->detach($request->event_id);
        //dd($user->transactions()->get());
        $found = 0;
        foreach($user->transactions()->get() as $key => $tran){
            if(count($tran->status_history) != 0){

                

                $title = $tran->status_history[0]['cart_data']['manualtransaction']['name'];
                //dd($title);
                if($event['title'] == $title){
                    $found = $tran['id'];
                }
                //dd($tran->status_history['cart_data']['manualtransaction']['name']);
            }

        }
        if($found != 0){
            
            $transaction = Transaction::find($found);
            $transaction->event()->detach($request->event_id);
            $transaction->user()->detach($request->user_id);
            $transaction->delete();
        }
        //$user->ticket()->attach($ticket_id, ['event_id' => $event_id]);
        //dd($user->transactions()->get());
        $user->events()->wherePivot('event_id', '=', $request->event_id)->detach();
        //$user->transactions()->wherePivot('event_id', '=', $request->event_id)->updateExistingPivot($event_id,['paid' => 0], false);
        return response()->json([
            'success' => 'Ticket assigned removed from user',
            'data' => $request->event_id
        ]);
    }

    /**
     * Store a newly created user in storage
     *
     * @param  \App\Http\Requests\UserRequest
     * @param  \App\Model\User  $model
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(UserRequest $request, User $model)
    {
        //dd($request->all());
        $user = $model->create($request->merge([
            'password' => Hash::make($request->get('password')),
        ])->all());

        $user->createMedia();

        $user = User::find($user['id']);
        $user->role()->sync($request->role_id);

        $connow = Carbon::now();
        $clientip = '';
        $clientip = \Request::ip();

        $user->consent = '{"ip": "' . $clientip . '", "date": "'.$connow.'" }';
        $user->save();

        $user->notify(new userActivationLink($user,'activate'));

        return redirect()->route('user.edit', $user->id)->withStatus(__('User successfully created.'));
    }

    /**
     * Show the form for editing the specified user
     *
     * @param  \App\Model\User  $user
     * @param  \App\Model\Role  $model
     * @return \Illuminate\View\View
     */
    public function edit(User $user, Role $model)
    {
        $data['events'] = Event::has('ticket')->whereIn('status',[0,2])->get();

        //dd($data['events']);
        $data['user'] = $user::with('ticket','role','events','image','transactions')->find($user['id']);

        $data['transactions'] = [];

        foreach($user->events as $key => $value){

            $user_id = $value->pivot->user_id;
            $event_id = $value->pivot->event_id;
            $event = Event::find($event_id);
            $ticket = $event->tickets()->wherePivot('event_id', '=', $event_id)->wherePivot('user_id', '=', $user_id)->first();

            $data['user']['events'][$key]['ticket_id'] = isset($ticket->pivot) ? $ticket->pivot->ticket_id : null;
            $data['user']['events'][$key]['ticket_title'] = isset($ticket['title']) ? $ticket['title'] : '';

            if(!key_exists($value['title'],$data['transactions'])){
                $data['transactions'][$value['title']] = [];
            }

            $data['transactions'][$value['title']] = $value->transactionsByUser($user_id)->get();

        }
        $data['subscriptions']=[];
        foreach($user->subscriptionEvents as $key => $value){
            $data['subscriptions'][$value['title']] = $value->subscriptionΤransactionsByUser($user_id)->get();

        }

        if($data['user']['receipt_details'] != null){
            $data['receipt'] = json_decode($data['user']['receipt_details'], true);
        }else{
            $data['receipt'] = null;
        }

        if($data['user']['invoice_details'] != null){
            $data['invoice'] = json_decode($data['user']['invoice_details'], true);
        }else{
            $data['invoice'] = null;
        }
        //dd($data['subscriptions']);
        return view('users.edit', ['subscriptions'=>$data['subscriptions'], 'transactions'=>$data['transactions'],'events' => $data['events'] ,'user' => $data['user'],'receipt' => $data['receipt'],'invoice' => $data['invoice'] ,'roles' => $model->all()]);
    }


    /**
     * Update the specified user in storage
     *
     * @param  \App\Http\Requests\UserRequest  $request
     * @param  \App\Model\User  $user
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(UserRequest $request, User $user)
    {
        $hasPassword = $request->get("password");
        $user->update(
            $request->merge([
                'picture' => $request->photo ? $request->photo->store('profile_user', 'public') : $user->picture,
                'password' => Hash::make($request->get('password'))
            ])->except([$hasPassword ? '' : 'password'])
        );

        return redirect()->route('user.index')->withStatus(__('User successfully updated.'));
    }


    /**
     * Remove the specified user from storage
     *
     * @param  \App\Model\User  $user
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(User $user)
    {
        if ($user->id == 1) {
            return abort(403);
        }

        $user->delete();

        return redirect()->route('user.index')->withStatus(__('User successfully deleted.'));
    }


    public function sendEmails($transaction,$content,$ticket)
    {

        $user = $transaction->user()->first();//Auth::user();

        $muser = [];
        $muser['name'] = $user->firstname . ' ' . $user->lastname;
        $muser['id'] = $user->id;
        $muser['first'] = $user->firstname;
        $muser['email'] = $user->email;

        $tickettypedrop = $ticket['ticket']['type'];
        $tickettypename = $ticket['ticket']['type'];
        $eventname = $content->title;
        $date = $content->summary1->first() ? $content->summary1->first()->title : '';
        $eventcity = '';

        $groupEmailLink = '';
        if ($content && $content->id == 2068) {
            $groupEmailLink = 'https://www.facebook.com/groups/846949352547091';
        }else{
            $groupEmailLink = 'https://www.facebook.com/groups/elearningdigital/';
        }

        $today = date('Y/m/d'); 
        $expiration_date = '';

        if($content->expiration){
            $monthsExp = '+' . $content->expiration .'months';
            $expiration_date = date('Y-m-d', strtotime($monthsExp, strtotime($today)));
        }

        $extrainfo = [$tickettypedrop, $tickettypename, $eventname, $date, '-', '-', $eventcity,$groupEmailLink,$expiration_date];
        
        $helperdetails[$user->email] = ['kcid' => $user->kc_id, 'deid' => $user->partner_id, 'stid' => $user->student_type_id, 'jobtitle' => $user->job_title, 'company' => $user->company, 'mobile' => $user->mobile];

        $adminemail = 'info@knowcrunch.com';

        $data = [];
        $data['user'] = $muser;
        $data['trans'] = $transaction;
        $data['extrainfo'] = $extrainfo;
        $data['helperdetails'] = $helperdetails;
        $data['eventslug'] = $content->slug;

        if($content->view_tpl == 'elearning_event'){
  
            $sent = Mail::send('emails.admin.info_new_registration_elearning', $data, function ($m) use ($adminemail, $muser) {

                $fullname = $muser['name'];
                $first = $muser['first'];
                $sub = 'Knowcrunch - ' . $first . ' your registration has been completed.';
                $m->from($adminemail, 'Knowcrunch');
                $m->to($muser['email'], $fullname);
                $m->subject($sub);
               // $m->attachData($pdf->output(), "invoice.pdf");
            });

        }else{
            
            $sent = Mail::send('emails.admin.info_new_registration', $data, function ($m) use ($adminemail, $muser) {

                $fullname = $muser['name'];
                $first = $muser['first'];
                $sub = 'Knowcrunch - ' . $first . ' your registration has been completed.';
                $m->from($adminemail, 'Knowcrunch');
                $m->to($muser['email'], $fullname);
                $m->subject($sub);
            });
        }

        //send elearning Invoice
        $transdata = [];
        $transdata['trans'] = $transaction;

        $transdata['user'] = $muser;
        $transdata['trans'] = $transaction;
        $transdata['extrainfo'] = $extrainfo;
        $transdata['helperdetails'] = $helperdetails;
        $transdata['coupon'] = $transaction->coupon_code;

        $sentadmin = Mail::send('emails.admin.admin_info_new_registration', $transdata, function ($m) use ($adminemail) {
            $m->from($adminemail, 'Knowcrunch');
            $m->to($adminemail, 'Knowcrunch');
            $m->subject('Knowcrunch - New Registration');
        });

    }


    public function createKC(Request $request){

        $KC = "KC-";
		$time = strtotime(date('Y-m-d'));
		$MM = date("m",$time);
		$YY = date("y",$time);

        
        $user = User::find($request->user);
       
        if(!$user->kc_id){

            $optionKC = Option::where('abbr','website_details')->first();
		    $next = $optionKC->value;

            $next_kc_id = str_pad($next, 4, '0', STR_PAD_LEFT);
            $knowcrunch_id = $KC.$YY.$MM.$next_kc_id;

            $user->kc_id = $knowcrunch_id;

            if ($next == 9999) {
                $next = 1;
            }
            else {
                $next = $next + 1;
            }
    
            $optionKC->value=$next;
            $optionKC->save();

        }
        $user->save();

       

        return back()->withStatus(__('User successfully updated.'));
    }

    public function createDeree(Request $request){

       

        
        $user = User::find($request->user);
        $option = Option::where('abbr','deree_codes')->first();
        $dereelist = json_decode($option->settings, true);

        if(count($dereelist) > 0 && !$user->partner_id){
            $user->partner_id = $dereelist[0];
            unset($dereelist[0]);

            $option->settings = json_encode(array_values($dereelist));
	        $option->save();

        }

       
        $user->save();

       

        return back()->withStatus(__('User successfully updated.'));
    }


}
