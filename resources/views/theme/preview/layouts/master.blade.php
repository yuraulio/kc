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
    {{-- @include('theme.layouts.favicons') --}}
    @include('theme.preview.layouts.header_scripts')
    <script>
        (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
        (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
        m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
        })(window,document,'script','//www.google-analytics.com/analytics.js','ga');
        ga('create', 'UA-XXXXXXXX-XX', 'auto');
        ga('send', 'pageview');
    </script>
    <style>
        .dpParseSection {}
        .cf_multi_dropzone { position: relative; }
        .cf_multi_dropzone .fancybox { display: none; position: relative; text-decoration: none; }
        .cf_multi_dropzone .fancybox:first-child { display: inline-block; }
        .cf_multi_dropzone .fancybox .media_num { position: absolute; bottom: 0; right: 0; width: auto; height: 30px; background-color: #666; opacity: 0.9; padding: 0 10px; }
        .cf_multi_dropzone .fancybox .media_num i { font-size: 20px; line-height: 30px; color: #111; }
        .cf_multi_dropzone .fancybox .media_num span { display: inline-block; font-size: 20px; line-height: 30px; color: #111; padding: 0 0 0 5px; }

        .fancy_social_media_cont { display: none; }
        .fancy_social_media {
            bottom: -5px;
            position: fixed;
            right: 10px;
            z-index: 10000;
         }
        .fancy_social_media ul { list-style: none; }
        .fancy_social_media li {  }
        .fancy_social_media a { text-decoration: none; text-align: center; display: block; margin: 10px; border-radius: 25px; padding: 10px; background-color: #333; color: #fff; }
        .fancy_social_media a:hover { text-decoration: none; display: block; margin: 10px; border-radius: 25px; padding: 10px; background-color: #111; color: #ddd; }
        .fancy_social_media .fs_facebook {}
        .fancy_social_media .fs_google_plus {}
        .fancy_social_media .fs_twitter {}
        .fancy_social_media a i { font-size: 2em; }
    </style>
</head>
<body>
    {{-- @include('theme.layouts.preloader') --}}
    @include('theme.preview.layouts.header')
    <div class="main_content_include">
        @yield('content')
    </div>
    @include('theme.preview.layouts.footer')
    @include('theme.preview.layouts.footer_scripts')
    {{-- @include('theme.layouts.flash_notifications') --}}
</body>
</html>
