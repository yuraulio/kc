<?php
    /**
     * Inform admin about a new student registration
     * @user : the new registration user (object)
     * @account : the account (array), has the following linked arrays
     * @account['default_store']
     * @account['users']
     */

    //dd($trans);  txId:  $res['txId']

//$extrainfo = [$tickettypedrop, $tickettype, $eventname, $eventdate];
    //$helperdetails[$user->email] = ['kcid' => $user->kc_id, 'deid' => $user->partner_id];
?>
@extends('emails.email_master_tpl')
@section('content')


<p>

Ο χρήστης {{$name}} δεν έχει πληρώσει τη δόση γι αυτον το μήνα για το μάθημα {{$eventTitle}}.

</p>


{{--include('emails.partials.the_team')--}}

@endsection
