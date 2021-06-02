 <!-- FULL VIDEO -->
@if(isset($section_fullvideo) && $section_fullvideo->body != '')
<div id="section-fullvideo" class="event-section">
   
            {{--<div class="col-lg-12">
            	<h2>{{ $section_fullvideo->title }}</h2>
            </div>--}}
       
    	<div class="row">
	       	<div class="col-lg-12 col-md-12 col-sm-12 hidden-xs">

	       		<div class="hs-responsive-embed-youtube">
	       			{!! $section_fullvideo->body !!}
	       		</div>

	       	</div>
 		</div>

 </div>
 @endif

