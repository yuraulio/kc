
@include('theme.layouts.menu_component_myaccount')
<!doctype html>
<html class="no-js" lang="">
   <head>
      <meta charset="utf-8">

	  @yield('title')
      <meta name="description" content="">
      <meta name="viewport" content="width=device-width, initial-scale=1">
      <meta name="csrf-token" content="{{ csrf_token() }}">
      @include('theme.layouts.favicons')
      <!-- Place favicon.ico in the root directory -->
      <link rel="stylesheet" href="theme/assets/css/old.css" />
      <link rel="stylesheet" href="{{ cdn('theme/assets/css/normalize.css') }}" />
      <link rel="stylesheet" href="{{cdn('theme/assets/css/jquery.mCustomScrollbar.css')}}" />
      <link rel="stylesheet" href="{{cdn('theme/assets/css/jquery-ui.css')}}">
      <link rel="stylesheet" href="{{ cdn('theme/assets/css/grid.css') }}" />
      <link rel="stylesheet" href="{{ cdn('theme/assets/css/grid-flex.css') }}" />
      <link rel="stylesheet" href="{{ cdn('theme/assets/css/global.css') }}" />
      <link rel="stylesheet" href="{{ cdn('theme/assets/css/main.css') }}" />
      <link rel="stylesheet" href="{{ cdn('theme/assets/css/fontawesome/css/kcfonts.css') }}" />
      <script type="application/ld+json">
         {
           "@context": "http://schema.org",
           "@type": "EducationalOrganization",
           "url": "https://knowcrunch.com/",
           "address": {
             "@type": "PostalAddress",
             "addressLocality": "Delaware",
             "addressRegion": "DE",
             "postalCode": "19702",
             "streetAddress": "2035 Sunset Lake Road"
           },
           "name": "Knowcrunch Inc",
           "logo": "/theme/assets/images/logo.png"
         }
      </script>
   </head>
   <body>
      <!--[if IE]>
      <p class="browserupgrade">You are using an <strong>outdated</strong> browser. Please <a href="https://browsehappy.com/">upgrade your browser</a> to improve your experience and security.</p>
      <![endif]-->
      <div class="page-wrapper">
         <header id="header" >
            <div class="container clearfix">
              @include('layouts.headers.logo')
               <div class="menu-area">
                  <button class="hamburger hamburger--spin mob-menu-btn" type="button">
                     <span class="hamburger-box">
                        <span class="hamburger-inner"></span>
                     </span>
                  </button>
                  <div class="header-actions clearfix">
                     <ul class="actions-list">
                        {{--@if($data['cartitems'] > 0)
                        <li>
                           <a href="/cart" title="Cart">
                              <img src="{{cdn('/theme/assets/images/icons/icon-cart.svg')}}" class="replace-with-svg" width="18" alt="Cart">
                              <!--  <span class="badge defaultCount">{{ Cart::content()->count() }}</span>-->
                           </a>
                        </li>
                        @endif--}}
                        @if (Auth::check())
                        <li class="account-menu">
                           <a href="javascript:void(0)" title="Go to my account."><img src="{{cdn('/theme/assets/images/icons/knowcrunch-superhero-icons-login.svg')}}" class="replace-with-svg" alt="Go to my account."></a>
                           <div class="account-submenu">
                              <ul>
                                 <li><a href="/myaccount">Account</a></li>
                                 <li><a href="{{ url('logmeout') }}">Sign Out</a></li>
                              </ul>
                           </div>
                        </li>
                        @else
                        <li class="account-menu">
                           <a href="javascript:void(0)" title="Login to your account."><img src="{{cdn('/theme/assets/images/icons/knowcrunch-superhero-icons-login.svg')}}" class="replace-with-svg" width="18" alt="Login to your account."></a>
                        </li>
                        @endif
                        <li class="header-search-area">
                           <a href="#" title="Search" class="search-toggle"><img src="{{cdn('/theme/assets/images/icons/icon-magnifier.svg')}}" class="replace-with-svg" width="22" alt="Search"></a>
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

							@yield('main_menu_desktop')
						</ul>
               </div>
			</div>

         </header>
		 <div class="mobile-menu">
   <div class="mob-menu-inner">
      <div class="search-wrapper">
      <form method="get" action="event_search">
          {{ csrf_field() }}
            <input type="text" name="search_term" placeholder="Search Knowcrunch">
            <button type="submit"><img src="{{cdn('/theme/assets/images/icons/icon-magnifier.svg')}}" class="replace-with-svg" alt=""/></button>
         </form>
      </div>
      <div class="menu-wrapper">
         <ul class="mob-menu">
         @yield('main_menu_mobile')
            @if (Auth::check())
            <li class="nav-item">
            <li><a href="/myaccount">Account</a></li>
            <li class="nav-item">
               <a href="{{ url('logmeout') }}">Sign out</a>
            </li>
            @else
            <li class="account-menu">
				   <a href="javascript:void(0)" title="Login to your account.">Account</a>
            </li>
            @endif
         </ul>
      </div>
   </div>
   <!-- /.mobile-menu -->
