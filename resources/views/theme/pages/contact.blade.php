@extends('theme.layouts.master')

@section('content')
@inject('frontHelp', 'Library\FrontendHelperLib')
@include('theme.preview.preview_warning', ["id" => $content->id, "type" => "content", "status" => $content->status])

<div id="main-content-body">
<div id="event-banner">
        <?php //$media = $content['featured'][0]['media']; ?>
        <!-- <div class="fill" style="background-image:url('{{ $frontHelp->pImg($content, 'main') }}');"></div> -->
        <!-- <img class="fullme" alt="{{ $content->title }}" src="{{ $frontHelp->pImg($content, 'header-image') }}" /> contact-help-->

        <div class="page-helper"></div>

    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="post-caption">
                    <h1 class="animatable fadeInDown">{{ $content->title }}</h1>
                    <h2 class="animatable fadeInUp">{!! $content->summary !!}</h2>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="contact-section">
<div class="container">
    	<div class="row row-offcanvas row-offcanvas-left">
    		<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12 side sidebar-offcanvas">
            	<div class="about-sidebar clearfix" id="diploma">
	            	<ul class="dont-boost" id="sidebar">
	            		<!-- <li><a href="/about" title="About Us"> About Us</a></li> -->
	                    @if (!empty($aboutSidebar))
	                        @foreach ($aboutSidebar as $key => $row)
	                        <li class="@if($url == 'about' && $key == 0) active @endif @if($url == $row->slug) active @endif">
	                            <a href="{{ $frontHelp->pSlug($row) }}" title="{{ $frontHelp->pField($row, 'title') }}">
	                            	{{ $frontHelp->pField($row, 'title') }}
	                            </a>
	                        </li>
	                        @endforeach
	                    @endif
	                    <!-- <li class="@if($url == 'contact') active @endif"><a href="/contact" title="Contact us"> Contact us</a></li> -->
	                </ul>
	            	</div>
	            </div>
    		<!-- <div class="col-lg-6 col-md-6 col-sm-6">

    		</div> -->
    		<div class="col-lg-8 col-lg-offset-1 col-md-8 col-md-offset-1 col-sm-8 col-sm-offset-1 col-xs-12 col-xs-offset-0">
    			<p class="visible-xs" style="margin-top:20px;">
		            <button type="button" class="btn btn-green" data-toggle="offcanvas" style="margin-left:0px;"><i class="fa fa-bars"></i> Menu</button>
				</p>
				<div class="contact-content">

    			<div id="map_contact"></div>

    			<div class="contact-wrap">
        			<h2 class="animatable fadeInUp">{{ $content->subtitle }}</h2>
              		{!! $content->body !!}
                </div>

			    <form id="doall" method="POST" action="" class="contactUsForm">
			        <div id="contact_page_form_container">
			            <div class="row">
			                <div class="col-xs-12 col-sm-12">
			                    <div class="row">
			                        <div class="form-group col-lg-6 col-md-6 col-xs-12 col-sm-12">
			                            <label for="cname" class="sr-only">name</label>
			                            <input type="text" class="form-control" id="cname" name="cname" placeholder="Name*" value="<?php if (isset($form['cname'])) : echo $form['cname']; endif; ?>">
			                        </div>
			                    <!-- </div>
			                    <div class="row"> -->
			                        <div class="form-group phone_input col-lg-6 col-md-6 col-xs-12 col-sm-12">
			                            <label for="surname" class="sr-only">surname</label>
			                            <input type="text" class="form-control" id="csurname" name="csurname" placeholder="Surname*" value="<?php if (isset($form['csurname'])) { echo $form['csurname']; } ?>">
			                        </div>
			                    </div>
			                    <div class="row">
			                        <div class="form-group col-xs-12 col-sm-12">
			                            <label for="email" class="sr-only">email</label>
			                            <input type="text" class="form-control" id="cemail" name="cemail" placeholder="Email*" value="<?php if (isset($form['cemail'])) { echo $form['cemail']; } ?>">
			                        </div>
			                    </div>

			                </div>
			                <div class="col-xs-12 col-sm-12 textarea_container">
			                	<!-- <div class="row"> -->
			                        <div class="form-group">
					                    <label for="message" class="sr-only">Comment:</label>
					                    <textarea name="cmessage" rows="7" class="form-control" id="comment" placeholder="Message*"><?php if (isset($form['message'])) { echo $form['cmessage']; } ?></textarea>
			                    	</div>
			                    <!-- </div> -->
			                </div>
			            </div>


                        <div class="mobileR" style="display: block;">
                        	{{-- <div class="custom-checkbox-uni" id="terms" style="margin-bottom: 14px;">

							<input class="c-box" id="accept" type="checkbox" value="0" name="accept" required="" checked="checked"><a id="toggleterms" target="_blank" title="View Terms and conditions" href="/terms-privacy">I agree &amp; accept the terms &amp; conditions</a>
							</div> --}}
                            <div class="custom-checkbox" id="terms" style="height: 34px;">
                              <input class="c-box" id="accept" type="checkbox" value="0" name="accept" required="" >
                              <label> I have read, agreeee &amp; accept the terms &amp; conditions and data privacy policy</label>

                            </div>
                            <br />
                            <div class="mobileR">
                            View the <a id="toggleterms" target="_blank" title="View the Terms and conditions" href="/terms-privacy">terms &amp; conditions</a> and <a class="termlink" target="_blank" title="View the data privacy policy" href="/data-privacy-policy">data privacy policy</a></div>

                            <br />
                            <br />

                            <a title="SEND MESSAGE" id="sendme" class="btn btn-green-invert contactUsSubmit">SEND MESSAGE</a>
                            <div id="loadingDivN"><img class="img-responsive" src="theme/assets/img/ajax-loader-blue.gif" alt="Loader" title="Loader" /></div>

                        </div>

			        </div>
			    </form>
			    <div class="contactUsReponse"></div>

			</div>




			</div>
		</div>
    </div>
    </div>
