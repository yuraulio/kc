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
ENROLLMENT SUCCESSFUL<br />
You are one of the privileged students of  <strong>{{ $extrainfo[2] }}</strong> and you have <strong>{{ $extrainfo[3] }}</strong>.
<p>
<br/>
<br />
<br />
<br />
PARTICIPANT <br />
Participant: <strong>{{ $user['name'] }}</strong><br />
Your login: <strong>{{ $user['email'] }} </strong> <br />
@if(isset($helperdetails[$user['email']]) && $helperdetails[$user['email']]['kcid'] != '')
 Student ID: <strong>{{ $helperdetails[$user['email']]['kcid'] }}</strong><br />
 @endif
{{--Your password: <strong> (password) </strong> <br />--}}
<br />

SUBSCRIPTION<br/>
 Ticket Type: <strong>{{ $extrainfo[1] }}</strong><br />
 Ticket Price: @if($trans->total_amount == 0) Free @else {{ round($trans->total_amount,2) }}</strong>@endif<br />
 @if($extrainfo[8]) Expiration date: <strong>{{date('d-m-Y', strtotime($extrainfo[8])) }}</strong><br />@endif


</p>
<br/>
<p>
USEFULL LINKS <br/>
Start watching: <a href="https://www.knowcrunch.com/{{$eventslug}}" target="_blank">{{ $extrainfo[2] }}</a><br />
E-Learning support group: <a href="{{ $extrainfo[7] }}" target="_blank">{{ $extrainfo[2] }}-Facebook</a><br />
</p>

<br />

@include('emails.partials.the_team')

@endsection
