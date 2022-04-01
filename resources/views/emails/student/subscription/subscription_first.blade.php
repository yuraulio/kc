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

<p>Thank you very much for your subscription activation!</p>

<br/>

<p>You are one of our privileged alumni that has access to our updated videos and files for a whole year! </p>
<p> Get ready for a wonderful experience.  </p>

<br/>

<br/>
<br/>

<p>Some information for you:</p>
<p>Subscription type: {{$sub_type}}</p>
<p>Subscription price: {{$sub_price}}</p>
<p>Subscription billing period: {{$sub_period}}</p>

<br/>
<p>Meanwhile, do not forget to join our alumni community for more updates: <a href="https://www.facebook.com/groups/KnowcrunchAlumni">Knowcrunch Official Alumni</a></p>
<br />
<p> Enjoy! </p>
<br/>
<span style="color:#006aa9;">KNOWCRUNCH </span> | LEARN. TRANSFORM. THRIVE <br />
We respect your data. Read our <a href="https://knowcrunch.com/data-privacy-policy">data privacy policy</a>



<br />



{{--include('emails.partials.the_team')--}}

@endsection
