@extends('theme.layouts.master')
@section('header')

<!-- <link rel="stylesheet" href="theme/assets/css/owl.carousel.css">
<link rel="stylesheet" href="theme/assets/css/owl.theme.css"> -->
<link rel="stylesheet" href="theme/assets/css/owl.carousel.min.css">
<link rel="stylesheet" href="theme/assets/css/owl.theme.default.min.css">
<link rel="stylesheet" href="theme/assets/css/transitions.css">
<link type="text/css" rel="stylesheet" href="theme/assets/addons/slider/css/swiper.css">
@stop
@inject('frontHelp', 'Library\FrontendHelperLib')

@section('content')
<?php $cart = Cart::content(); ?>
@if(isset($content['c_fields']['dropdown_select_status']['value']))

<?php $estatus = $content['c_fields']['dropdown_select_status']['value']; ?>

@else
<?php $estatus = 0; ?>
@endif

@include('theme.event.partials.banner')
@if($estatus == 0 || $estatus == 2)
@include('theme.event.partials.social')
@else
<div class="social-helper"></div>
@endif

<div id="main-content-body">


<!-- single-event-page -->
    <div id="single_post">

    	@if($estatus == 0 || $estatus == 2)
            @include('theme.event.partials.header')
            @include('theme.event.partials.summary')
            @if(isset($event_blocks_priorities))
	            <?php ksort($event_blocks_priorities); ?>
	            @foreach($event_blocks_priorities as $key => $block)
	            	<?php $loadBladeForSection = 'theme.event.partials.'.$block; ?>
	            	@include( $loadBladeForSection )
	            @endforeach
            @endif

            <!-- event_blocks_priorities -->
           <!--  include('theme.event.partials.topics')
            include('theme.event.partials.syllabus-manager')
            include('theme.event.partials.instructors')
            include('theme.event.partials.qnas')
            include('theme.event.partials.gallery')
            include('theme.event.partials.organisers')
            include('theme.event.partials.awarded')
            include('theme.event.partials.testimonials')
            include('theme.event.partials.location')
            include('theme.event.partials.seats')
            include('theme.event.partials.contact') -->
        @else
        	@include('theme.event.partials.header')
            <div class="status-helper"></div>


            @include('theme.event.partials.topics')

            @include('theme.event.partials.instructors')


        @endif

    </div>


    <div id="extramenu">
      <div class="container">
        <div class="row">
            <div id="nav-anchor"></div>
            <div id="vnav" >

                <ul class="@if($estatus != 0) sth @endif">
                    <li><a title="About" href="#event-banner">About</a></li>
                    @if($estatus == 0 || $estatus == 2)
                    <li><a title="Summary" href="#section-summary">Summary</a></li>

                    <!-- <li class="hidden-xs hidden-sm"><a title="Testimonials" href="#section-testimonials">Testimonials</a></li> -->
                    @endif

                    @if(isset($event_menu_priorities))
			            <?php ksort($event_menu_priorities); ?>
			            @foreach($event_menu_priorities as $key => $block)
			            	{{ $block }}
			            @endforeach
		            @endif

                    @if(isset($section_topics))
                    <li><a title="Topics" href="#section-topics">Topics</a></li>
                    @endif

                    @if(isset($section_instructors))
                    <li><a title="Instructors" href="#section-instructors">Instructors</a></li>
                    @endif

                    @if($estatus == 0 || $estatus == 2)
                    	@if(isset($section_qnas))
                    	<li class="hidden-xs hidden-sm"><a title="QnAs" href="#section-qnas">QnAs</a></li>
                    	@endif
                    	@if(isset($section_gallery))
                    	<li class="hidden-xs hidden-sm"><a title="Gallery" href="#section-gallery">Gallery</a></li>
                    	@endif
                    @endif
                    <!-- <li><a title="Poweredby" href="#section-organisers">Powered-by</a></li> -->
                    @if($estatus == 0 || $estatus == 2)
                    	@if(isset($section_testimonials))
                    	<li class="hidden-xs hidden-sm"><a title="Testimonials" href="#section-testimonials">Testimonials</a></li>
                    	@endif
                    	@if(isset($section_location))
                    	<li><a title="Location" href="#section-location"><span class="hidden-xs">Location</span><span class="hidden-lg hidden-md hidden-sm"><i class="fa fa-map-marker" aria-hidden="true"></i></span></a></li>
                    	@endif
                    <!-- <li><a title="Tickets" href="#section-seats">Tickets</a></li> -->
	                    @if(isset($section_contact))
	                    <li><a title="Contact" href="#section-contact"><span class="hidden-xs">Contact</span><span class="hidden-lg hidden-md hidden-sm"><i class="fa fa-envelope" aria-hidden="true"></i></span></a></li>
	                    @endif
                    @endif

                </ul>
                @if($estatus == 0)
                <div class="cta-inmenu"><a href="#section-seats" title="ENROLL NOW" id="cta"><span class="btn btn-calltoaction">BOOK NOW</span></a></div>
                @endif

            </div>
        </div>
      </div>
    </div>

    <!-- single-event-page END -->
