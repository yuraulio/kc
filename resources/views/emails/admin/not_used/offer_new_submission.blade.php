<?php
    /**
     * Inform admin about a new offer submission
     * @offer : the offer (array), has the following linked arrays
     * @offer['account']
     * @offer['account']['default_store']
     * @offer['creator'] : 'id','first_name','last_name','email'
     */
?>
@extends('emails.email_master_tpl')
@section('content')

<p>
	Προς: {{ $offer['account']['default_store']['title'] }}
</p>
<p>
Νέα προσφορά με αριθμό <strong>{{ $offer['id'] }}</strong> έχει προστεθεί στο κατάστημα <strong>{{ $offer['account']['default_store']['title'] }}</strong><br />
στις {{ $offer['created_at'] }} από {{ $offer['creator']['email'] }}<br />
@if ($offer['title'])
με ενδεικτικό τίτλο : {{ $offer['title'] }}
@endif
</p>

@endsection
