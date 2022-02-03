{{--<link rel="icon" href="{{ cdn('theme/assets/img/icon/fav_icon.gif') }}">
<link type="text/css" rel="stylesheet" href="{{ cdn('theme/assets/bootstrap/css/bootstrap.min.css') }}">
<link type="text/css" rel="stylesheet" href="{!! URL::to('/') !!}/theme/assets/css/font-awesome.min.css">
<link type="text/css" rel="stylesheet" href="assets/css/ionicons.min.css" />
<link type="text/css" rel="stylesheet" href="assets/css/popup.css" />
<link type="text/css" rel="stylesheet" href="assets/css/owl.carousel.css" />
<link type="text/css" rel="stylesheet" href="assets/css/owl.theme.css" />
<link type="text/css" rel="stylesheet" href="{!! URL::to('/') !!}/theme/assets/css/style.css" />
<link type="text/css" rel="stylesheet" href="{{ mix('theme/assets/css/style.css') }}" media="all" />
<!--<link href="{{ cdn(elixir('theme/assets/css/style_ver.css')) }}" rel="stylesheet" media="all" />
<link type="text/css" rel="stylesheet" href="{{ cdn('theme/assets/css/mobile_style_v2.css') }}" />-->
<link type="text/css" rel="stylesheet" href="{{ cdn('theme/assets/css/dpanimate.css') }}" />
<link type="text/css" rel="stylesheet" href="{{ cdn('theme/assets/css/magnific-popup.css') }}" />
<link type="text/css" rel="stylesheet" href="{{ cdn('theme/assets/css/jquery.bootstrap-touchspin.min.css') }}" />
<link type="text/css" rel="stylesheet" href="{{ cdn('theme/assets/css/bootstrap-select.min.css') }}" />--}}

{{--<link rel="stylesheet" href="theme/assets/css/old.css" />
<link rel="stylesheet" href="{{ cdn('theme/assets/css/normalize.css') }}" />
<link rel="stylesheet" href="{{cdn('theme/assets/css/jquery.mCustomScrollbar.css')}}" />
<link rel="stylesheet" href="{{cdn('theme/assets/css/jquery-ui.css')}}">
<link rel="stylesheet" href="{{ cdn('theme/assets/css/grid.css') }}" />
<link rel="stylesheet" href="{{ cdn('theme/assets/css/grid-flex.css') }}" />
<link rel="stylesheet" href="{{ cdn('theme/assets/css/global.css') }}" />
<link rel="stylesheet" href="{{ cdn('theme/assets/css/main.css') }}" />
<link rel="stylesheet" href="{{ cdn('theme/assets/css/fontawesome/css/kcfonts.css') }}" />--}}

<!-- <link rel="stylesheet" href="https://use.typekit.net/pfo3bjs.css"> -->

<link href="{{ cdn(mix('theme/assets/css/style_ver_new.css')) }}" rel="stylesheet" media="all" />

<link href="{{ cdn(mix('theme/assets/css/bootstrap5-grid.css')) }}" rel="stylesheet" media="all" />

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
          "name": "KnowCrunch Inc",
          "logo": "/theme/assets/images/logo.png"
        }
</script>

<script type="text/javascript" src="{{ cdn('theme/assets/js/jquery-2.1.4.min.js') }}"></script>
<script type="text/javascript">
var routesObj = {
    baseUrl : '{{ URL::to("/") }}/'
};

$.ajaxSetup({headers:{'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}});
    function notify(message, type, timeout) {
        //console.log(message, type, timeout);
        // default values
        message = typeof message !== 'undefined' ? message : 'Hello!';
        type = typeof type !== 'undefined' ? type : 'success';
        timeout = typeof timeout !== 'undefined' ? timeout : 3000;

        // append markup if it doesn't already exist
        if ($('#notification').length < 1) {
            markup = '<div id="notification" class="information"><span>Hello!</span><a class="close" href="#">x</a></div>';
            $('body').append(markup);
        }

        // elements
        $notification = $('#notification');
        $notificationSpan = $('#notification span');
        $notificationClose = $('#notification a.close');

        // set the message
        $notificationSpan.text(message);

        // setup click event
        $notificationClose.click(function (e) {
            e.preventDefault();
            $notification.css('top', '-70px');
        });

        // hide old notification, then show the new notification
        $notification.css('top', '-70px').stop().removeClass().addClass(type).animate({
            top: 0
        }, 500, function() {
            $notification.delay(timeout).animate({
                top: '-70px'
            }, 500);
        });
    }

</script>

<script>





function getCookie(c_name) {
    var c_value = document.cookie,
        c_start = c_value.indexOf(" " + c_name + "=");
    if (c_start == -1) c_start = c_value.indexOf(c_name + "=");
    if (c_start == -1) {
        c_value = null;
    } else {
        c_start = c_value.indexOf("=", c_start) + 1;
        var c_end = c_value.indexOf(";", c_start);
        if (c_end == -1) {
            c_end = c_value.length;
        }
        c_value = unescape(c_value.substring(c_start, c_end));
    }
    return c_value;
}




</script>

