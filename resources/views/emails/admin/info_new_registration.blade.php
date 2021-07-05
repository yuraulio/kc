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

<?php $res = $trans->payment_response;
    if ($res) {
        $res = json_decode($res, true);
    }
?>

<h1>Thank you {{ $user['name'] }}</h1>
<p>
Your registration has been completed.<br /><br />
You are one of the privileged attendants of <strong>{{ $extrainfo[2] }}</strong> which takes place: <strong>{{ $extrainfo[3] }}</strong>.<br /><br /> Get ready for a wonderful experience.<br />

<p>
Registration information: <br /><br />



 Student: <strong>{{ $user['name'] }}</strong><br />
 @if(isset($helperdetails[$user['email']]) && $helperdetails[$user['email']]['deid'] != '')
 Student ID: <strong>{{ $helperdetails[$user['email']]['deid'] }}</strong><br />
 @endif
 Ticket Type: <strong>{{ $extrainfo[1] }}</strong><br />
 Ticket Price: @if($trans->total_amount == 0) <strong> Free </strong> @else <strong>{{ $trans->total_amount }}</strong>@endif<br />
 @if(isset($res['paymentRef']) && $res['paymentRef'] != '')
 Bank Payment Reference: <strong>{{ $res['paymentRef'] }}</strong><br />
 @endif
</p>

<p>
Meanwhile, join a great Facebook community for Greek Digital &amp; Social Media Professionals: <a href="https://www.facebook.com/groups/socialmediagreece/" target="_blank">the Digital &amp; Social Media Nation.</a><br />
</p>

<p>
See you in our course!
</p>

<br />

@include('emails.partials.the_team')

@endsection
