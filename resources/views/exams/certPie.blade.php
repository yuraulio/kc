<!doctype html>

<html>

<head>

<meta charset="utf-8">

<meta name="description" content="">

<meta name="Saquib" content="Blade">

<title>Checkout our layout</title>
<meta property="og:title" content="The Rock" />
<meta property="og:type" content="video.movie" />
<meta property="og:url" content="https://www.imdb.com/title/tt0117500/" />
<meta property="og:image" content="https://ia.media-imdb.com/images/rock.jpg" />

<!-- load bootstrap from a cdn -->

<link rel="stylesheet" href="//netdna.bootstrapcdn.com/twitter-bootstrap/3.0.3/css/bootstrap-combined.min.css">

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

   </div>

   <footer class="row">

   <div id="copyright text-right">© Copyright 2017 Saquib Rizwan </div>

   </footer>

</div>

</body>

</html>
