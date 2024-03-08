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

@if(config('app.debug'))
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


<script>
  window.dataLayer = window.dataLayer || [];
  function gtag() {
      dataLayer.push(arguments);
  }
  gtag("consent", "default", {
      ad_storage: "denied",
      ad_user_data: "denied",
      ad_personalization: "denied",
      analytics_storage: "denied",
      functionality_storage: "denied",
      personalization_storage: "denied",
      security_storage: "granted",
      wait_for_update: 2000,
  });
  gtag("set", "ads_data_redaction", true);
  gtag("set", "url_passthrough", true);
</script>

@if(!config('app.debug'))
<!-- NEW Google Tag Manager -->
<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
	new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
	j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
	'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
	})(window,document,'script','dataLayer','GTM-ML7649C');
</script>

{{--<script async src="https://www.googletagmanager.com/gtag/js?id=AW-859787100"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'AW-859787100');
</script>--}}
@endif
<!-- End Google Tag Manager -->

<!-- Start cookieyes banner --> <script id="cookieyes" type="text/javascript" src="https://cdn-cookieyes.com/client_data/2428d5ba225ff1e2703356e8/script.js"></script> <!-- End cookieyes banner -->

<!-- OneTrust Cookies Consent Notice (Production CDN, knowcrunch.com, en-GB) start -->
@if(Agent::isDesktop())

{{--<script type="text/javascript">
var _iub = _iub || [];
_iub.csConfiguration = {"cookiePolicyInOtherWindow":true,"perPurposeConsent":true,"whitelabel":false,"lang":"en","siteId":1874776,"countryDetection":true,"gdprAppliesGlobally":false,"consentOnDocument":true,"cookiePolicyId":76147833,"cookiePolicyUrl":"https://knowcrunch.com/cookies-notice", "banner":{ "acceptButtonDisplay":true,"customizeButtonDisplay":true,"acceptButtonColor":"#c8d151","acceptButtonCaptionColor":"#010000","customizeButtonColor":"#DADADA","customizeButtonCaptionColor":"#4D4D4D","position":"float-top-center","textColor":"black","backgroundColor":"white","rejectButtonColor":"#0073CE","rejectButtonCaptionColor":"white" }};
</script>
<script type="text/javascript" src="//cdn.iubenda.com/cs/iubenda_cs.js" charset="UTF-8" async></script>--}}


{{--<script type="text/javascript">
var _iub = _iub || [];
_iub.csConfiguration = {"askConsentAtCookiePolicyUpdate":true,"consentOnContinuedBrowsing":false,"countryDetection":true,"enableLgpd":true,"enableUspr":true,"floatingPreferencesButtonDisplay":"bottom-right","invalidateConsentWithoutLog":true,"lang":"en","lgpdAppliesGlobally":false,"perPurposeConsent":true,"siteId":2409873,"whitelabel":false,"cookiePolicyId":41154288,"cookiePolicyUrl":"https://knowcrunch.com/cookies-notice","privacyPolicyUrl":"https://knowcrunch.com/data-privacy-policy","privacyPolicyNoticeAtCollectionUrl":"https://knowcrunch.com/terms-and-conditions", "banner":{ "acceptButtonColor":"#40CE00","acceptButtonDisplay":true,"closeButtonDisplay":false,"customizeButtonDisplay":true,"explicitWithdrawal":true,"listPurposes":true,"position":"float-top-center","rejectButtonColor":"#C8CFD6","rejectButtonDisplay":true }};
</script>
<script type="text/javascript" src="//cdn.iubenda.com/cs/gpp/stub.js"></script>
<script type="text/javascript" src="//cdn.iubenda.com/cs/iubenda_cs.js" charset="UTF-8" async></script>--}}

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

{{--<script type="text/javascript">
    var errorMessages = {
        requiredError: "Please fill in all mandatory data.",
        emailError: "Please enter a valid email address.",
    };
</script>--}}

{{--<script src="https://js.stripe.com/v3/"></script>--}}

@if(!config('app.debug'))
{{--<script id="Cookiebot" src="https://consent.cookiebot.com/uc.js" data-cbid="7c5a111b-df1a-4a4a-bd31-fbee0d6593ba" data-blockingmode="auto" type="text/javascript"></script>--}}
@endif

@yield('blog-custom-css')

</head>
<body>
@include('theme.layouts.menu_component_myaccount')

@if(!config('app.debug'))
{{--<script id="CookieDeclaration" src="https://consent.cookiebot.com/7c5a111b-df1a-4a4a-bd31-fbee0d6593ba/cd.js" type="text/javascript" async></script>--}}
@endif

<!-- Load Facebook SDK for JavaScript -->

@yield('fbchat')


@if(!config('app.debug'))
{{-- Google Tag Manager (noscript) --}}
{{--<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-ML7649C"
height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>--}}
<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-ML7649C"
height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
{{-- End Google Tag Manager (noscript) --}}
@elseif(config('app.env') == "development")
  <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-MLLXRGTK"
    height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
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
            <input type="button" onclick="loginAjaxNew()" value="LOGIN">
        </form>
    </div><!-- ./login-popup -->

    <div id="forgot-pass-input" class="login-popup" hidden>
        <a href="#" class="close-btn"><img width="26" src="{{cdn('theme/assets/images/icons/icon-close.svg')}}" class="replace-with-svg" alt="Close"></a>
        <div class="heading">
        <span>Password</span>
            <p>Use your account email to create or change your password.</p>
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

            <button type="button" class="btn btn--lg btn--secondary change-password"  value="Change">Create / Change</button>
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


@if(strtotime(date('Y-m-d')) == strtotime(env('BLACKFRIDAY')))
<script src="{{cdn('theme/assets/blackfriday/blackfriday.js')}}"> </script>
@endif

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

@if(Auth::user() && !config('app.debug'))
    <script>
        dataLayer.push({"User_id": "{{Auth::user()->id}}"})
    </script>

@elseif(!config('app.debug'))
    <script>
        dataLayer.push({'Visitor_id': "{{session()->getId()}}"});
    </script>
@endif



  @if(isset($tigran) && config('app.debug'))
    <script>
        $(document).ready(function(){
           @foreach($tigran as $key => $ti)
              dataLayer.push({"{{$key}}": $.parseHTML("{{$ti}}")[0].data})
           @endforeach
        });
    </script>
  @endif
  <script>

    function switchTabsByHash() {
        let hash = window.location.hash.slice(1);
        if(hash){
          hash = hash.split("?")[0]
          let target = "#" + hash;
          let activeTab = $('.tab-controls .active');
          let self = $('a[href="'+ target + '"]');

          if (activeTab.length && self.length && activeTab.get(0) !== self.get(0)) {
            $('.active-tab').removeClass('active-tab');
            activeTab.toggleClass('active');
            self.toggleClass('active');
            self.next('.tab-controls-list').slideToggle(300);

            let targetEl = $(target).first();
            $(this).addClass('active');
            if (targetEl.length) {
              targetEl.addClass('active-tab');
              $('html, body').animate({
                scrollTop: targetEl.offset().top - Math.round($('#header').outerHeight()) - 1
              }, 300);
            }
          }
        }
    }
    switchTabsByHash();
    $(window).on('hashchange',function(){
      switchTabsByHash();
    });
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

@yield('blog-custom-js')
</body>
</html>
