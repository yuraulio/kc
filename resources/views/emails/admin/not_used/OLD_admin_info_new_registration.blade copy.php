<?php
    /**
     * Inform admin about a new company registration.
     * @user : the new registration user (object)
     * @account : the account (array), has the following linked arrays
     * @account['default_store']
     * @account['users']
     */
    ?>
@extends('emails.email_master_tpl')
@section('content')
<?php $res = $trans->payment_response;
    if ($res) {
        $res = json_decode($res, true);
    }
    ?>

<h1>
New registration has been received
</h1>
<?php
       switch ($extrainfo[0]) {
           case 1:
               $type = 'UNEMPLOYED';
               break;
           case 2:
               $type = 'STUDENT';
               break;
           case 3:
               $type = 'KNOWCRUNCH ALUMNI';
               break;
           case 4:
               $type = 'DEREE ALUMNI';
               break;
           case 5:
               $type = 'GROUP OF 2+';
               break;

           default:
               $type = 'NORMAL';
               break;
       }

if (isset($helperdetails[$user['email']])) {
    $did = $helperdetails[$user['email']]['deid'];
    $stid = $helperdetails[$user['email']]['stid'];
    $dereeid = $did; //substr($did, 0, -2);
} else {
    $did = '-';
    $stid = '-';
    $dereeid = '-';
}
    ?>
<p>
<strong>EVENT NAME:</strong> {{ $extrainfo[2] }}<br /><br />
<strong>EVENT DATE:</strong> {{ $extrainfo[3] }}<br /><br />
<strong>STUDENT ID:</strong> {{ $dereeid }}<br /><br />
<strong>FULL NAME:</strong> {{ $user['name'] }}<br /><br />
<!-- <strong>JOB TITLE:</strong> -<br /><br />
<strong>COMPANY NAME:</strong> -<br /><br /> -->
<strong>TICKET TYPE:</strong> {{ $type }} ({{ $stid }})<br /><br />
<strong>TICKET AMOUNT:</strong> {{ $trans->total_amount }}<br /><br />
<strong>EMAIL:</strong> {{ $user['email'] }}<br /><br />
<!-- <strong>MOBILE PHONE:</strong> -<br /><br /> -->
<strong>INVOICE:</strong> -<br /><br />

<a target="_blank" href="http://www.knowcrunch.com/admin/transaction/edit/{{ $trans->id }}">Check the registration details online</a>.<br /><br />

 Bank Payment Ref: {{ $res['paymentRef'] }}<br />
 Transaction Id: {{ $res['txId'] }}<br />
</p>

@include('emails.partials.the_team')

@endsection
