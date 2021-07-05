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
Dear {{$firstName}},<br />
<p>
<br/>
We remind you that your subscription to our videos & files expires in {{$expirationDate}}.
It will be renewed automatically one day after {{$expirationDate}}. You can always cancel your subscription plan from your account any time. 
<br />
<br />
Thank you,
<br/>
<span style="color:#006aa9;">KNOWCRUNCH </span> | LEARN. TRANSFORM. THRIVE <br />
We respect your data. Read our <a href="https://knowcrunch.com/data-privacy-policy">data privacy policy</a>

<p>&nbsp;</p>

<br />


@endsection
