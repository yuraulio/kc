<!-- TESTIMONIALS -->
@if(isset($section_testimonials))
<div id="section-testimonials" class="tab-pane ">
<div class="row" id="diploma">
    		<div class="col-lg-12" id="testi-title">
    			
        			<h2>{{ $section_testimonials->title }}</h2>
        		{{--	<span class="section-subt">{!!$section_testimonials->body !!}</span> --}}
				
    			<!-- <h2>Testimonials</h2>
                <p class="section-p">Awesome things the people who attended our digital marketing courses say about us demo</p> -->
                
    		</div>
    	</div>

		@include('theme.event.partials.videos')
    <div class="container" id="testi-text">
    	
    	<div class="row">
	        <div class="col-lg-12 col-md-12 col-sm-12 testimonials_text_col" style="width: 90%;display: block;margin: 0 auto;float: none;">

				<div id="owl-testimonials" class="owl-carousel" style="height: 1000px;">

	                @if (!empty($testimonials))
	                    @foreach ($testimonials as $key => $row)
	                        <div class="testimonials_item">
						
	                            <!-- <p>"{{ $frontHelp->truncateOnSpace($frontHelp->pField($row, 'excerpt'), 300) }}"</p> -->
                                <div class="outerContainer" style="height: 1000px;">
                                    <div class="innerContainer">
                                        <img src="{{ $frontHelp->pImg($row, 'users') }}" alt="{!! $row->title !!}" title="{!! $row->title !!}" />
                                        <div class="owlfix">
                                        <h4>{{ $frontHelp->pField($row, 'title') }}</h4>
                                        <p >{{ $frontHelp->pField($row, 'header') }}</p><br />
										
                                        </div>
                                        <div class="element" > {!! $frontHelp->pField($row, 'excerpt') !!}<br />
                                        <!-- <p> {{ $frontHelp->pField($row, 'subtitle') }}</p> -->
                                        </div>

                                    </div>
                                </div>

	                        </div>
	                    @endforeach
	                @endif

	            </div>
				{{--   <div class="testimonials_navigation">

	             <span id="testimonials_carousel_back" class="testi-nav"><img src="theme/assets/img/round_arrow_left.svg" alt="Previous" title="Previous" /></span>
	                <span id="testimonials_carousel_next" class="testi-nav"><img src="theme/assets/img/round_arrow_right.svg" alt="Next" title="Next" /></span>
	            </div>--}} 
	        </div>

	    </div>

    </div>
</div>
@endif
<!-- TESTIMONIALS END -->
