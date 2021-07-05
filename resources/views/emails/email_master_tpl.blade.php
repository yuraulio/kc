<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="robots" content="noindex">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
        <meta name="description" content="Knowcrunch - Darkpony CMS">
        <meta name="author" content="Darkpony">
        @yield('head')
        @include('emails.partials.base_style')
    </head>
    <body>
        <header>
            @yield('header')
            @include('emails.partials.email_header')
        </header>
        <section class="emailArea">
            @yield('content')
        </section>
        <footer>
            @yield('footer')
            @include('emails.partials.email_footer')
        </footer>
    </body>
</html>