</div>
@if (!Auth::check())

<div  class="login-popup-wrapper">

<div class="alert-outer" hidden>
					<div class="container">
						<div class="alert-wrapper error-alert">
							<div class="alert-inner">
								<p id="account-error"></p>
							</div>
						</div>
					</div>
				<!-- /.alert-outer -->
	</div>

    <div id="login-popup" class="login-popup">
        <a href="#" class="close-btn"><img width="26" src="{{cdn('theme/assets/images/icons/icon-close.svg')}}" class="replace-with-svg" alt="Close"></a>
        <div class="heading">
        <span>Account login</span>
            <p>Access your courses, schedule & files.</p>
        </div>

        <label>Email <span class="required">(*)</span></label>
            <div class="input-wrapper input-wrapper--text input-wrapper--email">
                <input type="text" id="email" autocomplete="off">

            </div>

            </br>

            <label> Password <span class="required">(*)</span></label><span data-id="password" class="icon"><img width="20" src="{{cdn('/theme/assets/images/icons/eye-password.svg')}}" alt="">Show</span>
            <div class="input-wrapper input-wrapper--text">
                <input type="password"  id="password" autocomplete="off">
            </div>

            <div class="form-group">
                <label for="remember-me"><input id="remember-me" type="checkbox">Remember me</label>
                <a id="forgot-pass" href="javascript:void(0)">Reset or create your password.</a>
            </div>
            <input type="submit" onclick="loginAjaxNew()" value="LOGIN">

    </div><!-- ./login-popup -->

    <div id="forgot-pass-input" class="login-popup" hidden>
        <a href="#" class="close-btn"><img width="26" src="{{cdn('theme/assets/images/icons/icon-close.svg')}}" alt="Close"></a>
        <div class="heading">
        <span>Password</span>
            <p>Use your account email to create or change your password.</p>
        </div>
        <form method="post" action="/myaccount/reset" autocomplete="off" class="validate-form">
            {!! csrf_field() !!}
            <div class="input-wrapper input-wrapper--text input-wrapper--email">
                <span class="icon"><img width="14" src="{{cdn('theme/assets/images/icons/icon-email.svg')}}" alt=""></span>
                <input type="text" placeholder="Email" name="email" id="email-forgot">
            </div>

            <input type="submit"  value="Change">
        </form>
    </div><!-- ./login-popup -->
</div><!-- ./login-popup-wrapper -->
@endif
         @yield('message_page')
         <?php
