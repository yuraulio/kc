@extends('theme.layouts.master')
@section('header')


{{-- http://schema.org/Event --}}
{{--
     <!-- <div itemscope itemtype="http://schema.org/EducationEvent">
      <meta itemprop="description" content="The complete digital & social media diploma with a long track record as a catalyst for change is now awarded as the 'Best Digital & Social Media Diploma in Greece' from Social Media World.

Trusted by more than 500 executives in top agencies, brands and corporations. Designed by experienced professionals, and driven by the latest technology, case studies and online tactics. Course's syllabus is EQF Level 5. Consists of exclusive topics and advanced knowledge and has 95.4% Net Promoter Score!

Learn from the best, transform your digital career and thrive in your field. This is your best step towards having a career as a certified Digital & Social Media Specialist.">
        <meta itemprop="startDate" content="2018-01-10T00:00">
        <meta itemprop="endDate" content="2018-03-30T00:00">
        <meta itemprop="duration" content="0000-00-00T18:00">
        <link itemprop="url" href="https://knowcrunch.com/professional-diploma-in-digital-social-media-athens-january-2018" rel="author"/>
        <a itemprop="url" href="https://knowcrunch.com/professional-diploma-in-digital-social-media-athens-january-2018"><span itemprop="name" style="display:block;"><strong>Professional Diploma in Digital & Social Media, Athens</strong></span></a>
        <div itemprop="address" itemscope itemtype="http://schema.org/PostalAddress">
      <span itemprop="streetAddress" style="display:block;">At Deree campus</span>
      <span itemprop="postOfficeBoxNumber" style="display:block;">15342</span>
      <div>
        <span itemprop="addressLocality" style="display:block;">Athens</span>,
        <span itemprop="addressRegion"style="display:block;">Makedonia</span>
      </div>
      <span itemprop="postalCode"style="display:block;">15342</span>
      <span itemprop="addressCountry"style="display:block;">Greece</span>
    </div>
  </div> -->
  <script type='application/ld+json'>
  
/*{
  "@context": "http://www.schema.org",
  "@type": "Event",
  "name": "Launch Party",
  "url": "http://www.example.com/launch-party",
  "description": "We're excited to announce the launch party for our newest app!",
  "startDate": "10/05/2015 12:00PM",
  "endDate": "10/05/2015 02:00PM",
  "location": {
    "@type": "Place",
    "name": "Joe's Party Palace",
    "sameAs": "http://www.example.com",
    "address": {
      "@type": "PostalAddress",
      "streetAddress": "100 Main Street",
      "addressLocality": "Seattle",
      "addressRegion": "WA",
      "postalCode": "98101",
      "addressCountry": "USA"
    }
  },
  "offers": {
    "@type": "Offer",
    "description": "an offer description",
    "url": "http://www.example.com",
    "price": "$9.99"
  }
}*/



 </script>


 --}}
{{-- http://schema.org/Event end --}}

<!-- <link rel="stylesheet" href="theme/assets/css/owl.carousel.css">
<link rel="stylesheet" href="theme/assets/css/owl.theme.css"> -->
<link rel="stylesheet" href="{{ cdn('theme/assets/css/owl.carousel.min.css') }}">
<link rel="stylesheet" href="{{ cdn('theme/assets/css/owl.theme.default.min.css') }}">
<link rel="stylesheet" href="{{ cdn('theme/assets/css/transitions.css') }}">
<link type="text/css" rel="stylesheet" href="{{ cdn('theme/assets/addons/slider/css/swiper.css') }}">


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
   
   
    <div id="main-content-body" class="tab-content">
    
      
        <!-- single-event-page -->
          
          @if($estatus == 0 || $estatus == 2)
              @include('theme.event.partials.header')
                {{--@include('theme.event.partials.summary')--}}
                @include('theme.event.partials.benefits')
            
             @include('theme.event.partials.topics')
            @include('theme.event.partials.instructors')
            @include('theme.event.partials.testimonials')
            @include('theme.event.partials.location')
            @include('theme.event.partials.qnas')
            @include('theme.event.partials.seats')
            <!-- event_blocks_priorities --> 
            {{--include('theme.event.partials.syllabus-manager')
            include('theme.event.partials.gallery')
            include('theme.event.partials.organisers')
            include('theme.event.partials.awarded')
            include('theme.event.partials.seats')
            include('theme.event.partials.contact')--}}
          @else
             @include('theme.event.partials.header')
            <div class="status-helper"></div>
            @include('theme.event.partials.topics')
            @include('theme.event.partials.instructors')
           @endif
      
    </div>

    <div id="extramenu" >
      <div class="container">
        <div class="row">
            <div id="nav-anchor"></div>
            <div id="vtab"  class="tabs">

                <ul class="nav nav-tabs">
                    <li class="active"><a title="About" data-toggle="tab" href="#section-header">Overview</a></li>
                    @if($estatus == 0 || $estatus == 2)
                    <!--	<li ><a title="Summary" data-toggle="tab" href="#section-summary">Summary</a></li>-->
                    <li><a title="Benefit" data-toggle="tab" href="#section-benefits">Benefits</a></li>
                      <li><a title="Topics" data-toggle="tab" href="#section-topics">Topics</a></li>
                      <li><a title="Instructors" data-toggle="tab" href="#section-instructors">Instructors</a></li>
                      <li><a title="Testimonials" data-toggle="tab" href="#section-testimonials">Testimonials</a></li>
                      <li><a title="Location" data-toggle="tab" href="#section-location">Location</a></li>
                      <li><a title="FAQ" data-toggle="tab" href="#section-qnas">FAQ</a></li>
                    @endif
                </ul>

                

               @if($estatus == 0)
                <div class="cta-inmenu"><a href="#section-seats" class="fbpix" title="ENROLL NOW" id="cta"><span id="enrollbt" class="btn btn-calltoaction">ENROLL NOW</span></a></div>
                @endif

              

            </div>
        </div>
      </div>
    </div>

    <!-- single-event-page END -->
</div>
<div class="loadmodal"><!-- Place at bottom of page --></div>

<div id="advancedModal" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Expertise level confirmation</h4>
            </div>
            <div class="modal-body">
                <p>Do you have more than 2 years hands-on operating experience on the subject of this course?</p>

                <div class="btn-group" data-toggle="buttons" id="miniquiz1">
                    <label class="btn">
                      <input type="radio" name="mqq1" value="1" /><i class="fa fa-circle-o fa-2x"></i><i class="fa fa-dot-circle-o fa-2x"></i> <span>  Yes</span>
                    </label>


                    <label class="btn">
                      <input type="radio" name="mqq1" value="0" /><i class="fa fa-circle-o fa-2x"></i><i class="fa fa-dot-circle-o fa-2x"></i> <span>  No</span>
                    </label>
                </div>

                <br />
                <br />

                <p>Did you successfully complete any full program covering all digital & social media channels in the last 2 years?</p>

                <div class="btn-group" data-toggle="buttons" id="miniquiz2">
                    <label class="btn">
                      <input type="radio" name="mqq2" value="1" /><i class="fa fa-circle-o fa-2x"></i><i class="fa fa-dot-circle-o fa-2x"></i> <span>  Yes</span>
                    </label>


                    <label class="btn">
                      <input type="radio" name="mqq2" value="0" /><i class="fa fa-circle-o fa-2x"></i><i class="fa fa-dot-circle-o fa-2x"></i> <span>  No</span>
                    </label>
                </div>

                <br />
                <br />

                <p class="miniq-result">Based on your answers we strongly suggest that you enroll at our <a href="/diplomas">Professional Diploma in Digital & Social Media</a> which is a course covering all digital & social media channels & strategies targeted to people with limited experience or knowledge.</p>
            </div>
            <div class="modal-footer" style="text-align: center;">
                <a href="/diplomas" class="btn btn-green-invert sh-diploma">VIEW ALL DIPLOMAS</a>
                {{-- <button type="button" class="btn btn-completed" data-dismiss="modal">Close</button> --}}
                <a id="final-answer" href="#" class="btn btn-booknow-full sh-book">ENROLL NOW</a>
            </div>
        </div>
    </div>
</div>

@endsection
@section('scripts')
<script src="{{ cdn('theme/assets/js/cart.js') }}"></script>
<script src="{{ cdn('theme/assets/js/owl.carousel.min.js') }}"></script>
<script type="text/javascript" src="{{ cdn('theme/assets/addons/slider/js/swiper-cb.min.js') }}"></script>
<script src="{{ cdn('theme/assets/js/jquery.scrollto.js') }}"></script>

@include('theme.layouts.tab_scripts')

<script type="text/javascript">
function myFunction() {
    document.getElementById("myDropdown").classList.toggle("dp-show");
}

// Close the dropdown menu if the user clicks outside of it
window.onclick = function(event) {
  if (!event.target.matches('.dropbtn')) {

    var dropdowns = document.getElementsByClassName("dp-dropdown-content");
    var i;
    for (i = 0; i < dropdowns.length; i++) {
      var openDropdown = dropdowns[i];
      if (openDropdown.classList.contains('dp-show')) {
        openDropdown.classList.remove('dp-show');
      }
    }
  }
}

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
            autoplay: {
              delay: 2000,
              disableOnInteraction: true,
            },
           

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





        var tottick = 0;
        if($('#ticketshelp').length) {
          tottick = $('#ticketshelp').html();
        }
        if(tottick > 1) {
            var myseatSwiper = new Swiper ('.forseatsonly', {
          // Optional parameters

            pagination: '.swiper-pagination',
              slidesPerView: 1.2,
              centeredSlides: true,
              paginationClickable: true,
              spaceBetween: 14,
              simulateTouch: true,
              autoplay: {
              delay: 2000,
              disableOnInteraction: true,
            },

          });
        }
        else {
            var myseatSwiper = new Swiper ('.forseatsonly', {
          // Optional parameters

            pagination: '.swiper-pagination',
              slidesPerView: 1,
              centeredSlides: true,
              paginationClickable: true,
              spaceBetween: 14,
              simulateTouch: true,
              autoplay: {
              delay: 2000,
              disableOnInteraction: true,
            },

          });

        }

        var myseatSwiper = new Swiper ('.forseatsonly', {
          // Optional parameters

          pagination: '.swiper-pagination',
            slidesPerView: 1.2,
            centeredSlides: true,
            paginationClickable: true,
            spaceBetween: 14,
            simulateTouch: true,
            autoplay: {
              delay: 2000,
              disableOnInteraction: true,
            },

        });



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

        var owl_videos = $("#owl-videos");

   
        owl.owlCarousel({
            autoplay: true,
            loop:true,
            autoplayHoverPause: true,
            slideBy: 2,
            mouseDrag: false,
            touchDrag: true,
            navigation: true,
            dotsEach:({!! json_encode($testimonials) !!})['length']/5,
            nav:true,
      navText:["<span id='testi-carousel-back' class='testi-nav'><img  src='theme/assets/img/outline-left.svg' alt='Previous' title='Previous' /></span>",
               "<span id='testi-carousel-next' class='testi-nav'><img  src='theme/assets/img/outline-right.svg' alt='Next' title='Next' /></span>"],
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
          },
      
			},
     

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

        $('#texts_carousel_next').on("click",function(){
            owl.trigger('next.owl.carousel');
            //owl_images.trigger('next.owl.carousel');
        });
        $('#texts_carousel_back').on("click",function(){
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

@if(isset($section_videos))
<script type="text/javascript">

$(document).ready(function() {
    var owl_videos = $("#owlvideos");

    owl_videos.owlCarousel({
                autoplay: true,
                smartSpeed: 2500,
                loop:true,
                autoplayHoverPause: true,
                slideBy: 4,
                margin: 14,
                mouseDrag: true,
                touchDrag: true,
                nav:true,
                navText:["<span id='testi-carousel-back' class='testi-nav'><img  src='theme/assets/img/outline-left.svg' alt='Previous' title='Previous' /></span>",
               "<span id='testi-carousel-next' class='testi-nav'><img  src='theme/assets/img/outline-right.svg' alt='Next' title='Next' /></span>"],
               responsive:{
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
                      items: 4,
                  }
               }

        });

    $('#videos_carousel_next').on("click",function(){
            owl_videos.trigger('next.owl.carousel');
            //owl_images.trigger('next.owl.carousel');
        });
        $('#videos_carousel_back').on("click",function(){
            owl_videos.trigger('prev.owl.carousel');
            //owl_images.trigger('prev.owl.carousel');
        });



          autoPlayYouTubeModal();

  function autoPlayYouTubeModal() {
      var trigger = $("body").find('[data-toggle="modal"]');
      trigger.click(function () {
          var theModal = $(this).data("target"),
              videoSRC = $(this).attr("data-theVideo"),
              videoSRCauto = videoSRC + "?autoplay=1";
          $(theModal + ' iframe').attr('src', videoSRCauto);
          $(theModal + ' button.close').click(function () {
              $(theModal + ' iframe').attr('src', videoSRC);
          });
          $('.modal').click(function () {
              $(theModal + ' iframe').attr('src', videoSRC);
          });
      });
  }




});
                </script>
@endif
<script type="text/javascript">
$(document).ready(function() {
        $('.popup').magnificPopup({
            // child items selector, by clicking on it popup will open  delegate: 'a',
            //AIzaSyB165ZXH0MizYqAzFNGqle5WwnfJ6WBx_g
            type: 'image',
            fixedContentPos: false,
            // other options
            gallery: {
                enabled:true
            }
        });

    });


</script>

 @if(isset($section_location) && isset($linkedvenue))
 <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBRc1QTdw4p42OVAnxVHtEcofHtTmUAA5I&amp;callback=initMap"
        async defer></script>


<script>


      function initMap() {

        @foreach($linkedvenue as $vkey => $venue)

      	var no = false;
      	if(document.getElementById("lat{{$vkey}}") && document.getElementById("lon{{$vkey}}")) {

      		var lat = Number(document.getElementById("lat{{$vkey}}").innerHTML);
      		var lng = Number(document.getElementById("lon{{$vkey}}").innerHTML);
          var tit = document.getElementById("map_helper{{$vkey}}").innerHTML;
      		no = true;
      	}
      	else {
      		no = false;
      	}

      	if (no) {

        var myLatLng = {lat: lat, lng: lng};
        var iconBase = '{{ url('/theme/assets/img') }}';

        var contentString = '<div id="mcontent{{$vkey}}">'+
      '<h3>'+tit+'</h3>'+
      '</div>';

        // Create a map object and specify the DOM element for display.
        var map{{$vkey}} = new google.maps.Map(document.getElementById('map_container{{$vkey}}'), {
          center: myLatLng,
          scrollwheel: false,
          zoom: 16,
          scrollwheel: false,
          navigationControl: true,
          mapTypeControl: false,
          scaleControl: true,
          draggable: true,

        });

        // Create a marker and set its position.
        var marker = new google.maps.Marker({
          map: map{{$vkey}},
          position: myLatLng,
          title: 'Event Venue Location!',
          icon: iconBase + '/map-marker-sm.png'
        });

        var infowindow = new google.maps.InfoWindow({
		      content: contentString,
		       position: myLatLng
		  });

        infowindow.open(map{{$vkey}}, marker);
      }

      @endforeach
    }

</script>


@endif
<script>

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



    
    <script>
    $(document).ready(function(){

       $(window).scroll(function(){

        
          var navbar = $("#extramenu");
          var imageH = $("#event-banner");
          var top;
            var window_top = $(window).scrollTop();
            //var div_top = $('#main-content-body').offset().top;
          
                if (window_top > 50) {
                	//var is = $('#vnav').is(":visible");
                	/*if (!is) {
                		setTimeout(function() {
                			$('[data-toggle="tooltip"]').tooltip("show");
    				}, 600);

                		setTimeout(function() {
                			$('[data-toggle="tooltip"]').tooltip("hide");
    				}, 5000);
                	}*/
                }

        });

        $('.panel-collapse').on('shown.bs.collapse', function (e) {
            var $panel = $(this).closest('.panel');
            $('html,body').animate({
                scrollTop: $panel.offset().top - 140
            }, 500);
        });


        /**
         * This part does the "fixed navigation after scroll" functionality
         * We use the jQuery function scroll() to recalculate our variables as the
         * page is scrolled/
         */
       

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




        $('#miniquiz1 input[type=radio]').on('change', function() {
            var q1 = $('#miniquiz1 input[name=mqq1]:checked').val();
            var q2 = $('#miniquiz2 input[name=mqq2]:checked').val();

            if(q1 == 1 || q2 == 1) {
                $('.sh-diploma').hide();
                $('.sh-book').css('display', 'inline-block');
                $('.miniq-result').hide();
            }
            else {
                $('.sh-diploma').css('display', 'inline-block');
                $('.sh-book').hide();
                $('.miniq-result').show();
            }

        });

        $('#miniquiz2 input[type=radio]').on('change', function() {
            var q1 = $('#miniquiz1 input[name=mqq1]:checked').val();
            var q2 = $('#miniquiz2 input[name=mqq2]:checked').val();

            if(q1 == 1 || q2 == 1) {
                $('.sh-diploma').hide();
                $('.sh-book').css('display', 'inline-block');
                $('.miniq-result').hide();
            }
            else {
                $('.sh-diploma').css('display', 'inline-block');
                $('.sh-book').hide();
                $('.miniq-result').show();
            }
        });


    });
</script>
@stop

@section('fbchat')

<div id="fb-root"></div>

@if(Agent::isDesktop())
<script>

(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = 'https://connect.facebook.net/en_US/sdk/xfbml.customerchat.js#xfbml=1&version=v2.12';
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));
</script>

<!-- Your customer chat code -->
<div class="fb-customerchat"
  attribution="setup_tool"
  page_id="486868751386439">
</div>

@endif
@stop


