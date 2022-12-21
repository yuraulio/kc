@inject('frontHelp', 'Library\FrontendHelperLib')

{{-- Navigation {{ !Request::is('/') ? "top-nav-collapse short" : "" }}--}}

<header id="header" >
				<div class="container clearfix">
					<div class="logo-area">
						<a href="/" class="logo">Know Crunch</a>
					</div>
					<div class="menu-area xs-flex">

					<?php $cartitems = Cart::content()->count(); ?>
					@if($cartitems > 0)
					<span class="cart-menu xs-cart">
						<a href="/cart" title="Cart"><img src="{{cdn('/theme/assets/images/icons/icon-cart.svg')}}" width="18" alt="Cart">
							<!--  <span class="badge defaultCount">{{ Cart::content()->count() }}</span>-->
						</a>
					</span>
						@endif

						<button class="hamburger hamburger--spin mob-menu-btn" type="button">

						  	<span class="hamburger-box">
						    	<span class="hamburger-inner"></span>
						  	</span>
						</button>

						<div class="header-actions clearfix">
							<ul class="actions-list">
                                <?php $cartitems = Cart::content()->count(); ?>
                                @if($cartitems > 0)
								    <li class="cart-menu">
                                        <a href="/cart" title="Cart"><img src="{{cdn('/theme/assets/images/icons/icon-cart.svg')}}" width="18" alt="Cart">
                                          <!--  <span class="badge defaultCount">{{ Cart::content()->count() }}</span>-->
                                        </a>
                                    </li>
								@endif

                                @if (Sentinel::check())

                                <li class="account-menu">
									<a href="javascript:void(0)" title="Superhero Login"><img src="{{cdn('/theme/assets/images/icons/user-circle.svg')}}"  alt="Superhero Login"></a>
                                        <div class="account-submenu">
                                            <ul>
                                                <li class="account-menu"><a href="/myaccount">Account</a></li>
                                                <li><a href="{{ url('logout') }}">Sign Out</a></li>
                                            </ul>
                                        </div>

                                    </li>

                                @else

                                <li class="account-menu">
									<a href="javascript:void(0)" title="Superhero Login"><img src="{{cdn('/theme/assets/images/icons/user-circle.svg')}}" width="18" alt="Superhero Login"></a>

                                </li>
                                @endif




                                <li class="header-search-area">
									<a href="javascript:void(0)" title="Search" class="search-toggle"><img src="{{cdn('/theme/assets/images/icons/icon-magnifier.svg')}}" alt="Search"></a>
									<div class="header-search-wrapper">
										<form method="get" action="event_search">
                                        {{ csrf_field() }}
											<input id="sat" type="text" name="search_term"  class="search-input" placeholder="Search">
										</form>
									</div>


                                </li>


							</ul>
						</div>

						<ul class="main-menu">

                            @if (!empty($filter_type))
                                @foreach ($filter_type as $key => $row)
                                    <li>
                                        <a title="{{ $row->name }}" href="{{ $row->slug }}">{{ $row->name }}</a>
                                    </li>
								@endforeach
								<li>
								<a title="Corporate Training" href="/corporate-training">Corporate Training</a>
								</li>
                            @endif
							<!--<li><a href="#">In-class courses</a></li>
							<li><a href="#">E-learning Courses</a></li>
							<li><a href="#">Corporate training</a></li>-->
						</ul>
					</div>
				</div>

			</header>

@if(Request::is('/') )
<script>

    document.getElementById('header').classList.add('header-transparent');
</script>
@endif

@include('flash::message')