$social_media = get_social_media();
?>


   <footer id="footer">
    @if(strtotime(date('Y-m-d')) == strtotime(config('services.promotions.BLACKFRIDAY')))
        {{--@include('theme.blackfriday.blackfriday')--}}
    @endif
    <div class="container">
        <div class="footer-row">
            <div class="col12 award-section">
                <h4 class="footer-title award-title">Our awards</h4>
                <div class="mobile-toggle">
                  <div class="row clearfix footer-award-menu">
                    <div class="col4 col-sm-12 award-div">
                      <div><img alt_text="social media award" width="158" height="37" src="{{cdn('/awards/knowcrunch-awards-2024.png')}}"></div>
                      {{--                            <p> Best Digital Marketing E-learning--}}
                      {{--                                Award by <span class="text-highlight2"> Education Leaders </span> </p>--}}
                    </div>
                    {{--                        <div class="col4 col-sm-12 award-div">--}}
                    {{--                            <div><img alt_text="social media award" width="41" height="38"  src="{{cdn('/awards/knowcrunch-award-marketing-education-best-social-media-learning-program-2x.png')}}"></div>--}}
                    {{--                            <p> Best Social Media Learning Program--}}
                    {{--                            Award by <span class="text-highlight2"> Social Media World </span></p>--}}
                    {{--                        </div>--}}
                    {{--                        <div class="col4 col-sm-12 award-div">--}}
                    {{--                            <div><img alt_text="social media award" width="62" height="35"  src="{{cdn('/awards/knowcrunch-award-marketing-best-content-strategy-2x.png')}}"></div>--}}
                    {{--                            <p> Best Multi-Channel Content Strategy & Best Use of Multichannel Social Media in Content Marketing by <span class="text-highlight2">Digital Marketing Awards </span> </p>--}}
                    {{--                        </div>--}}
                  </div>
                </div>
        </div>
        <div class="footer-col-1">
            <h4 class="footer-title">Get our news</h4>
            <div class="mobile-toggle">
                <div class="footer-form">
                    <div class="newsletterReponse"></div>
                    <div id="mc_embed_signup">
                        <form  class="form-control" action="//knowcrunch.us15.list-manage.com/subscribe/post?u=312b4ca8015cf92c92eeb4dbb&amp;id=e427673a3f" method="post" id="mc-embedded-subscribe-form" name="mc-embedded-subscribe-form" class="validate" target="_blank" novalidate>
                            <div id="mc_embed_signup_scroll">
                                <input type="text" id="mce-FNAME" name="FNAME" placeholder="First name"/>
                                <input type="text" id="mce-LNAME" name="LNAME" placeholder="Surname"/>
                                <div class="input-safe-wrapper">
                                    <input type="text" class="required" id="mce-EMAIL" name="EMAIL" placeholder="E-mail address *"  />
                                </div>
                                <input type="text" name="mobile-num" placeholder="Mobile number" />
                                <div style="position: absolute; left: -5000px;" aria-hidden="true"><input type="text" name="b_312b4ca8015cf92c92eeb4dbb_e427673a3f" tabindex="-1" value=""></div>
                                <button type="submit" class="btn btn--md btn--secondary">Subscribe</button>
                                <div id="mce-responses" class="clear">
                                    <div class="response" id="mce-error-response" style="display:none"></div>
                                    <div class="response" id="mce-success-response" style="display:none"></div>
                                </div>
                                <p>We respect your personal data. By subscribing, you agree that we can contact you to share our news or for marketing purposes according to our <a href="/data-privacy-policy" class="dark-bg">Data Privacy Policy.</a></p>
                                <script type='text/javascript'>(function($) {window.fnames = new Array(); window.ftypes = new Array();fnames[0]='EMAIL';ftypes[0]='email';fnames[1]='FNAME';ftypes[1]='text';fnames[2]='LNAME';ftypes[2]='text';});</script>
                                <div class="col-lg-12 col-md-12 col-sm-12">
                                    <div id="mce-responses" class="clear">
                                        <div class="response" id="mce-error-response" style="display:none"></div>
                                        <div class="response" id="mce-success-response" style="display:none"></div>
                                    </div>
                                </div>
                            </div>
                        </form>
                        <script type='text/javascript' src='//s3.amazonaws.com/downloads.mailchimp.com/js/mc-validate.js'></script><script type='text/javascript'>(function($) {window.fnames = new Array(); window.ftypes = new Array();fnames[0]='EMAIL';ftypes[0]='email';fnames[1]='FNAME';ftypes[1]='text';fnames[2]='LNAME';ftypes[2]='text';});</script>
                    </div>
                </div>
            </div>
        </div>
        <div class="footer-col-2">
            <div class="row clearfix">
            <div class="col6 col-sm-12">

                {{-- footer menu 1 desktop --}}
                @yield('footer_menu_1_title_desktop')
                <div class="mobile-toggle menu-desktop">
                    <ul class="footer-menu">
                        @yield('footer_menu_1_desktop')
                    </ul>
                </div>

                {{-- footer menu 1 mobile --}}
                @yield('footer_menu_1_title_mobile')
                <div class="mobile-toggle menu-mobile">
                    <ul class="footer-menu">
                        @yield('footer_menu_1_mobile')
                    </ul>
                </div>

            </div>
            <div class="col6 col-sm-12">

                {{-- footer menu 2 desktop --}}
                @yield('footer_menu_2_title_desktop')
                <div class="mobile-toggle menu-desktop">
                    <ul class="footer-menu">
                        @yield('footer_menu_2_desktop')
                    </ul>
                </div>

                {{-- footer menu 2 mobile --}}
                @yield('footer_menu_2_title_mobile')
                <div class="mobile-toggle menu-mobile">
                    <ul class="footer-menu">
                        @yield('footer_menu_2_mobile')
                    </ul>
                </div>

                {{-- footer menu 3 desktop --}}
                @yield('footer_menu_3_title_desktop')
                <div class="mobile-toggle menu-desktop">
                    <ul class="footer-menu footer-menu-3">
                        @yield('footer_menu_3_desktop')
                    </ul>
                </div>

                {{-- footer menu 3 mobile --}}
                @yield('footer_menu_3_title_mobile')
                <div class="mobile-toggle menu-mobile">
                    <ul class="footer-menu footer-menu-3">
                        @yield('footer_menu_3_mobile')
                    </ul>
                </div>

            </div>
            </div>
        </div>
        <div class="footer-col-3">
            <div class="clearfix">
                <ul class="footer-social-menu">
                    @if($social_media['facebook']['url'] != '')
                        <li>
                            <a target="_blank" title="{{ $social_media['facebook']['title'] }}" href="{{ $social_media['facebook']['url'] }}">
                                <img class="replace-with-svg" src="{{cdn('/theme/assets/images/icons/social/Facebook.svg')}}" height="23" width="23" alt="{{ $social_media['facebook']['title'] }}">
                            </a>
                        </li>
                    @endif
                    @if($social_media['twitter']['url'] != '')
                        <li>
                            <a target="_blank" title="{{ $social_media['twitter']['title'] }}" href="{{ $social_media['twitter']['url'] }}">
                                <img class="replace-with-svg" src="{{cdn('/theme/assets/images/icons/social/Twitter.svg')}}" height="23" width="23" alt="{{ $social_media['twitter']['title'] }}">
                            </a>
                        </li>
                    @endif
                    @if($social_media['instagram']['url'] != '')
                        <li>
                            <a target="_blank" title="{{ $social_media['instagram']['title'] }}" href="{{ $social_media['instagram']['url'] }}">
                                <img class="replace-with-svg" src="{{cdn('/theme/assets/images/icons/social/Instagram.svg')}}" height="23" width="23" alt="{{ $social_media['instagram']['title'] }}">
                            </a>
                        </li>
                    @endif
                    @if($social_media['linkedin']['url'] != '')
                        <li>
                            <a target="_blank" title="{{ $social_media['linkedin']['title'] }}" href="{{ $social_media['linkedin']['url'] }}">
                                <img class="replace-with-svg" src="{{cdn('/theme/assets/images/icons/social/Linkedin.svg')}}" height="23" width="23" alt="{{ $social_media['linkedin']['title'] }}">
                            </a>
                        </li>
                    @endif
                    @if($social_media['youtube']['url'] != '')
                        <li>
                            <a target="_blank" title="{{ $social_media['youtube']['title'] }}" href="{{ $social_media['youtube']['url'] }}">
                                <img class="replace-with-svg" src="{{cdn('/theme/assets/images/icons/social/Youtube.svg')}}" height="23" width="23" alt="{{ $social_media['youtube']['title'] }}">
                            </a>
                        </li>
                    @endif

                    @if($social_media['tiktok']['url'] != '')
                        <li>
                            <a target="_blank" title="{{ $social_media['tiktok']['title'] }}" href="{{ $social_media['tiktok']['url'] }}">
                                <img class="replace-with-svg" src="{{cdn('/theme/assets/images/icons/social/Knowcrunch-TikTok.svg')}}" height="23" width="23" alt="{{ $social_media['tiktok']['title'] }}"> </a>
                        </li>
                    @endif

                    @if($social_media['medium']['url'] != '')
                        <li>
                            <a target="_blank" title="{{ $social_media['medium']['title'] }}" href="{{ $social_media['medium']['url'] }}">
                                <img class="replace-with-svg" src="{{cdn('/theme/assets/images/icons/social/Knowcrunch-Medium-Blog.svg')}}" height="23" width="23" alt="{{ $social_media['medium']['title'] }}"> </a>
                        </li>
                    @endif

                    @if($social_media['pinterest']['url'] != '')
                        <li>
                            <a target="_blank" title="{{ $social_media['pinterest']['title'] }}" href="{{ $social_media['pinterest']['url'] }}">
                                <img class="replace-with-svg" src="{{cdn('/theme/assets/images/icons/social/Knowcrunch-Pinterest.svg')}}" height="23" width="23" alt="{{ $social_media['pinterest']['title'] }}"> </a>
                        </li>
                    @endif

                    @if($social_media['spotify']['url'] != '')
                        <li>
                            <a target="_blank" title="{{ $social_media['spotify']['title'] }}" href="{{ $social_media['spotify']['url'] }}">
                                <img class="replace-with-svg" src="{{cdn('/theme/assets/images/icons/social/Knowcrunch-Spotify.svg')}}" height="23" width="23" alt="{{ $social_media['spotify']['title'] }}"> </a>
                        </li>
                    @endif
                </ul>
            </div>
            <div class="copyright-wrapper">
                <p>Knowcrunch Inc., <br/>2035 Sunset Lake Road, <br/>Delaware, USA.</p>
                <p>Knowcrunch Inc. © <?php echo date('Y')?></p>
            </div>
        </div>
    </div>