</div>
<div class="loadmodal"><!-- Place at bottom of page --></div>
@endsection
@section('scripts')
<script src="theme/assets/js/cart.js"></script>
<script src="theme/assets/js/owl.carousel.min.js"></script>
<script type="text/javascript" src="theme/assets/addons/slider/js/swiper.min.js"></script>

<script type="text/javascript">

$(document).ready(function () {
    //initialize swiper when document ready
    var width = $(window).width();
    if (width < 768) {
        var mySwiper = new Swiper ('.swiper-container', {
          // Optional parameters

          pagination: '.swiper-pagination',
            slidesPerView: 1.2,
            centeredSlides: true,
            paginationClickable: true,
            spaceBetween: 14,
            simulateTouch: true,
            autoplay: 2000,
            autoplayDisableOnInteraction: true

        });


        /*,
            breakpoints: {
			    // when window width is <= 320px
			    768: {
			      slidesPerView: 1,
			      spaceBetween: 14
			    },
			    // when window width is <= 480px
			    1024: {
			      slidesPerView: 3,
			      spaceBetween: 14
			    },
			    // when window width is <= 640px
			    2048: {
			      slidesPerView: 5,
			      spaceBetween: 14
			    }
			  }*/



        /*$( '#dl-menu' ).dlmenu({
        animationClasses : { classin : 'dl-animate-in-4', classout : 'dl-animate-out-4' }
        });*/
    }


  });
            /*autoPlaySpeed: 5000,
            autoPlayTimeout: 5000,*/
    $(document).ready(function() {

        //testimonials carousel
        //var owl_home = $("#owl-home");
        var owl = $("#owl-testimonials");
       // var owl_images = $("#owl-testimonials-images");
        var owl_gallery_images = $("#owl-gallery-images");



        owl.owlCarousel({
            autoplay: false,
            loop:true,
            slideBy: 2,
            mouseDrag: false,
            touchDrag: true,
            responsive : {
			    // breakpoint from 0 up
			    0 : {
			        items: 1,
			    },
			    // breakpoint from 480 up
			    480 : {
			        items: 1,
			    },
			    // breakpoint from 768 up
			    768 : {
			        items: 2,
			    }
			}

        });

        /*var checkWidth = $(document).width();

        if(checkWidth <= 600){
        		var seats = $("#seatscarousel");
			    seats.owlCarousel({
	            autoplay: false,
	            nav: false,
	            margin: 20,
	            items: 1,
	            stagePadding: 20,
	            mouseDrag: true,
	            touchDrag: true,
	            loop:false
	        });
		}*/
		//	            center: true,
        /*owl_images.owlCarousel({
            autoplay: true,
            //animateOut: 'owl-fade-out',
            //animateIn: 'owl-fade-in',
            items: 1,
            mouseDrag: false,
            touchDrag: false,
            loop:true
        });*/

        $('#testimonials_carousel_next').on("click",function(){
            owl.trigger('next.owl.carousel');
            //owl_images.trigger('next.owl.carousel');
        });
        $('#testimonials_carousel_back').on("click",function(){
            owl.trigger('prev.owl.carousel');
            //owl_images.trigger('prev.owl.carousel');
        });


        /*owl_gallery_images.owlCarousel({
            autoPlay: false,
            autoplayHoverPause: true,
            singleItem : true,
            pagination: false,
            slideSpeed: 2000,
            animateOut: 'fadeOut',
            mouseDrag: false,
            touchDrag: false
        });*/

        owl_gallery_images.owlCarousel({
            items: 1,
            loop:true
        });


        $('#gallery_carousel_next').on("click",function(){
           // owl.trigger('owl.next');
            owl_gallery_images.trigger('next.owl.carousel');
        });
        $('#gallery_carousel_back').on("click",function(){
            //owl.trigger('owl.prev');
            owl_gallery_images.trigger('prev.owl.carousel');
        });


    });
