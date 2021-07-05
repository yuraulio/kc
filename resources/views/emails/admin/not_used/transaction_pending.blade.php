<?php
    /**
     * Inform the admin about a transaction failure
     * @transaction : the transaction (array), has the following linked arrays
     * @transaction['account']
     * @transaction['account']['default_store']
     * @transaction['paymethod']
     * @transaction['user']
     */
?>
@extends('emails.email_master_tpl')
@section('content')

<p>
    Προς: {{ $transaction['account']['default_store']['title'] }}
</p>
<p>
    Η συναλλαγή σας είναι σε αναμονή έγκρισης.
</p>
<p>
    Η συναλλαγή με κωδικό <strong>{{ $transaction['id'] }}</strong> @include('emails.partials.webpage_link')<br>
    για το κατάστημα {{ $transaction['account']['default_store']['title'] }}<br />
    που τοποθετήθηκε στις {{ $transaction['placement_date'] }} σαν {{ $transaction['paymethod']['method_name'] }}<br />
    με ενδεικτικό ποσό : {{ $transaction['total_amount'] }} ευρώ<br />
    αναμένει περαιτέρω επεξεργασία από τους διαχειριστές του συστήματος
</p>

@endsection