</div>
@endsection
@section('scripts')
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBRc1QTdw4p42OVAnxVHtEcofHtTmUAA5I&amp;callback=initMap"
        async defer></script>
<script>
      function initMap() {
      	var lat = 37.4488002;
      	var lng = -122.161087;

      	//alert(lat+'|'+lng); AIzaSyBRc1QTdw4p42OVAnxVHtEcofHtTmUAA5I

        var myLatLng = {lat: lat, lng: lng};
        var iconBase = '{{ url('/theme/assets/img/new_icons/location') }}';//'{{ url('/theme/assets/img/new_icons') }}';//'{{ url('/theme/assets/img') }}';

        var contentString = '<div id="mapcontent">'+
      '<div id="siteNotice">'+
      '</div>'+
      '<h1 class="firstHeading">Knowcrunch</h1>'+
      '<div class="mapbodyContent">'+
      '<p>530 University ave.<br />94301, Palo Alto, CA<br />USA</p> ' +
      '</div>'+
      '</div>';
        // Create a map object and specify the DOM element for display.
        var map = new google.maps.Map(document.getElementById('map_contact'), {
          	center: myLatLng,
          	scrollwheel: false,
          	zoom: 7,
          	scrollwheel: false,
		    navigationControl: true,
		    mapTypeControl: false,
		    scaleControl: true,
		    draggable: true,

        });

        // Create a marker and set its position.
        var marker = new google.maps.Marker({
          map: map,
          position: myLatLng,
          title: 'Knowcrunch Location!',
          icon: iconBase + '/pin.svg'//'/place.svg'//'/map-marker-sm.png'
        });

        var infowindow = new google.maps.InfoWindow({
		      content: contentString,
		       position: myLatLng
		  });

        infowindow.open(map, marker);
      }
</script>
<script type="text/javascript">
$(window).load(function(){

var width = $(window).width();
if (width > 768) {
    var $sidebar   = $("#sidebar"),
        $window    = $(window),
        offset     = $sidebar.offset(),
        topPadding = 170;
    var maxcont = $('.contact-content').height() - 280;
    var ban = $('#event-banner').height();

    $window.scroll(function() {

        var modfix = offset.top - 60;

        if ($window.scrollTop() > modfix && $window.scrollTop() <  maxcont + ban) {
            $sidebar.stop().animate({
                marginTop: $window.scrollTop() - ban + 100

            });
        } else {
            $sidebar.stop().animate({
                marginTop: 0
            });
        }
    });
}

});

</script>
<script type="text/javascript">


$(document).ready(function() {
  $('[data-toggle=offcanvas]').click(function() {
    $('.row-offcanvas').toggleClass('active');
  });
});

</script>
@stop
