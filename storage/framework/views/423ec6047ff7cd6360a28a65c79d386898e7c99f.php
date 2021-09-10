<!doctype html>
<html lang="en" class="no-js">
<head>
<base href="<?php echo URL::to('/'); ?>/" target="_self" />
<meta charset="UTF-8">
<meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name=viewport content="width=device-width, initial-scale=1">

<?php if(isset($meta) && isset($meta['header_code'])): ?>
<?php echo $meta['header_code']; ?>

<?php endif; ?>


<?php echo $__env->make('theme.layouts.favicons', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php echo $__env->yieldContent('header'); ?>
<?php echo $__env->make('theme.layouts.header_scripts', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>


<?php if(!env('APP_DEBUG')): ?>


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

<script>
setTimeout(function(){

!function(f,b,e,v,n,t,s){if(f.fbq)return;n=f.fbq=function(){n.callMethod?
n.callMethod.apply(n,arguments):n.queue.push(arguments)};if(!f._fbq)f._fbq=n;
n.push=n;n.loaded=!0;n.version='2.0';n.queue=[];t=b.createElement(e);t.async=!0;
t.src=v;s=b.getElementsByTagName(e)[0];s.parentNode.insertBefore(t,s)}(window,
document,'script','https://connect.facebook.net/en_US/fbevents.js');
fbq('init', '1745172385734431'); // Insert your pixel ID here.
fbq('track', 'PageView');



}, 500);
</script>
<noscript><img height="1" width="1" style="display:none" alt="fb pixel" title="fb pixel"
src="https://www.facebook.com/tr?id=1745172385734431&amp;ev=PageView&amp;noscript=1"
/></noscript>



<?php endif; ?>
<script type="text/javascript">
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
</script>

<!-- OneTrust Cookies Consent Notice (Production CDN, knowcrunch.com, en-GB) start -->
<?php if(Agent::isDesktop()): ?>




<script type="text/javascript">
var _iub = _iub || [];
_iub.csConfiguration = {"whitelabel":false,"lang":"en","siteId":1874776,"cookiePolicyInOtherWindow":true,"consentOnDocument":true,"perPurposeConsent":true,"consentOnHorizontalScroll":true,"cookiePolicyId":76147833,"cookiePolicyUrl":"https://knowcrunch.com/data-privacy-policy", "banner":{ "acceptButtonDisplay":true,"acceptButtonColor":"#C8D151","acceptButtonCaptionColor":"white","position":"float-top-center","textColor":"black","backgroundColor":"white","customizeButtonDisplay":true,"customizeButtonColor":"#DADADA","customizeButtonCaptionColor":"#4D4D4D" }};
</script>
<script type="text/javascript" src="//cdn.iubenda.com/cs/iubenda_cs.js" charset="UTF-8" async></script>

<script type="text/javascript">

function OptanonWrapper() { }

</script>
<?php endif; ?>


<script type="text/javascript">
  _linkedin_data_partner_id = "32143";
</script>

</head>
<body>
<!-- Load Facebook SDK for JavaScript -->
<div id="fb-root"></div>

<?php if(Agent::isDesktop()): ?>
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
<?php endif; ?>

<?php if(!env('APP_DEBUG')): ?>


<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-ML7649C"
height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>

<?php endif; ?>
<div class="page-wrapper">
<?php echo $__env->make('theme.layouts.header_consent', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php echo $__env->make('theme.layouts.mobile_menu', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<div class="main_content_include">
    <?php echo $__env->yieldContent('content'); ?>
</div>
</div>


<?php echo $__env->make('theme.layouts.footer_scripts', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

<?php echo $__env->yieldContent('scripts'); ?>

</body>
</html>
<?php /**PATH C:\laragon\www\kcversion8\resources\views/theme/layouts/master_consent.blade.php ENDPATH**/ ?>