</script>
<script type="text/javascript">
$(document).ready(function() {
        $('.popup').magnificPopup({
            // child items selector, by clicking on it popup will open  delegate: 'a',
            type: 'image',
            fixedContentPos: false,
            // other options
            gallery: {
                enabled:true
            }
        });

    });


</script>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyB165ZXH0MizYqAzFNGqle5WwnfJ6WBx_g&amp;callback=initMap"
        async defer></script>
<script>


      function initMap() {
      	var no = false;
      	if(document.getElementById("lat") && document.getElementById("lon")) {

      		var lat = Number(document.getElementById("lat").innerHTML);
      		var lng = Number(document.getElementById("lon").innerHTML);
          var tit = document.getElementById("map_helper").innerHTML;


      		no = true;
      	}
      	else {
      		//alert('Noooo');
      		no = false;
      	}


      	//alert(lat+'|'+lng);
      	if (no) {

        var myLatLng = {lat: lat, lng: lng};
        var iconBase = '{{ url('/theme/assets/img') }}';

        var contentString = '<div id="mcontent">'+
      '<h3>'+tit+'</h3>'+
      '</div>';
      //var contentString = '';
        // Create a map object and specify the DOM element for display.
        var map = new google.maps.Map(document.getElementById('map_container'), {
          center: myLatLng,
          scrollwheel: false,
          zoom: 16,
          scrollwheel: false,
          navigationControl: true,
          mapTypeControl: false,
          scaleControl: true,
          draggable: true,

    /*styles: [
              {
                  "featureType": "administrative",
                  "elementType": "labels.text.fill",
                  "stylers": [
                      {
                          "color": "#444444"
                      }
                  ]
              },
              {
                  "featureType": "landscape",
                  "elementType": "all",
                  "stylers": [
                      {
                          "color": "#f2f2f2"
                      }
                  ]
              },
              {
                  "featureType": "poi",
                  "elementType": "all",
                  "stylers": [
                      {
                          "visibility": "off"
                      }
                  ]
              },
              {
                  "featureType": "road",
                  "elementType": "all",
                  "stylers": [
                      {
                          "saturation": -100
                      },
                      {
                          "lightness": 45
                      }
                  ]
              },
              {
                  "featureType": "road.highway",
                  "elementType": "all",
                  "stylers": [
                      {
                          "visibility": "simplified"
                      }
                  ]
              },
              {
                  "featureType": "road.arterial",
                  "elementType": "labels.icon",
                  "stylers": [
                      {
                          "visibility": "off"
                      }
                  ]
              },
              {
                  "featureType": "transit",
                  "elementType": "all",
                  "stylers": [
                      {
                          "visibility": "off"
                      }
                  ]
              },
              {
                  "featureType": "water",
                  "elementType": "all",
                  "stylers": [
                      {
                          "color": "#46bcec"
                      },
                      {
                          "visibility": "on"
                      }
                  ]
              }
          ]*/
        });

        // Create a marker and set its position.
        var marker = new google.maps.Marker({
          map: map,
          position: myLatLng,
          title: 'Event Venue Location!',
          icon: iconBase + '/map-marker-sm.png'
        });

        var infowindow = new google.maps.InfoWindow({
		      content: contentString,
		       position: myLatLng
		  });

        infowindow.open(map, marker);
      }
     }



