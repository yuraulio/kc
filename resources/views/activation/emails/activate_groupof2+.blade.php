@extends('emails.email_master_tpl')

@section('content')
Hi,
<br /><br />
There was a request to activate your account. <strong> Your temporary password is the Student Id from registration mail you received. You should change your password!!!</strong>
<br />
If you did not make this request, just ignore this email. Otherwise, please click the button bellow to activate your account.
<br />
<br />
<a href="{{ URL::to("myaccount/activate/{$code}") }}">Activate Account </a><br /><br />
<br />

@include('emails.partials.the_team')

@endsection