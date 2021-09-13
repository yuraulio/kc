



<!-- <link rel="stylesheet" href="https://use.typekit.net/pfo3bjs.css"> -->
<link href="<?php echo e(cdn(mix('theme/assets/css/style_ver.css'))); ?>" rel="stylesheet" media="all" />
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

<script type="text/javascript" src="<?php echo e(cdn('theme/assets/js/jquery-2.1.4.min.js')); ?>"></script>
<script type="text/javascript">
var routesObj = {
    baseUrl : '<?php echo e(URL::to("/")); ?>/'
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



<?php // echo $frontHelp->activeSplash(); ?>
<?php /**PATH C:\laragon\www\kcversion8\resources\views/theme/layouts/header_scripts.blade.php ENDPATH**/ ?>