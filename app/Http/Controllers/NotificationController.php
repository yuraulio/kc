<?php

namespace App\Http\Controllers;

use Notification;
use Illuminate\Http\Request;
use App\Notifications\userStatusChange;
use App\Notifications\userActivationLink;
use App\Model\Activation;
use App\Model\User;

class NotificationController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth.aboveauthor');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $data = [];
        $data['notifications'] = Notification::all();
        //dd($data);
        return view('notification.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Model\Notification  $notification
     * @return \Illuminate\Http\Response
     */
    public function show(Notification $notification)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Model\Notification  $notification
     * @return \Illuminate\Http\Response
     */
    public function edit(Notification $notification)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Model\Notification  $notification
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Notification $notification)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Model\Notification  $notification
     * @return \Illuminate\Http\Response
     */
    public function destroy(Notification $notification)
    {
        //
    }

    public function userStatusChange($user) {
        //$a = Notification::send($user, new userStatusChange($user));

        $user->notify(new userStatusChange($user));

        return true;

    }

    public function userChangePassword($user_id = 0)
    {
        //$user = User::where('id', $user_id)->with('activation')->first();
        $user = User::find($user_id);
       
        if ($user) {
            $user->notify(new userChangePassword($user));
            return 1;

          
        } else {
            return 0;
        }
    }

    public function userActivationLink($user_id = 0)
    {
        $user = User::find($user_id);
        //echo $user['email'];
        //User::where('id', $user_id)->with('activation')->first();
        if ($user) {
            $user->notify(new userActivationLink($user,'re-activate'));

            return true;
            
        } else {
            return false;
        }
    }
}
