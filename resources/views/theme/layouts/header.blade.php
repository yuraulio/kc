{{-- Navigation {{ !Request::is('/') ? "top-nav-collapse short" : "" }}--}}
<header id="header">
  <div class="container clearfix">
    @include('layouts.headers.logo')
    <div class="menu-area xs-flex">

      <?php $cartitems = Cart::content()->count(); ?>
      {{--if($cartitems > 0)
      <span class="cart-menu xs-cart">
						<a href="/cart" title="Cart"><img loading="lazy" class="resp-img"
                                              src="{{cdn('/theme/assets/images/icons/icon-cart.svg')}}" width="18"
                                              height="18" alt="Cart" title="Cart">
							  <span class="cart-number">{{ Cart::content()->count() }}</span>
						</a>
					</span>
      endif--}}

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
            <a href="/cart" title="Cart"><img loading="lazy" src="{{cdn('/theme/assets/images/icons/icon-cart.svg')}}"
                                              class="replace-with-svg resp-img" width="18" height="18" alt="Cart"
                                              title="Cart">
              <span class="cart-number">{{ Cart::content()->count() }}</span>
            </a>
          </li>
          @endif

          @if (Auth::check())
          <li class="account-menu login-pad">
            <a href="javascript:void(0)" title="Go to my account">
              {!! \App\Helpers\UserHelper::getUserProfileImage(Auth::user(), ['width' => 23, 'height' => 23, 'id' =>
              'user-img-up', 'class' => 'login-image profile_images_panel' ]) !!}
            </a>
            <div class="account-submenu">
              @yield('account_menu')
            </div>

          </li>
          @else

          <li class="account-menu">
            <a href="javascript:void(0)" title="Login to your account.">
              <img loading="lazy"
                   src="{{cdn('/theme/assets/images/icons/user-circle.svg')}}"
                   class="replace-with-svg resp-img"
                   width="18" height="18"
                   alt="Login to your account."
                   title="Login to your account.">
            </a>
          </li>
          @endif


          <li class="header-search-area">
            <a href="javascript:void(0)" title="Search" class="search-toggle">
              <img loading="lazy"
                   src="{{cdn('/theme/assets/images/icons/icon-magnifier.svg')}}"
                   class="replace-with-svg" alt="Search"
                   width="20" height="20"
                   title="Search">
            </a>
            <div class="header-search-wrapper">

              @if(request()->segment(2) == 'blog')
              <form method='get' action='{{route("binshopsblog.search", app(' request')->get('locale'))}}' class='text-center'>
              {{ csrf_field() }}
              <input type="input" class="search-input" name="s" placeholder="Search" id='name'
                     value="{{ \Request::get('s') }}" />
              </form>
              @else
              <form method="get" action="event_search">
                {{ csrf_field() }}
                <input id="sat" type="text" name="search_term" class="search-input" placeholder="Search">
              </form>
              @endif
            </div>
          </li>
        </ul>
      </div>

      {{--
      <ul class="main-menu">
        @if (!empty($header_menus))
        @foreach ($header_menus['menu']['Header'] as $key => $row)
        <?php //dd($row['header_menus']['data']);
        //dd($row); ?>
        <li>
          <a title="{{ $row['data']['name'] }}" href="{{ $row['data']['slugable']['slug'] }}">{{ $row['data']['name'] }}</a>
        </li>
        @endforeach

        <li>
          <a title="Corporate Training" href="/corporate-training">Corporate Training</a>
        </li>

        <li>
          <a title="Blog" href="/blog">Blog</a>
        </li>
        @endif
      </ul>
      --}}

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

{{--@include('flash::message')--}}
