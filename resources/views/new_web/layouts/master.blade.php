<!doctype html>

<html lang="en-US">

    <head>
        <meta charset="UTF-8">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        @yield('meta')
        @yield('css')

        <link href="{{ cdn(mix('theme/assets/css/style_ver.css')) }}" rel="stylesheet" media="all" />
    </head>

    <body>
        <div class="page-wrapper">
            @include('new_web.layouts.header')
            <div class="main_content_include">
                @yield('content')
            </div>
            {{-- @include('footer') --}}
            <a href="#" class="go-top-btn"><i class="icon-up-open"></i></a>
        </div>
        @yield('scripts')
    </body>

</html>