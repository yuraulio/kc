 <!-- LOCATION -->
 @if(isset($section_location))
<div id="section-location" class="tab-pane">
    <div class="container" id="diploma">
        @if(isset($linkedvenue) && count($linkedvenue) > 1)
            <h2>{!! $section_location->title !!}</h2>
        @else
            <h2>{!! $section_location->title !!}</h2>
        @endif
        @if(isset($linkedvenue))
            @foreach($linkedvenue as $vkey => $venue)
        	<div class="row">
    		    <div class="col-lg-12 col-md-12 col-sm-12">
                
                    <div class="venue-tile-title">
                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                        <div class="locpinme">
                            {{--<div class="thelocpin pin-venue"><img src="theme/assets/img/pin.svg" alt="{{ $venue['title'] }}" title="{{ $venue['title'] }}" /></div>--}}
                                <div class="theloctitle"><h3>{!! $venue['htmlTitle'] !!}</h3>
                                    <div class="venue-info">
                                        {!! $venue['subtitle'] !!}<br />
                                    </div>
                                    <div class="venue-info">
                                       {!! $venue['short_title'] !!}
                                    </div>
                                </div>
                        </div>
                    	{{-- <span class="contact-phone pin-venue"><img src="theme/assets/img/pin.svg" alt="{{ $venue['title'] }}" title="{{ $venue['title'] }}" /></span><span class="contact-phone pin-venue">{{ $venue['title'] }}</span><br /> --}}
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                        <div class="venue-info" id="location-direction">
    	                  
    	                    {!! $venue['body'] !!}
                        </div>
                    </div>
    					@if(isset($venue['header']) && $venue['header'] != '')
                        <span class="contact-phone"><img src="theme/assets/img/phone.svg" alt="{{ $venue['header'] }}<" />{{ $venue['header'] }}</span>
    					@endif
    					<div id="lat{{$vkey}}" class="hidden">{{ $venue['c_fields']['simple_text'][0]['value'] }}</div>
                    	<div id="lon{{$vkey}}" class="hidden">{{ $venue['c_fields']['simple_text'][1]['value'] }}</div>
                    </div>

    		    </div>
    			<div class="col-lg-12 col-md-12 col-sm-12">
    			    <div id="map_container{{$vkey}}"></div>
                  
          		</div>
     		</div>
            @endforeach
        @else
            No venue found attached on this event
        @endif
 	</div>
 </div>
 @endif
 <!-- LOCATION END -->
