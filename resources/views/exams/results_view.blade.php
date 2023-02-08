<!DOCTYPE html>

<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>

    <meta charset="utf-8">

    <meta name="viewport" content="width=device-width, initial-scale=1">

<meta name="description" content="">

<?php
$title1 = str_replace('_',' ',$title);
?>

<title>{{ $title1 }} Result</title>



<!-- load bootstrap from a cdn -->

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha512-k78e1fbYs09TQTqG79SpJdV4yXq8dX6ocfP0bzQHReQSbEghnS6AQHE2BbZKns962YaqgQL16l7PkiiAHZYvXQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />

<!-- Primary Meta Tags -->
<title>Result</title>
<meta name="title" content="Result">
<meta name="description" content="{{ $title1 }} Result">
<meta property="fb:app_id" content="961275423898153">

<!-- Open Graph / Facebook -->
<meta property="og:type" content="article" />
<meta property="og:url" content="{{ Request::url() }}">
<meta property="og:title" content="Result">
<meta property="og:description" content="{{ $title1 }} Result">
<meta property="og:image" content="{{ $og_image }}">

<!-- Twitter -->
<meta property="twitter:card" content="summary_large_image">
<meta property="twitter:url" content="{{ Request::url() }}">
<meta property="twitter:title" content="Result">
<meta property="twitter:description" content="{{ $title1 }} Result">
<meta property="twitter:image" content="{{ $og_image }}">


</head>

<body>

<div class="container">

   <header class="row"></header>

    <div id="main" class="container">
        <div style="height:100vh" class="row">

            <div style="margin: auto;" class="col-10">
                <div class="card card-block">
                    <img class="text-center" style="height:70vh;" src="{{ $img }}" alt="result-image">
                </div>
            </div>


        </div>
    </div>

   <footer class="row"></footer>

</div>

</body>

</html>
