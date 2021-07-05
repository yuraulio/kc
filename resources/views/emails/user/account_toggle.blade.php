<?php
    /**
     * Inform admin about a new offer submission
     * @account : the account (array), has the following linked arrays
     * @account['default_store']
     * @account['users']
     */
?>
@extends('emails.email_master_tpl')
@section('content')

<p>
Προς: {!! $account['default_store']['title'] !!}
</p>
<p>
Ο λογαριασμός σας έχει ενεργοποιηθεί επιτυχώς. Παρακαλώ πατήστε τον παρακάτω σύνδεσμο για να εισέλθετε στον <a href="{{ URL::to('/login') }}" target="_blank">εταιρικό σας λογαριασμό </a>για να δημοσιεύσετε τις προσφορές σας.
Χρησιμοποιείστε στα στοιχεία εισόδου το email καθώς και το password που ορίσατε κατά την εγγραφή σας. Αν για οποιοδήποτε λόγο δεν μπορείτε να εισέλθετε παρακαλώ επικοινωνήστε μαζί μας στο 2310 301111.
</p>
<p>
<a href="http://offers.prosfores-fylladia.gr/login" target="_blank">Είσοδος στον εταιρικό λογαριασμό, κλικ εδώ</a>
</p>
@include('emails.partials.the_team')

@endsection