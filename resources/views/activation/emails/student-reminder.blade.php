@extends('emails.email_master_tpl')

@section('content')

Hi,
<br /><br />
There was a request to change your password.
<br />
If you did not make this request, just ignore this email. Otherwise, please click the button bellow to change your password.
<br /><br />
<a href="{{ URL::to("myaccount/reset/{$user->getUserId()}/{$code}") }}">Change Password</a><br /><br />

@include('emails.partials.the_team')

@endsection
