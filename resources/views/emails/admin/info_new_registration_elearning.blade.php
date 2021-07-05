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

<?php 

    $monthAccess = '';
    if(isset($eventAccess[0]) && $eventAccess[1]){
        $monthAccess = '+'.$eventAccess[0]. ' '.$eventAccess[1];
    }

    $expirationDate = '';
    $eventAccess = explode(' ',trim($extrainfo[3]));
    
    $currentDay = date('Y-m-d');
    $expirationDate = date('d-m-Y', strtotime($monthAccess, strtotime($currentDay)));
   

?>

SUBSCRIPTION<br/>
 Ticket Type: <strong>{{ $extrainfo[1] }}</strong><br />
 Ticket Price: @if($trans->total_amount == 0) Free @else {{ $trans->total_amount }}</strong>@endif<br />
 Expiration date: <strong>{{ $expirationDate }}</strong><br />

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
