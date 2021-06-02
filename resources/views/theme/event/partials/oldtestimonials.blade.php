<!-- TESTIMONIALS -->
<div id="section-testimonials" class="event-section">
    <div class="container">
    	<div class="row">
    		<div class="col-lg-12">
    			@if(isset($event_blocks) && isset($event_blocks[2]))
        			<h1>{{ $event_blocks[2]->title }}</h1>
        			<span class="section-subt">{!! $event_blocks[2]->body !!}</span>
	                	
			
				@else
    			<h1>Testimonials</h1>
                <p class="section-p">Awesome things the people who attended our digital marketing courses say about us</p>
                @endif
    		</div>
    	</div>
    	<div class="row">
    		<div class="col-lg-2 col-md-2 col-sm-3 testimonials_img">
	            <div id="owl-testimonials-images" class="owl-carousel">
	                @if (!empty($testimonials))
	                    @foreach ($testimonials as $key => $row)
	                        <div class="item">
	                            <img src="{{ $frontHelp->pImg($row, 'users') }}" alt="{!! $row->title !!}" />
                                <div class="owlfix">
                                <h4>{{ $frontHelp->pField($row, 'title') }}</h4>
                                <p style="color: #70767C;">{{ $frontHelp->pField($row, 'header') }}</p>
                                </div>
	                            <!-- <img src="http://placehold.it/900x500" alt="testimonials photo" /> -->
	                        </div>
	                    @endforeach
	                @endif

	            </div>
	        </div>
	        <div class="col-lg-10 col-md-10 col-sm-9 testimonials_text_col">

	            <div id="owl-testimonials" class="owl-carousel">
	                @if (!empty($testimonials))
	                    @foreach ($testimonials as $key => $row)
	                        <div class="testimonials_item">
	                            <!-- <p>"{{ $frontHelp->truncateOnSpace($frontHelp->pField($row, 'excerpt'), 300) }}"</p> -->
                                <div class="outerContainer">
                                    <div class="innerContainer">
                                        <div class="element">{!! $frontHelp->pField($row, 'excerpt') !!}<br />
                                            <p class="mobileR"> {{ $frontHelp->pField($row, 'subtitle') }}</p>
                                        </div>

                                    </div>
                                </div>


	                            <!-- <div class="testi-content">{!! $frontHelp->pField($row, 'excerpt') !!}</div>
	                            <p>{{ $frontHelp->pField($row, 'subtitle') }}</p> -->

	                        </div>
	                    @endforeach
	                @endif

	            </div>
	            <div class="testimonials_navigation">

	                <span id="testimonials_carousel_back" class="testi-nav"><img src="theme/assets/img/round_arrow_left.svg" alt="Previous" /></span>
	                <span id="testimonials_carousel_next" class="testi-nav"><img src="theme/assets/img/round_arrow_right.svg" alt="Next" /></span>
	            </div>
	        </div>

	    </div>

    </div>
</div>
<!-- TESTIMONIALS END -->
