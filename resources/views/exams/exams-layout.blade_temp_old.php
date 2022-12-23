

<!DOCTYPE html>

<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>

    <meta charset="utf-8">

    <meta name="viewport" content="width=device-width, initial-scale=1">



    <!-- CSRF Token -->

    <meta name="csrf-token" content="{{ csrf_token() }}">
    @include('theme.layouts.favicons')
    @include('theme.layouts.header_scripts')


    <!-- Scripts -->

    <script src="{{ asset('js/app.js') }}" defer></script>
    <script src="{{ asset('js/ib_script1.js') }}" defer></script>
    <!-- Custom Style -->

    <link rel="stylesheet" href="{{ asset('css/ib_style.css') }}">

    <!-- Font Awesome -->

    <link rel="stylesheet" href="{{ asset('bower_components/font-awesome/css/font-awesome.min.css') }}">



    <!-- Fonts -->

    <link rel="dns-prefetch" href="https://fonts.gstatic.com">

    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet" type="text/css">



    <!-- Styles -->

    <link href="{{ asset('css/app.css') }}" rel="stylesheet">

    <!-- jQuery 3 -->

    <script src="{{ asset('bower_components/jquery/dist/jquery.min.js') }}"></script>

 @yield('head_scripts')


</head>
@include('theme.layouts.header_exam')

<!-- <body style="background: transparent;" class="hold-transition exam-page disable-select" oncontextmenu="return false;" onkeydown="return (event.keyCode != 116)"> -->
<body style="background: transparent;" class="hold-transition exam-page disable-select" >
    <div id="app">

        {{--<nav class="navbar navbar-expand-md navbar-light navbar-laravel">


                <h1  id="custom" class="navbar-brand">

                    {{ $event_title }}

                </h1>

                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">

                    <span class="navbar-toggler-icon"></span>

                </button>



                <div class="collapse navbar-collapse" id="navbarSupportedContent">

                    <!-- Left Side Of Navbar -->

                    <ul class="navbar-nav mr-auto">



                    </ul>



                    <!-- Right Side Of Navbar -->

                    <ul class="navbar-nav ml-auto">

                        <li class="nav-item">

                            <div><strong>{{ $first_name }} {{ $last_name }}</strong></button>

                        </li>

                        <li class="nav-item">



                            <!-- <button type="button" class="btn btn-danger" onclick="window.close('fs')"> <i class="fa fa-window-close" aria-hidden="true"></i>Ολοκλήρωση</button> -->

                        </li>

                    </ul>

                </div>



        </nav>--}}



        <main style="margin-top: 13rem;" class="">

            @yield('content')

        </main>

    </div>

    <script>

		//window.onbeforeunload = function() { return "Your work will be lost."; };

		//$(window).blur(function() {	alert('You are not allowed to leave page'); });

	</script>



    <script language="JavaScript">



    </script>

    @yield('scripts')

</body>

</html>
