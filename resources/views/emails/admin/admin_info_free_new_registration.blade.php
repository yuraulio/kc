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


?>
<p>
<strong>EVENT NAME:</strong> {{ $extrainfo[2] }}<br /><br />
<strong>EVENT DATE:</strong> {{ $extrainfo[3] }}<br /><br />
<strong>STUDENT ID:</strong> {{ $user['kc_id'] }}<br /><br />
<strong>FULL NAME:</strong> {{ $user['first_name'] }} {{ $user['last_name'] }}<br /><br />
<strong>EMAIL:</strong> {{ $user['email'] }}<br /><br />
<strong>MOBILE PHONE:</strong> {{ $user['mobile'] }}<br /><br />
<strong>JOB TITLE:</strong> {{ $user['job_title'] }}<br /><br />
<strong>COMPANY NAME:</strong> {{ $user['company'] }}<br /><br />

<strong>TICKET TYPE:</strong> {{ $tickettype }} <br /><br />

<a target="_blank" href="http://www.knowcrunch.com/admin1/transaction/edit/{{ $trans->id }}">Check the registration details online</a>.<br /><br />
</p>

@include('emails.partials.the_team')

@endsection
