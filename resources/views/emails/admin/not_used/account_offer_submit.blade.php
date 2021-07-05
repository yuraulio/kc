<?php
    /**
     * Inform admin about a new offer submission
     * @data : the submitted data (array), posted_on, offer_urls
     * @account : the account (array), has the following linked arrays
     * @account['default_store']
     * @account['users']
     */
?>
@extends('emails.email_master_tpl')
@section('content')

<p>Αίτημα για προσθήκη προσφορών @include('emails.partials.webpage_link')</p>
<p>
	Για το κατάστημα : <strong>{{ $account['default_store']['title'] }}</strong> υποβλήθηκε από <br>
	{{ $account['users'][0]['email'] }} στις {{ $data['posted_on'] }}
</p>

<ol>
@foreach ($data['offer_urls'] as $key => $row)
<li>
{!! $row !!}
</li>
@endforeach
</ol>

@endsection