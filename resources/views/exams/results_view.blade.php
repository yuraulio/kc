<!DOCTYPE html>

<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>

    <meta charset="utf-8">

    <meta name="viewport" content="width=device-width, initial-scale=1">

<meta name="description" content="">

<title>Checkout our layout</title>

<!-- load bootstrap from a cdn -->

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha512-k78e1fbYs09TQTqG79SpJdV4yXq8dX6ocfP0bzQHReQSbEghnS6AQHE2BbZKns962YaqgQL16l7PkiiAHZYvXQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />

<!-- Primary Meta Tags -->
<title>View Certification</title>
<meta name="title" content="View Certification">
<meta name="description" content="Exam Result">

<!-- Open Graph / Facebook -->
<meta property="og:type" content="website">
<meta property="og:url" content="{{ env('MIX_APP_URL')}}">
<meta property="og:title" content="View Certification">
<meta property="og:description" content="Exam Result">
<meta property="og:image" content="{{ $img }}">

<!-- Twitter -->
<meta property="twitter:card" content="summary_large_image">
<meta property="twitter:url" content="{{ env('MIX_APP_URL')}}">
<meta property="twitter:title" content="View Certification">
<meta property="twitter:description" content="Exam Result">
<meta property="twitter:image" content="{{ $img }}">


</head>

<body>

<div class="container">

   <header class="row">



   </header>



   <div id="main" class="container">
   <div class="row">
    <div class="col-md-8 offset-md-2 col-sm-8 offfset-sm-2">
    <img style="height:80vh;" src="{{ $img }}" alt="">
    </div>
  </div>
        

   </div>

   <footer class="row">



   </footer>

</div>

</body>

</html>
