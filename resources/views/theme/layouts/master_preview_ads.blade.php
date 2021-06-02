<!doctype html>
<html lang="en" class="no-js">
<head>
    <base href="{!! URL::to('/') !!}/" target="_self" />
    <!-- meta data -->
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name=viewport content="width=device-width, initial-scale=1">
    <!-- title and favicons -->
    @if (isset($meta) && isset($meta['header_code']))
    {!! $meta['header_code'] !!}
    @endif
    <!--<link rel="icon" href="theme/assets/img/icon/fav_icon.gif">-->
    <!-- necessary stylesheets -->
    @include('theme.layouts.favicons')
    @include('theme.layouts.header_scripts')
    <script>
        (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
        (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
        m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
        })(window,document,'script','//www.google-analytics.com/analytics.js','ga');
        ga('create', 'UA-35250446-6', 'auto');
        ga('send', 'pageview');
    </script>
    <script type='text/javascript'>
      var googletag = googletag || {};
      googletag.cmd = googletag.cmd || [];
      (function() {
        var gads = document.createElement('script');
        gads.async = true;
        gads.type = 'text/javascript';
        var useSSL = 'https:' == document.location.protocol;
        gads.src = (useSSL ? 'https:' : 'http:') +
          '//www.googletagservices.com/tag/js/gpt.js';
        var node = document.getElementsByTagName('script')[0];
        node.parentNode.insertBefore(gads, node);
      })();
    </script>
    <script type='text/javascript'>
        @include('theme.layouts.google_tags')
    </script>
</head>
<body>
    {{-- @include('theme.layouts.preloader') --}}
    <div class="main_content_include">
        @yield('content')
    </div>
</body>
</html>
