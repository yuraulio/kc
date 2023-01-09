<!doctype html>
<html lang="en" class="no-js">
<head>
<base href="{!! URL::to('/') !!}/" target="_self" />
<meta charset="UTF-8">
<meta name="csrf-token" content="{{ csrf_token() }}">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name=viewport content="width=device-width, initial-scale=1">
{{-- title and favicons --}}
@if (isset($meta) && isset($meta['header_code']))
{!! $meta['header_code'] !!}
@endif
{{--<link rel="icon" href="theme/assets/img/icon/fav_icon.gif">--}}
{{-- necessary stylesheets --}}
@include('theme.layouts.favicons')
@yield('header')
@include('theme.layouts.header_scripts')


@if(!env('APP_DEBUG'))

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

  gtag('config', 'AW-859787100');--}}
</script>--}}

@endif

<!-- OneTrust Cookies Consent Notice (Production CDN, knowcrunch.com, en-GB) start -->
@if(Agent::isDesktop())

{{--<script src="https://cdn.cookielaw.org/consent/db838fc2-3cee-42e6-9af7-4c6f2fc126b0.js" type="text/javascript" charset="UTF-8"></script>--}}

{{--<script type="text/javascript">
var _iub = _iub || [];
_iub.csConfiguration = {"whitelabel":false,"lang":"en","siteId":1874776,"cookiePolicyInOtherWindow":true,"consentOnDocument":true,"perPurposeConsent":true,"consentOnHorizontalScroll":true,"cookiePolicyId":76147833,"cookiePolicyUrl":"https://knowcrunch.com/data-privacy-policy", "banner":{ "acceptButtonDisplay":true,"acceptButtonColor":"#C8D151","acceptButtonCaptionColor":"white","position":"float-top-center","textColor":"black","backgroundColor":"white","customizeButtonDisplay":true,"customizeButtonColor":"#DADADA","customizeButtonCaptionColor":"#4D4D4D" }};
</script>
<script type="text/javascript" src="//cdn.iubenda.com/cs/iubenda_cs.js" charset="UTF-8" async></script>

<script type="text/javascript">

function OptanonWrapper() { }

</script>--}}

{{--<script type="text/javascript">
var _iub = _iub || [];
_iub.csConfiguration = {"cookiePolicyInOtherWindow":true,"perPurposeConsent":true,"whitelabel":false,"lang":"en","siteId":1874776,"countryDetection":true,"gdprAppliesGlobally":false,"consentOnDocument":true,"cookiePolicyId":76147833,"cookiePolicyUrl":"https://knowcrunch.com/cookies-notice", "banner":{ "acceptButtonDisplay":true,"customizeButtonDisplay":true,"acceptButtonColor":"#c8d151","acceptButtonCaptionColor":"#010000","customizeButtonColor":"#DADADA","customizeButtonCaptionColor":"#4D4D4D","position":"float-top-center","textColor":"black","backgroundColor":"white","rejectButtonColor":"#0073CE","rejectButtonCaptionColor":"white" }};
</script>
<script type="text/javascript" src="//cdn.iubenda.com/cs/iubenda_cs.js" charset="UTF-8" async></script>--}}

<script type="text/javascript">
var _iub = _iub || [];
_iub.csConfiguration = {"askConsentAtCookiePolicyUpdate":true,"consentOnContinuedBrowsing":false,"countryDetection":true,"enableLgpd":true,"enableUspr":true,"floatingPreferencesButtonDisplay":"bottom-right","invalidateConsentWithoutLog":true,"lang":"en","lgpdAppliesGlobally":false,"perPurposeConsent":true,"siteId":2409873,"whitelabel":false,"cookiePolicyId":41154288,"cookiePolicyUrl":"https://knowcrunch.com/cookies-notice","privacyPolicyUrl":"https://knowcrunch.com/data-privacy-policy","privacyPolicyNoticeAtCollectionUrl":"https://knowcrunch.com/terms-and-conditions", "banner":{ "acceptButtonColor":"#40CE00","acceptButtonDisplay":true,"closeButtonDisplay":false,"customizeButtonDisplay":true,"explicitWithdrawal":true,"listPurposes":true,"position":"float-top-center","rejectButtonColor":"#C8CFD6","rejectButtonDisplay":true }};
</script>
<script type="text/javascript" src="//cdn.iubenda.com/cs/gpp/stub.js"></script>
<script type="text/javascript" src="//cdn.iubenda.com/cs/iubenda_cs.js" charset="UTF-8" async></script>

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
</head>
<body>
<!-- Load Facebook SDK for JavaScript -->
<div id="fb-root"></div>

@if(Agent::isDesktop())
<script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = 'https://connect.facebook.net/en_US/sdk/xfbml.customerchat.js#xfbml=1&version=v2.12';
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script>

<!-- Your customer chat code -->
<div class="fb-customerchat"
  attribution="setup_tool"
  page_id="486868751386439">
</div>
@endif

@if(!env('APP_DEBUG'))
{{-- Google Tag Manager (noscript) --}}
{{--<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-ML7649C"
height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>--}}
<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-ML7649C"
height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
{{-- End Google Tag Manager (noscript) --}}
@endif
<div class="page-wrapper">
@include('theme.layouts.header_consent')
@include('theme.layouts.mobile_menu')
<div class="main_content_include">
    @yield('content')
</div>
</div>
{{--include('theme.home.partials.newsletter')--}}
{{--include('theme.layouts.footer_consent')--}}
@include('theme.layouts.footer_scripts')
{{--include('theme.layouts.flash_notifications')--}}
@yield('scripts')

</body>
</html>
