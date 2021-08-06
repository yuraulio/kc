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
use App\Model\Transaction;
use \Cart as Cart;
use Carbon\Carbon;
use App\Model\Media;
use App\Model\Certificate;
use Session;
use App\Http\Requests\UserRequest;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

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
        $this->authorize('manage-users', User::class);
        $user = Auth::user();

        $data['all_users'] = $model::count();
        $data['total_graduates'] = total_graduate();

        //dd($model->with('role', 'image')->get()[0]);

        //dd($model->with('role', 'image')->get()->toArray()[0]['image']);

        return view('users.index', ['users' => $model->with('role', 'image','statusAccount')->get()->toArray(), 'data' => $data]);
    }

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
       $person_details = [];
       $person_details['billing'] = $billing;
       $person_details['billname'] = $user['name'];
       $person_details['billsurname'] = $user['surname'];

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

        $person_details = json_encode($person_details);


       //Create Transaction
       $transaction = new Transaction;
       $transaction->placement_date = Carbon::now();
       $transaction->ip_address = \Request::ip();
       $transaction->type = $ticket['type'];
       $transaction->billing_details = $person_details;
       $transaction->total_amount = $price;
       $transaction->amount = $price;
       $transaction->trial = 0;

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
        $user->ticket()->wherePivot('event_id', '=', $request->event_id)->wherePivot('ticket_id', '=', $request->ticket_id)->detach($request->ticket_id);
        $user->events()->wherePivot('event_id', '=', $request->event_id)->detach($request->event_id);
        //$user->ticket()->attach($ticket_id, ['event_id' => $event_id]);
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
        //dd($user);
        $data['events'] = Event::has('ticket')->get();

        //dd($data['events']);
        $data['user'] = $user::with('ticket','role','events','image')->find($user['id']);
        //dd($data['user']);
        foreach($user->events as $key => $value){
            $user_id = $value->pivot->user_id;
            $event_id = $value->pivot->event_id;
            $event = Event::find($event_id);
            $ticket = $event->tickets()->wherePivot('event_id', '=', $event_id)->wherePivot('user_id', '=', $user_id)->first();

            $data['user']['events'][$key]['ticket_id'] = isset($ticket->pivot) ? $ticket->pivot->ticket_id : null;
            $data['user']['events'][$key]['ticket_title'] = isset($ticket['title']) ? $ticket['title'] : '';
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

        return view('users.edit', ['events' => $data['events'] ,'user' => $data['user'],'receipt' => $data['receipt'],'invoice' => $data['invoice'] ,'roles' => $model->all()]);
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

}
