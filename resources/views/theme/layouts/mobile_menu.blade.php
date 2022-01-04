<div class="mobile-menu">
   <div class="mob-menu-inner">
      <div class="search-wrapper">
      <form method="get" action="search/term">
          {{ csrf_field() }}
            <input type="text" name="search_term" placeholder="Search KnowCrunch">
            <button type="submit"><img src="/theme/assets/images/icons/icon-magnifier.svg" alt=""/></button>
         </form>
      </div>
      <div class="menu-wrapper">
         <ul class="mob-menu">
            @if (!empty($header_menus))
            @foreach ($header_menus['menu']['Header'] as $key => $row)
            <li class="nav-item uppercase-item">
            <a title="{{ $row['data']['name'] }}" href="{{ $row['data']['slugable']['slug'] }}">{{ $row['data']['name'] }}</a>
            </li>
            @endforeach
            <li class="nav-item uppercase-item">
                <a title="Blog" href="/en/blog">Blog</a>
            </li>
            @endif
            @if (Auth::check())
            <li class="nav-item">
            <li  class="account-menu"><a href="/myaccount">Account</a></li>

            <?php $cartitems = Cart::content()->count(); ?>
            @if($cartitems > 0)
               <li class="cart-menu">
                  <a href="/cart" title="Cart">Cart<span class="cart-number-mobile @if(Auth::check()) loged @endif">{{ Cart::content()->count() }}</span>
                  </a>
               </li>
            @endif

            <li class="nav-item">
               <a href="{{ url('logout') }}">Sign out</a>
            </li>
            @else
            <li class="account-menu">
				   <a href="javascript:void(0)" title="Superhero Login">Account</a>
            </li>
            <?php $cartitems = Cart::content()->count(); ?>
            @if($cartitems > 0)
               <li class="cart-menu">
                  <a href="/cart" title="Cart">Cart<span class="cart-number-mobile @if(Auth::check()) loged @endif">{{ Cart::content()->count() }}</span>
                  </a>
               </li>
            @endif
            @endif

         </ul>
      </div>
   </div>
   <!-- /.mobile-menu -->
</div>
