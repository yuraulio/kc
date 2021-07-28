@extends('emails.email_master_tpl')

@section('content')

Activate your account by clicking <a href="{{ URL::to("myaccount/activate/{$user->id}/{$code}") }}">here</a><br /><br />


@include('emails.partials.the_team')

@endsection
