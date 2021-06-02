@if(isset($section_awarded))
<div id="section-syllabus" class="event-section">
	<div class="container">
    	<div class="row">
	        <div class="col-lg-5 col-md-5 col-sm-6 col-xs-12">
	            <!-- <div class="blueoverlay"></div> -->
	            <div class="about-content awarded">		            
	                <h2>{{ $section_awarded->title }}</h2>
	                {!! $section_awarded->body !!}
	        	</div>
	        </div>
	        <div class="col-lg-7 col-md-7 col-sm-6 col-xs-12">
	        	@if (!empty($section_awarded['featured']) && isset($section_awarded['featured'][0]) && isset($section_awarded['featured'][0]['media']) && !empty($section_awarded['featured'][0]['media']))
				    <img alt="{{ $frontHelp->pField($section_awarded, 'title') }}" title="{{ $frontHelp->pField($section_awarded, 'title') }}" src="{{ $frontHelp->pImg($section_awarded, 'awarded-block') }}" class="img-responsive fullme" />
				@else
					<?php  $backis = '/theme/assets/img/noimage.jpeg'; ?>
					<img alt="no-image" src="{{ $backis }}" title="no-image" class="img-responsive" />
				@endif
	        </div>
    	</div>
	</div>
</div>
@endif
