<!doctype html>

<html>

<head>

<meta charset="utf-8">

<meta name="description" content="">

<meta name="Saquib" content="Blade">

<title>Checkout our layout</title>

<!-- load bootstrap from a cdn -->

<link rel="stylesheet" href="//netdna.bootstrapcdn.com/twitter-bootstrap/3.0.3/css/bootstrap-combined.min.css">

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

   <div id="main" class="row">

           @yield('content')

           <img src="{{ $img }}" alt="">

   </div>

   <footer class="row">



   </footer>

</div>

</body>

</html>
