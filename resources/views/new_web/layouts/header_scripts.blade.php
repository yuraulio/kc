<link href="{{ cdn(mix('theme/assets/css/style_ver_new.css')) }}" rel="stylesheet" media="all" />

<link href="{{ cdn(mix('theme/assets/css/bootstrap5-grid.css')) }}" rel="stylesheet" media="all" />
<!-- <link href="{{ cdn('theme/assets/css/carouselTicker.css') }}" rel="stylesheet" media="all" /> -->

<script type="text/javascript" src="{{ cdn('theme/assets/js/jquery-2.1.4.min.js') }}"></script>
<!-- <script type="text/javascript" src="{{ cdn('theme/assets/js/jquery.carouselTicker.js') }}"></script> -->

<script type="text/javascript">
    var routesObj = {
        baseUrl : '{{ URL::to("/") }}/'
    };

    $.ajaxSetup({headers:{'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}});
    function notify(message, type, timeout) {
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