$(document).ready(function() {

  $(".toggle-accordion").on("click", function() {
    var accordionId = $(this).attr("accordion-id"),
      numPanelOpen = $(accordionId + ' .collapse.in').length;
    $(this).toggleClass("active");

    if ($(this).hasClass("active") || numPanelOpen == 0) {
      openAllPanels(accordionId);
      $(this).html('-');

    } else {
      closeAllPanels(accordionId);
      $(this).html('+');
    }
  })

  openAllPanels = function(aId) {
    //console.log("setAllPanelOpen");
    $(aId + ' .collapse').each(function(index) {
      $(this).collapse('show');

    });

  }
  closeAllPanels = function(aId) {
    //console.log("setAllPanelclose");
    $(aId + ' .collapse.in').each(function(index) {
      $(this).collapse('hide');
    });
  }

});

    </script>



<script src="theme/assets/js/jquery.scrollto.js"></script>
    <script>



    $(document).ready(function(){

        /**
         * This part does the "fixed navigation after scroll" functionality
         * We use the jQuery function scroll() to recalculate our variables as the
         * page is scrolled/
         */
        $(window).scroll(function(){
            var window_top = $(window).scrollTop();
            //var div_top = $('#main-content-body').offset().top;
                if (window_top > 100) {
                	//var is = $('#vnav').is(":visible");
                	/*if (!is) {
                		setTimeout(function() {
                			$('[data-toggle="tooltip"]').tooltip("show");
    				}, 600);

                		setTimeout(function() {
                			$('[data-toggle="tooltip"]').tooltip("hide");
    				}, 5000);
                	}*/

                	$('#extramenu').animate({opacity: 'show', height: 'show'}, 'slow');
                    //$('#extramenu').slideDown(); //show();


                } else {
                    //$('#extramenu').slideUp(); //.hide();
                    $('#extramenu').animate({opacity: 'hide', height: 'hide'}, 'slow');

                }
        });

        /*$('[data-toggle="tooltip"]').find(".tooltip.fade.top").removeClass("in"); */
        /**
         * This part causes smooth scrolling using scrollto.js
         * We target all a tags inside the nav, and apply the scrollto.js to it.
         */
        $("#vnav a").click(function(evn){
            evn.preventDefault();
            $('html,body').scrollTo(this.hash, this.hash);
        });

        $("a#cta").click(function(evn){
            evn.preventDefault();
            $('html,body').scrollTo(this.hash, this.hash);
        });



        /**
         * This part handles the highlighting functionality.
         * We use the scroll functionality again, some array creation and
         * manipulation, class adding and class removing, and conditional testing
         */
        var aChildren = $("#vnav li").children(); // find the a children of the list items
        var aArray = []; // create the empty aArray
        for (var i=0; i < aChildren.length; i++) {
            var aChild = aChildren[i];
            var ahref = $(aChild).attr('href');
            aArray.push(ahref);
        } // this for loop fills the aArray with attribute href values

        $(window).scroll(function(){
            var windowPos = $(window).scrollTop(); // get the offset of the window from the top of page
            var windowHeight = $(window).height(); // get the height of the window
            var docHeight = $(document).height();

            for (var i=0; i < aArray.length; i++) {
                var theID = aArray[i];
                var divPos = $(theID).offset().top - 180; // get the offset of the div from the top of page
                var divHeight = $(theID).height(); // get the height of the div in question
                if (windowPos >= divPos && windowPos < (divPos + divHeight)) {
                	 /*if (!$("a[href='" + theID + "']").hasClass("nav-active")) {
                	 	$("a[href='" + theID + "']").tooltip("show");
                	}*/
                    $("a[href='" + theID + "']").addClass("nav-active");


                } else {
                	/*if ($("a[href='" + theID + "']").hasClass("nav-active")) {
                		 $("a[href='" + theID + "']").tooltip("hide");
                	}*/
                    $("a[href='" + theID + "']").removeClass("nav-active");

                }
            }

            if(windowPos + windowHeight == docHeight) {
                if (!$("#vnav li:last-child a").hasClass("nav-active")) {
                    var navActiveCurrent = $(".nav-active").attr("href");
                    $("a[href='" + navActiveCurrent + "']").removeClass("nav-active");
                    $("#vnav li:last-child a").addClass("nav-active");
                }
            }
        });
    });



</script>
@stop


