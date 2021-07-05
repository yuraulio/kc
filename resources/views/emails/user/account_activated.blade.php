<?php
    /**
     * Inform the user about his current login status
     * @user : the user (object)
     */
?>
@extends('emails.email_master_tpl')
@section('content')

<p>Ο λογαριασμός @include('emails.partials.webpage_link')</p>
<p>για <strong>{{ $user->email }}</strong>  έχει ενεργοποιηθεί</p>
@include('emails.partials.the_team')

@endsection
