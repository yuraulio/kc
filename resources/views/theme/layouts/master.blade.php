<?php $header_menus = get_header();?>
<!doctype html>
<html lang="en" class="no-js">
<head>
<base href="{!! URL::to('/') !!}/" target="_self" />
<meta charset="UTF-8">
<meta name="csrf-token" content="{{ csrf_token() }}">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
@yield('metas')
@yield('css')

{{-- title and favicons --}}

{{--<link rel="icon" href="theme/assets/img/icon/fav_icon.gif">--}}
{{-- necessary stylesheets --}}
@include('theme.layouts.favicons')
@yield('header')
@include('theme.layouts.header_scripts')

@if(!env('APP_DEBUG'))
{{-- Google Tag Manager --}}
{{--<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
})(window,document,'script','dataLayer','GTM-ML7649C');
</script>--}}
{{-- End Google Tag Manager --}}

{{-- Facebook Pixel Code --}}
{{--<script>
!function(f,b,e,v,n,t,s){if(f.fbq)return;n=f.fbq=function(){n.callMethod?
n.callMethod.apply(n,arguments):n.queue.push(arguments)};if(!f._fbq)f._fbq=n;
n.push=n;n.loaded=!0;n.version='2.0';n.queue=[];t=b.createElement(e);t.async=!0;
t.src=v;s=b.getElementsByTagName(e)[0];s.parentNode.insertBefore(t,s)}(window,
document,'script','https://connect.facebook.net/en_US/fbevents.js');
fbq('init', '1745172385734431'); // Insert your pixel ID here.
fbq('track', 'PageView');
</script>
<noscript><img height="1" width="1" style="display:none" alt="fb pixel" title="fb pixel"
src="https://www.facebook.com/tr?id=1745172385734431&amp;ev=PageView&amp;noscript=1"
/></noscript>--}}
{{-- DO NOT MODIFY --}}
{{-- End Facebook Pixel Code --}}
@endif

{{--<script type="text/javascript">
function timer11(){ga('send', 'event', 'TimeOnPage', '1', '11-30 seconds', { 'nonInteraction': 1 });}
function timer31(){ga('send', 'event', 'TimeOnPage', '2', '31-60 seconds', { 'nonInteraction': 1 });}
function timer61(){ga('send', 'event', 'TimeOnPage', '3', '61-180 seconds', { 'nonInteraction': 1 });}
function timer181(){ga('send', 'event', 'TimeOnPage', '4', '181-600 seconds', { 'nonInteraction': 1 });}
function timer601(){ga('send', 'event', 'TimeOnPage', '5', '601-1800 seconds', { 'nonInteraction': 1 });}
function timer1801(){ga('send', 'event', 'TimeOnPage', '6', '1801+ seconds', { 'nonInteraction': 1 });}
//ga('send', 'event', 'TimeOnPage', '0', '0-10 seconds', { 'nonInteraction': 1 });
setTimeout(timer11,11000);
setTimeout(timer31,31000);
setTimeout(timer61,61000);
setTimeout(timer181,181000);
setTimeout(timer601,601000);
setTimeout(timer1801,1801000);
</script>--}}

@if(!env('APP_DEBUG'))
<!-- NEW Google Tag Manager -->
<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
	new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
	j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
	'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
	})(window,document,'script','dataLayer','GTM-ML7649C');
</script>

<script async src="https://www.googletagmanager.com/gtag/js?id=AW-859787100"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'AW-859787100');
</script>
@endif
<!-- End Google Tag Manager -->

<!-- OneTrust Cookies Consent Notice (Production CDN, knowcrunch.com, en-GB) start -->
@if(Agent::isDesktop())

<script type="text/javascript">
var _iub = _iub || [];
_iub.csConfiguration = {"whitelabel":false,"lang":"en","siteId":1874776,"cookiePolicyInOtherWindow":true,"consentOnDocument":true,"perPurposeConsent":true,"consentOnHorizontalScroll":true,"cookiePolicyId":76147833,"cookiePolicyUrl":"https://knowcrunch.com/data-privacy-policy", "banner":{ "acceptButtonDisplay":true,"acceptButtonColor":"#C8D151","acceptButtonCaptionColor":"white","position":"float-top-center","textColor":"black","backgroundColor":"white","customizeButtonDisplay":true,"customizeButtonColor":"#DADADA","customizeButtonCaptionColor":"#4D4D4D" }};
</script>
<script type="text/javascript" src="//cdn.iubenda.com/cs/iubenda_cs.js" charset="UTF-8" async></script>

<script type="text/javascript">

function OptanonWrapper() { }

</script>

@endif

{{-- Linkedin Code --}}
<script type="text/javascript">
  _linkedin_data_partner_id = "32143";
</script>
{{--<script type="text/javascript">
  (function(){var s = document.getElementsByTagName("script")[0];
  var b = document.createElement("script");
  b.type = "text/javascript";b.async = true;
  b.src = "https://snap.licdn.com/li.lms-analytics/insight.min.js";
  s.parentNode.insertBefore(b, s);})();
</script>--}}

<script type="text/javascript">
    var errorMessages = {
        requiredError: "Please fill in all mandatory data.",
        emailError: "Please enter a valid email address.",
    };
</script>

{{--<script src="https://js.stripe.com/v3/"></script>--}}

</head>

<body>


<!-- Load Facebook SDK for JavaScript -->

@yield('fbchat')


@if(!env('APP_DEBUG'))
{{-- Google Tag Manager (noscript) --}}
{{--<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-ML7649C"
height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>--}}
<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-ML7649C"
height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
{{-- End Google Tag Manager (noscript) --}}
@endif

<div class="page-wrapper non-pointer">
@include('theme.layouts.header')
@include('theme.layouts.mobile_menu')

