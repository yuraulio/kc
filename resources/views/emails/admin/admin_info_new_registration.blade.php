<?php
    /**
     * Inform admin about a new company registration
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
			$type = 'REGULAR';
			break;
	}

//check ticket type, if dropdown special seat show speciality otherwise show ticket name
if ($extrainfo[4] == 1) {

    $tickettype = $type;
}
else {

    $tickettype = $extrainfo[1];
}


if(isset($helperdetails[$user['email']])) {

    $did = $helperdetails[$user['email']]['deid'];
    $mob = $helperdetails[$user['email']]['mobile'];
    $com = $helperdetails[$user['email']]['company'];
    $job = $helperdetails[$user['email']]['jobtitle'];
    //$stid = $helperdetails[$user['email']]['stid']; (user given ticket type id)
    $dereeid = $did; //substr($did, 0, -2);
}
else {

    $did = '-';
    //$stid = '-';
    $dereeid = '-';
    $mob = '-';
    $com = '-';
    $job = '-';
}



?>

<?php if(isset($trans->status_history[0]['pay_seats_data']['student_type_id'])){

	$stId = $trans->status_history[0]['pay_seats_data']['student_type_id'][0];
	
}else{
	
	$stId = '-';
}
?>

<p>
<strong>EVENT NAME:</strong> {{ $extrainfo[2] }}<br /><br />
<strong>EVENT DATE:</strong> {{ $extrainfo[3] }}<br /><br />
<strong>{{$type}} ID:</strong> {{$stId}}<br /><br />
<strong>FULL NAME:</strong> {{ $user['name'] }}<br /><br />
<strong>EMAIL:</strong> {{ $user['email'] }}<br /><br />
<strong>MOBILE PHONE:</strong> {{ $mob }}<br /><br />
<strong>JOB TITLE:</strong> {{ $job }}<br /><br />
<strong>COMPANY NAME:</strong> {{ $com }}<br /><br />

<strong>TICKET TYPE:</strong> {{ $tickettype }} <br /><br />
<strong>TICKET PRICE:</strong> @if($trans->total_amount == 0) Free @else {{ $trans->total_amount }} @endif<br /><br />

<strong>INVOICE:</strong> {{ $extrainfo[5] }}<br /><br />
<strong>CITY:</strong> {{ $extrainfo[6] }}<br /><br />

@if(isset($trans->coupon_code) && $trans->coupon_code != '') <strong>COUPON CODE:</strong> {{ $trans->coupon_code }}<br /><br />@endif
@if(isset($user['id']))<a target="_blank" href="http://www.knowcrunch.com/admin/user/{{$user['id']}}/edit">Check the registration details online</a>.<br /><br />@endif
<!--<a target="_blank" href="https://kclioncode.j.scaleforce.net/admin/transaction/edit/{{ $trans->id }}">Check the registration details online</a>.<br /><br />-->

</p>

@include('emails.partials.the_team')

@endsection
