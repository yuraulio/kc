
{{-- Navigation {{ !Request::is('/') ? "top-nav-collapse short" : "" }}--}}

@php
$hours = 0;
$minutes = 0;
@endphp

<header id="header" >
<a href="#" title="Close window" class="close"></a>
				<div class="container clearfix">
                    <div class="row">

                        {{--<div class="logo-area col-sm-12 col-md-2 col-lg-2 text-sm-center">
                            <a href="/" class="logo">Know Crunch</a>
                        </div>
                        <div class="title-area col-sm-12 col-md-9 offset-md-1 col-lg-5 offset-lg-1">
                            <h1>{{ $event_title }}</h1>
                        </div>
                        <div class="menu-area col-sm-12 col-md-12 col-lg-3 text-right">--}}

                        <div class="logo-area col-sm-12 col-md-2 col-lg-2 text-sm-center">
                            <a href="/" class="logo">Know Crunch</a>
                        </div>
                        <div class="title-area col-sm-12 col-md-5 offset-md-1 col-lg-5 offset-lg-1">
                            <h1>{{ $event_title }}</h1>
                        </div>
                        <div class="menu-area col-sm-12 col-md-4 col-lg-3 text-right">

                            {{--<div class="header-actions clearfix">--}}
                            <div class="clearfix">
                                <ul class="actions-list">

                                    <?php

                                        $img_src = get_profile_image(Auth::user()->image);

                                    ?>

                                    <li class="account-menu login-pad">
                                        <div class="event-name">
                                            <h5>{{ Auth::user()->firstname.' '.Auth::user()->lastname }}</h5>
                                            <img class="login-image" src="{{cdn($img_src)}}" onerror="this.src='{{cdn('/theme/assets/images/icons/user-circle-placeholder.svg')}}'" alt="user-profile-placeholder-image"/>
                                        </div>
                                        <div class="event-name-icon" href="javascript:void(0)">



                                        </div>
                                        <div class="time_remaining_header" hidden>
                                            <label for="">Time remaining:</label>
                                        </div>

                                        <div hidden id="timerdiv" class="countdown-styled text-right time_remaining_header">
                                            <span id="hours">{{$hours}}</span> <span >hr :</span>
                                            <span id="mins"><?php if($minutes<10){ echo '0'; }?>{{$minutes}}</span> <span >min :</span>
                                            <span id="seconds">00</span> <span >sec</span>
                                        </div>


                                    </li>













                                    {{--<li class="header-search-area">
                                        <a href="javascript:void(0)" title="Search" class="search-toggle"><img src="{{cdn('/theme/assets/images/icons/icon-magnifier.svg')}}" class="replace-with-svg" alt="Search"></a>
                                        <div class="header-search-wrapper">

                                            @if(request()->segment(2) == 'blog')
                                                <form method='get' action='{{route("binshopsblog.search", app('request')->get('locale'))}}' class='text-center'>
                                                    {{ csrf_field() }}
                                                    <input type="input" class="search-input" name="s" placeholder="Search" id='name' value="{{ \Request::get('s') }}"/>
                                                </form>
                                            @else
                                                <form method="get" action="event_search">
                                                    {{ csrf_field() }}
                                                    <input id="sat" type="text" name="search_term"  class="search-input" placeholder="Search">
                                                </form>
                                            @endif
                                        </div>


                                    </li>--}}


                                </ul>



                            </div>


                        </div>
                    </div>

				</div>

			</header>

@if(Request::is('/') )
<script>

    document.getElementById('header').classList.add('header-transparent');



</script>
@endif
