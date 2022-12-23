
{{-- Navigation {{ !Request::is('/') ? "top-nav-collapse short" : "" }}--}}
<style>
    .close {
  position: absolute;
  right: 32px;
  top: 32px;
  width: 32px;
  height: 32px;
  opacity: 0.3;
}
.close:hover {
  opacity: 1;
}
.close:before, .close:after {
  position: absolute;
  left: 30px;
  content: ' ';
  height: 33px;
  width: 2px;
  top: -15px;
  background-color: #96c1ff;
}
.close:before {
  transform: rotate(45deg);
}
.close:after {
  transform: rotate(-45deg);
}
</style>
<header id="header" >
<a href="#" title="Close window" class="close"></a>
				<div class="container clearfix">
                    <div class="row">
                        <div class="logo-area col-md-2 col-sm-12 text-sm-center">
                            <a href="/" class="logo">Know Crunch</a>
                        </div>
                        <div class="title-area col-md-7 col-sm-12">
                            <h1 style="color:white;">{{ $event_title }}</h1>
                        </div>
                        <div class="menu-area col-md-3 col-sm-12">

                            <div class="header-actions clearfix">
                                <ul class="actions-list">

                                    <?php

                                        $img_src = get_profile_image(Auth::user()->image);

                                    ?>

                                    <li class="account-menu login-pad">
                                        <div style="display:inline-block; margin-right: 1rem; vertical-align: sub;">
                                            <h5 style="color:white !important;">{{ Auth::user()->firstname.' '.Auth::user()->lastname }}</h5>
                                        </div>
                                        <div style="display:inline-block" href="javascript:void(0)">

                                            <img class="login-image" src="{{cdn($img_src)}}" onerror="this.src='{{cdn('/theme/assets/images/icons/user-circle-placeholder.svg')}}'" alt="user-profile-placeholder-image"/>

                                        </div>
                                        <div>
                                            <label style="color:#A9D2FE; font-size: 1.2rem" for="">Time remaining:</label>
                                        </div>

                                        <div style="background-color: #A9D2FE; font-size: 18px; width: fit-content; float: inline-end;" id="timerdiv" class="countdown-styled text-right">
                                            <span style="color:white;" id="hours">{{$hours}}</span> <span style="color:white;" >hr :</span>
                                            <span style="color:white;" id="mins"><?php if($minutes<10){ echo '0'; }?>{{$minutes}}</span> <span style="color:white;" >min :</span>
                                            <span style="color:white;" id="seconds">00</span> <span style="color:white;" >sec</span>
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

    $(".close").click(function(){
          alert("The paragraph was clicked.");
    });
</script>
@endif
