<header id="header">
    <div class="container clearfix">
        @include('layouts.headers.logo')
        <div class="menu-area xs-flex">

            <?php $cartitems = Cart::content()->count(); ?>
            <button class="hamburger hamburger--spin mob-menu-btn" type="button">
                <span class="hamburger-box">
                    <span class="hamburger-inner"></span>
                    @if($cartitems > 0)
                    <span class="cart-number">{{ Cart::content()->count() }}</span>
                    @endif
                </span>
            </button>

            <div class="header-actions clearfix">
                <ul class="actions-list">

                    <?php $cartitems = Cart::content()->count(); ?>
                    @if($cartitems > 0)
                        <li class="cart-menu">
                            <a href="/cart" title="Cart"><img src="{{cdn('/theme/assets/images/icons/icon-cart.svg')}}" class="replace-with-svg" width="18" alt="Cart">
                                <span class="cart-number">{{ Cart::content()->count() }}</span>
                            </a>
                        </li>
                    @endif

                    @if (isset($page) && $page->type == "Knowledge")
                        <!-- no user menu -->
                    @else
                        @if (Auth::check())
                            <li class="account-menu login-pad">
                                <a href="javascript:void(0)" title="Go to my account.">
                                    {!! \App\Helpers\UserHelper::getUserProfileImage(Auth::user(), ['width' => 23, 'height' => 23, 'id' => 'user-img-up', 'class' => 'login-image profile_images_panel' ]) !!}
                                </a>
                                <div class="account-submenu">
                                    @yield('account_menu')
                                </div>
                            </li>
                        @else
                            <li class="account-menu">
                                <a href="javascript:void(0)" title="Login to your account."><img src="{{cdn('/theme/assets/images/icons/user-circle.svg')}}"class="replace-with-svg" width="18" alt="Login to your account."></a>
                            </li>
                        @endif
                    @endif

                    <li class="header-search-area">
                        <a href="javascript:void(0)" title="Search" class="search-toggle"><img src="{{cdn('/theme/assets/images/icons/icon-magnifier.svg')}}" class="replace-with-svg" alt="Search"></a>
                        <div class="header-search-wrapper">
                            @php
                                $subdomain = explode(".", request()->getHost())[0];

                                use App\Model\Admin\Setting;
                                $searchPlaceholder = Setting::whereSetting("search_placeholder")->first();
                            @endphp

                            @if($subdomain == 'knowledge')
                                <form method='get' action='knowledge_search' class='text-center'>
                                    {{ csrf_field() }}
                                    <input id="sat" type="text" name="search_term"  class="search-input" placeholder="{{ $searchPlaceholder->value }}">
                                </form>
                            @elseif(request()->segment(1) == 'blog' || request()->segment(1) == 'blog_search' || request()->segment(1) == 'our-blog')
                                <form method='get' action='blog_search' class='text-center'>
                                    {{ csrf_field() }}
                                    <input id="sat" type="text" name="search_term"  class="search-input" placeholder="{{ $searchPlaceholder->value }}">
                                </form>
                            @else
                                <form method="get" action="event_search">
                                    {{ csrf_field() }}
                                    <input id="sat" type="text" name="search_term"  class="search-input" placeholder="{{ $searchPlaceholder->value }}">
                                </form>
                            @endif
                        </div>
                    </li>
                </ul>
            </div>

            {{-- main menu desktop --}}
            <ul class="main-menu">
                @yield('main_menu_desktop')
            </ul>

        </div>
    </div>

</header>

@if(Request::is('/') )
    <script>
        document.getElementById('header').classList.add('header-transparent');
    </script>
@endif

<script>

    $(document).ready(function(){

        if(hasTicker !== undefined && hasTicker){
            $('#header').addClass('has_ticker');
        }

        if(typeof hasCountdown != 'undefined' && hasCountdown){
            $('#header').addClass('has_countdown');
        }

    });
</script>



