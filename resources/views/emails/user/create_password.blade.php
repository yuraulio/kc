@extends('emails.email_master_tpl')
@section('content')

<p> Παρακαλώ δημιουργείστε τον κωδικο σας ακολουθώντας το παρακάτω link </p>
<a href='{{ URL::to("create-your-password/$slug") }}'>Create your password</a>
@include('emails.partials.the_team')

@endsection