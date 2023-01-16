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
<title>Meta Tags — Preview, Edit and Generate</title>
<meta name="title" content="Meta Tags — Preview, Edit and Generate">
<meta name="description" content="With Meta Tags you can edit and experiment with your content then preview how your webpage will look on Google, Facebook, Twitter and more!">

<!-- Open Graph / Facebook -->
<meta property="og:type" content="website">
<meta property="og:url" content="https://metatags.io/">
<meta property="og:title" content="Meta Tags — Preview, Edit and Generate">
<meta property="og:description" content="With Meta Tags you can edit and experiment with your content then preview how your webpage will look on Google, Facebook, Twitter and more!">
<meta property="og:image" content="{{url('/theme/assets/images/homepage/home-hero-img.jpg')}}">

<!-- Twitter -->
<meta property="twitter:card" content="summary_large_image">
<meta property="twitter:url" content="https://metatags.io/">
<meta property="twitter:title" content="Meta Tags — Preview, Edit and Generate">
<meta property="twitter:description" content="With Meta Tags you can edit and experiment with your content then preview how your webpage will look on Google, Facebook, Twitter and more!">
<meta property="twitter:image" content="{{url('/theme/assets/images/homepage/home-hero-img.jpg')}}">


</head>

<body>

<div class="container">

   <header class="row">

   <div class="navbar">

<div class="navbar-inner">

    <a id="logo" href="/">Single Malt</a>

    <ul class="nav">

        <li><a href="/">Home</a></li>

        <li><a href="/contact">Contact</a></li>

    </ul>

</div>

</div>

   </header>

   <div id="main" class="row">

           @yield('content')
           <p>TEST</p>
           <img src="" alt="">

   </div>

   <footer class="row">

   <div id="copyright text-right">© Copyright 2017 Saquib Rizwan </div>

   </footer>

</div>

</body>

</html>
