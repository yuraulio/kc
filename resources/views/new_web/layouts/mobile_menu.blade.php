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

            @yield('main_menu_mobile')

            @if (Auth::check())

               @yield('account_menu')

               <?php $cartitems = Cart::content()->count(); ?>
               @if($cartitems > 0)
                  <li class="cart-menu">
                     <a href="/cart" title="Cart">
                        Cart
                        <span class="cart-number-mobile @if(Auth::check()) loged @endif">{{ Cart::content()->count() }}</span>
                     </a>
                  </li>
               @endif
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
</div>
