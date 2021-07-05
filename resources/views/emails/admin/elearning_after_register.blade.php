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
Welcome to the most complete & award-winning e-learning course in Greece! Some useful information for the course:
<br />

<p>&nbsp;</p>

<ul>
	<li>
	<p><b>DURATION</b>: 6 months starting from your registration</p>
	</li>
	<li>
	<p><b>VIDEOS</b>: you can find them on our website within your account</p>
	</li>
	<li>
	<p><b>SUPPORT</b>: <a href="https://www.facebook.com/groups/elearningdigital/">https://www.facebook.com/groups/elearningdigital/</a></p>
	</li>
	<li>
	<p><b>EXAMS</b>: they are activated automatically when you complete watching at least 80% of the videos</p>
	</li>
	<li>
	<p><b>CERTIFICATION</b>: your diploma will appear automatically &amp; dynamically upon successful completion of your exams</p>
	</li>
</ul>

<br />
Attached you may find a PDF file which gives you clear instructions about your login to our website and your account. 

It is our honor that you have chosen us & we promise you a wonderful journey full of inspiration and creativity!
 
Welcome to KnowCrunch 
<br />
<span style="color:#006aa9;">KNOWCRUNCH </span> | LEARN. TRANSFORM. THRIVE <br />
We respect your data. Read our <a href="https://knowcrunch.com/data-privacy-policy">data privacy policy</a>

<p>&nbsp;</p>

<br />



{{--include('emails.partials.the_team')--}}

@endsection
