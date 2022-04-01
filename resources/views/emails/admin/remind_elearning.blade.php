<?php

?>

@extends('emails.email_master_tpl')
@section('content')

<p>
Dear {{$firstName}},<br />
<p>
<br/>

We remind you that your access to the {{$eventTitle}} expires on {{$expirationDate}}. 
<br />
Continue your training and gain full access to our updated videos, files and your personal notes by purchasing an annual subscription in your account.
<br />
<br />

Thank you,

<br />
<br />
<span style="color:#006aa9;">KNOWCRUNCH </span> | LEARN. TRANSFORM. THRIVE <br />
We respect your data. Read our <a href="https://knowcrunch.com/data-privacy-policy">data privacy policy</a>



<br />

@endsection
