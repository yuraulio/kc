 <!-- INSTRUCTORS -->
 @if(isset($section_instructors))
<div id="section-instructors" class="tab-pane">
    <div class="container" id="diploma">
    	<div class="row is-flex">
    		<div class="col-lg-12 col-md-12 col-sm-12">
        			<h2>{!! $section_instructors->title !!}</h2>
	                {{-- {!! $section_instructors->body !!} --}}
    		</div>
			@if(isset($instructors))

    			@foreach($instructors as $lkey => $lvalue)
				<span class="instructorss">
    			<div class="col-lg-3 col-offset-1 col-md-3 col-sm-6 hidden-xs">
    				<div class="inst-cell">
    					<div class="event-instruct-image">
                            {{--<a href="{{ $frontHelp->pSlug($lvalue) }}" title="{{ $frontHelp->pField($lvalue, 'title') }}">--}}
                            <a href="{{ $lvalue->slug }}" title="{{ $frontHelp->pField($lvalue, 'title') }}">
                            @if(isset($lvalue['featured'][0]['media']))
                            	<img alt="{{ $frontHelp->pField($lvalue, 'title') }} {{ $lvalue['subtitle'] }}" title="{{ $frontHelp->pField($lvalue, 'title') }} {{ $lvalue['subtitle'] }}" src="{{ $frontHelp->pImg($lvalue, 'instructors-testimonials') }}" class="img-responsive hvr-grow" />
                            @else
                            	<img alt="{{ $frontHelp->pField($lvalue, 'title') }} {{ $lvalue['subtitle'] }}" title="{{ $frontHelp->pField($lvalue, 'title') }} {{ $lvalue['subtitle'] }}" src="{{cdn ('assets/img/no-featured-60.jpg') }}"  class="img-responsive hvr-grow"/>
                        	@endif
                    		</a>
                        </div>
                        <div class="inst-text">
	                    	<span class="inst-name"><a target="_blank" title="{{ $lvalue['title'] }} {{ $lvalue['subtitle'] }}" href="{{ $lvalue['ext_url'] }}">{{ $lvalue['title'] }} {{ $lvalue['subtitle'] }}</a></span><br />
	                    	@if(isset($lvalue['header']))
							<span class="inst-info">{{ $lvalue['header'] }},
								@if($lvalue['c_fields']['simple_text'][1]['value'] != '')
									@if(isset($lvalue['ext_url']) && $lvalue['ext_url'] != '')
									<a target="_blank" title="{{ $lvalue['c_fields']['simple_text'][1]['value'] }}" href="{{ $lvalue['ext_url'] }}"> {{ $lvalue['c_fields']['simple_text'][1]['value'] }}</a>
									@else
									{{ $lvalue['c_fields']['simple_text'][1]['value'] }}
									@endif
								@endif
							</span>
	                    	@endif
	                    </div>

                    	<div class="inst-social">
                            @if(isset($lvalue['c_fields']['simple_text'][2]) && $lvalue['c_fields']['simple_text'][2]['value'] != '')
                            <a target="_blank" title="Find me on facebook" href="{{ $lvalue['c_fields']['simple_text'][2]['value'] }}"><img class="social_ins" src="{{ cdn('theme/assets/img/new_icons/instructors/facebook.svg') }}" alt="Facebook logo" title="Facebook logo" /></a>
                            @endif

                            @if(isset($lvalue['c_fields']['simple_text'][3]) && $lvalue['c_fields']['simple_text'][3]['value'] != '')
                            <a target="_blank" title="Find me on twitter" href="{{ $lvalue['c_fields']['simple_text'][3]['value'] }}"><img class="social_ins" src="{{ cdn('theme/assets/img/new_icons/instructors/twitter.svg') }}" alt="Twitter logo" title="Twitter logo"/></a>
                            @endif

                            @if(isset($lvalue['c_fields']['simple_text'][4]) &&  $lvalue['c_fields']['simple_text'][4]['value'] != '')
                            <a target="_blank" title="Find me on instagram" href="{{ $lvalue['c_fields']['simple_text'][4]['value'] }}"><img class="social_ins" src="{{ cdn('theme/assets/img/new_icons/instructors/instagram.svg') }}" alt="Instagram logo" title="Instagram logo" /></a>
                            @endif

                            @if(isset($lvalue['c_fields']['simple_text'][5]) &&  $lvalue['c_fields']['simple_text'][5]['value'] != '')
                            <a target="_blank" title="Find me on linkedin" href="{{ $lvalue['c_fields']['simple_text'][5]['value'] }}"><img class="social_ins" src="{{ cdn('theme/assets/img/new_icons/instructors/linkedin.svg') }}" alt="LinkedIn logo" title="Linkedin logo" /></a>
                            @endif

                            @if(isset($lvalue['c_fields']['simple_text'][6]) &&  $lvalue['c_fields']['simple_text'][6]['value'] != '')
                            <a target="_blank" title="Find me on youtube" href="{{ $lvalue['c_fields']['simple_text'][6]['value'] }}"><img class="social_ins" src="{{ cdn('theme/assets/img/new_icons/instructors/youtube.svg') }}" alt="YouTube logo" title="YouTube logo" /></a>
                            @endif
                    	</div>
					</div>
    			</div></span>
    			@endforeach
    		@endif

    		@if(isset($instructors))
			<div class="swiper-container hidden-lg hidden-md hidden-sm">
				<div class="swiper-wrapper">
    			@foreach($instructors as $lkey => $lvalue)
    			<div class="swiper-slide">
    				<div class="inst-cell">
    					<div class="event-instruct-image">
                            <a href="{{ $lvalue->slug }}" title="{{ $frontHelp->pField($lvalue, 'title') }}">
                            @if(isset($lvalue['featured'][0]['media']))
                            	<img alt="{{ $frontHelp->pField($lvalue, 'title') }} {{ $lvalue['subtitle'] }}" title="{{ $frontHelp->pField($lvalue, 'title') }} {{ $lvalue['subtitle'] }}" src="{{ $frontHelp->pImg($lvalue, 'instructors-testimonials') }}" class="img-responsive hvr-grow" />
                            @else
                            	<img alt="{{ $frontHelp->pField($lvalue, 'title') }}" title="{{ $frontHelp->pField($lvalue, 'title') }}" src="{{ cdn('assets/img/no-featured-60.jpg') }}"  class="img-responsive hvr-grow"/>
                        	@endif
                    		</a>
                        </div>
                        <div class="inst-text">
	                    	<span class="inst-name">{{ $lvalue['title'] }} {{ $lvalue['subtitle'] }}</span><br />
	                    	@if(isset($lvalue['header']))
							<span class="inst-info">{{ $lvalue['header'] }}
								@if($lvalue['c_fields']['simple_text'][1]['value'] != '')<br />
									@if(isset($lvalue['ext_url']) && $lvalue['ext_url'] != '')
									<a target="_blank" title="{{ $lvalue['c_fields']['simple_text'][1]['value'] }}" href="{{ $lvalue['ext_url'] }}"> {{ $lvalue['c_fields']['simple_text'][1]['value'] }}</a>
									@else
									{{ $lvalue['c_fields']['simple_text'][1]['value'] }}
									@endif
								@endif
							</span>
	                    	@endif
	                    </div>

                    	<div class="inst-social">
                            @if(isset($lvalue['c_fields']['simple_text'][2]) && $lvalue['c_fields']['simple_text'][2]['value'] != '')
                            <a target="_blank" title="Find me on facebook" href="{{ $lvalue['c_fields']['simple_text'][2]['value'] }}"><img class="social_ins" src="{{ cdn('theme/assets/img/new_icons/instructors/facebook.svg') }}" alt="Facebook logo" title="Facebook logo" /></a>
                            @endif

                            @if(isset($lvalue['c_fields']['simple_text'][3]) && $lvalue['c_fields']['simple_text'][3]['value'] != '')
                            <a target="_blank" title="Find me on twitter" href="{{ $lvalue['c_fields']['simple_text'][3]['value'] }}"><img class="social_ins" src="{{ cdn('theme/assets/img/new_icons/instructors/twitter.svg') }}" alt="Twitter logo" title="Twitter logo" /></a>
                            @endif

                            @if(isset($lvalue['c_fields']['simple_text'][4]) &&  $lvalue['c_fields']['simple_text'][4]['value'] != '')
                            <a target="_blank" title="Find me on instagram" href="{{ $lvalue['c_fields']['simple_text'][4]['value'] }}"><img class="social_ins" src="{{ cdn('theme/assets/img/new_icons/instructors/instagram.svg') }}" alt="Instagram logo" title="Instagram logo" /></a>
                            @endif

                            @if(isset($lvalue['c_fields']['simple_text'][5]) &&  $lvalue['c_fields']['simple_text'][5]['value'] != '')
                            <a target="_blank" title="Find me on linkedin" href="{{ $lvalue['c_fields']['simple_text'][5]['value'] }}"><img class="social_ins" src="{{ cdn('theme/assets/img/new_icons/instructors/linkedin.svg') }}" alt="LinkedIn logo" title="LinkedIn logo" /></a>
                            @endif

                            @if(isset($lvalue['c_fields']['simple_text'][6]) &&  $lvalue['c_fields']['simple_text'][6]['value'] != '')
                            <a target="_blank" title="Find me on youtube" href="{{ $lvalue['c_fields']['simple_text'][6]['value'] }}"><img class="social_ins" src="{{ cdn('theme/assets/img/new_icons/instructors/youtube.svg') }}" alt="YouTube logo" title="YouTube logo" /></a>
                            @endif
                    	</div>
					</div>
    			</div>
    			@endforeach
	    		</div>
	    	</div>
    		@endif
    	</div>
    </div>
</div>
@endif
<!-- INSTRUCTORS END -->