</div>
</footer>
         <a href="#" class="go-top-btn"><i class="icon-up-open"></i></a>
      </div>
      <script src="{{cdn('/theme/assets/js/new_js/vendor/modernizr-3.7.1.min.js')}}"></script>
<script src="{{cdn('/theme/assets/js/new_js/jquery-3.4.1.min.js')}}" ></script>
<script src="{{cdn('/theme/assets/js/new_js/jquery-ui.js')}}"></script>
<script src="{{cdn('/theme/assets/js/new_js//plugins.js')}}"></script>
<script src="{{cdn('/theme/assets/js/new_js//main.js')}}"></script>

      <script>
         $(document).on('click', '#forgot-pass', function(e){
             $('#login-popup').hide()
             $('#forgot-pass-input').show()
         })

         $(document).on('click', '.close-btn', function(e){
             $('#login-popup').show()
             $('#forgot-pass-input').hide()
         })

		   function loginAjaxNew(){
    var email = $('#email').val();
    var password = $('#password').val();
    var remember = document.getElementById("remember-me").checked;

    if (email.length > 4 && password.length > 4) {

    $.ajax({
            headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
            url: "/studentlogin", type: "post",
            data: {email:email, password:password, remember:remember},
            success: function(data) {

                data = data.data

                switch (data.status) {
                    case 0:
                        if (data.message.length > 0) {

                            var p = document.getElementById('account-error').textContent = data['message'];
                          /*  var img = document.createElement('img');
                            img.setAttribute('src',"/theme/assets/images/icons/alert-icons/icon-error-alert.svg" )
                            img.setAttribute('alt',"Info Alert" )

                            $('#account-error').append(img);*/
                        //	console.log(p);
                            $('.alert-outer').show()

                        } else {


                        }
                        break;
                    case 1:

                        //setTimeout( function(){
                            window.location.replace(data.redirect);
                        //}, 1000 );

                        break;

                    default:
                        shakeModal();
                        break;
                }



            },
            error: function(data) {
                //shakeModal();
            }
        });

        }
        else {
          //  shakeModal();

        }


}



$(".close-alert").on("click", function () {

$('.alert-outer').hide()

});


$(document).keyup(function(event){

    if($('.login-popup-wrapper').hasClass('active')){

       if(event.keyCode == 13){
        loginAjaxNew()
       }
    }
})
      </script>
      <script>

$('.icon').click(function(){
    let input = $(`#${$(this).data('id')}`);

    if(input.attr('type') === "password"){
        input.attr('type','text')

        $(this).find('img').attr('src', "{{cdn('/theme/assets/images/icons/eye-password-active.svg')}}");


    }else{
        input.attr('type','password')
        $(this).find('img').attr('src', "{{cdn('/theme/assets/images/icons/eye-password.svg')}}");
    }

})

</script>
   </body>
</html>