@if (!Auth::check())

   

<div  class="login-popup-wrapper">



    <div id="login-popup" class="login-popup">
        <a href="#" class="close-btn"><img width="26" src="{{cdn('theme/assets/images/icons/icon-close.svg')}}" class="replace-with-svg" alt="Close"></a>
        <div class="heading">
            <span>Account login</span>
            <p>Access your courses, schedule & files.</p>
        </div>
        <div class="alert-outer" hidden>
					
						<div class="alert-wrapper error-alert">
							<div class="alert-inner">
								<p id="account-error"></p>
							{{--<a href="javascript:void(0)" class="close-alert"><img src="{{cdn('/theme/assets/images/icons/alert-icons/icon-close-alert.svg')}}" alt="Close Alert"/></a>--}}
							</div>
					</div>
				<!-- /.alert-outer -->
	    </div>
        <form autocomplete="off" class="login-form">
            <div class="input-wrapper input-wrapper--text input-wrapper--email">
                <span class="icon"><img width="14" src="{{cdn('/theme/assets/images/icons/icon-email.svg')}}" alt=""></span>
                <input type="text" placeholder="Email" id="email" autocomplete="off">
            </div>
            <div class="input-wrapper input-wrapper--text">
                <span class="icon"><img width="10" src="{{cdn('/theme/assets/images/icons/icon-lock.svg')}}" alt=""></span>
                <input type="password" placeholder="Password" id="password" autocomplete="off">
            </div>
            <div class="form-group">
                <label for="remember-me"><input id="remember-me" type="checkbox">Remember me</label>
                <a id="forgot-pass" href="javascript:void(0)">Forgot password?</a>
            </div>
            <input type="button" onclick="loginAjaxNew()" value="LOGIN">
        </form>
    </div><!-- ./login-popup -->

    <div id="forgot-pass-input" class="login-popup" hidden>
        <a href="#" class="close-btn"><img width="26" src="{{cdn('theme/assets/images/icons/icon-close.svg')}}" class="replace-with-svg" alt="Close"></a>
        <div class="heading">
        <span>Change your Password</span>
            <p>Use your account email to change your password</p>
        </div>
        {{--<form method="post" action="/myaccount/reset" autocomplete="off" class="validate-form change-password-form"> --}}
        <form autocomplete="off" class="login-form">
        {!!csrf_field()!!}

        <div id="error-mail" class="alert-outer" hidden>
					
                    <div class="alert-wrapper error-alert">
                        <div class="alert-inner">
                            <p id="message-error"></p>
                        </div>
                </div>
            <!-- /.alert-outer -->
        </div>

        <div id="success-mail" class="alert-outer" hidden>
                           <div class="container">
                              <div class="alert-wrapper success-alert">
                                 <div class="alert-inner">
                                 <p id="message-success"></p>                                   
                                </div>
                              </div>
                           </div>
                        <!-- /.alert-outer -->
                        </div>

            <div class="input-wrapper input-wrapper--text input-wrapper--email">
            <div class="input-safe-wrapper">	
                <span class="icon"><img width="14" src="{{cdn('/theme/assets/images/icons/icon-email.svg')}}" alt=""></span>
                <input type="email"  placeholder="Email" name="email" id="email-forgot" class="required"> 
            </div>
            </div>
           
            <button type="button" class="btn btn--lg btn--secondary change-password"  value="Change">Change</button>
        </form>
    </div><!-- ./login-popup -->
</div><!-- ./login-popup-wrapper -->
@endif

<!--<script src="{{ URL::to('/assets/colorbox/jquery.colorbox.js') }}">></script>-->
<div class="main_content_include">

    @yield('content')
    
</div>
{{--include('theme.home.partials.newsletter')--}}
@include('theme.layouts.footer')
<a href="#" class="go-top-btn"><i class="icon-up-open"></i></a>
</div>
@include('theme.layouts.footer_scripts')
@include('theme.layouts.flash_notifications')
@yield('scripts')


@if(!Auth::check() && request()->login)
    <script>
        $(document).ready(function(){
            $('li.account-menu a').click();
        });
    </script>
@endif

<script>


    $(document).on('click', '.change-password', function(e){
        $('#error-mail').hide()
        $('#success-mail').hide()
        //console.log($(".change-password-form").serialize());
        var email = document.getElementById('email-forgot').value
        
        if(email!=''){
        
            $.ajax({ url: '/myaccount/reset', type: "post",
                    
                    data: {"email": email},
                    success: function(data) {
                        
                        if(data['success']){
                            $('#success-mail').show()
                            var p = document.getElementById('message-success').textContent = data['message'];

                           /* setTimeout( function(){
								window.location.replace('/');
							}, 1000 );*/


                        }else{
                            $('#error-mail').show()
                            var p = document.getElementById('message-error').textContent = data['message'];

                        }
                    },
                
                });
        }

    })

</script>


<script>
$(document).on('click', '#forgot-pass', function(e){
    $('#login-popup').hide()
    $('#forgot-pass-input').show()    
})

$(document).on('click', '.close-btn', function(e){
    $('#login-popup').show()
    $('#forgot-pass-input').hide()    
})

</script>

@if(Auth::user() && !env('APP_DEBUG'))
    <script>
        dataLayer.push({"User_id": "{{Auth::user()->id}}"})
    </script>

@elseif(!env('APP_DEBUG'))
    <script>
        dataLayer.push({'Visitor_id': "{{session()->getId()}}"});
    </script>
@endif

@if(isset($tigran) && !env('APP_DEBUG'))

      <script>

         @foreach($tigran as $key => $ti)
            dataLayer.push({"{{$key}}": "{{$ti}}"})
         @endforeach

      </script>

   @endif

</body>
</html>
