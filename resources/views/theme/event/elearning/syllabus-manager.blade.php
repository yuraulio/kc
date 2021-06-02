@if(isset($section_syllabus_manager))
<div id="section-syllabus" class="event-section" >
	<div class="container" id="diploma">
    	<div class="row">
			
	        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
	        	@if (!empty($section_syllabus_manager['featured']) && isset($section_syllabus_manager['featured'][0]) && isset($section_syllabus_manager['featured'][0]['media']) && !empty($section_syllabus_manager['featured'][0]['media']))
				    <img alt="{{ $frontHelp->pField($section_syllabus_manager, 'title') }}" title="{{ $frontHelp->pField($section_syllabus_manager, 'title') }}" src="{{ $frontHelp->pImg($section_syllabus_manager, 'instructors-small') }}" class="img-responsive" />
				@else
					<?php $backis = cdn('/theme/assets/img/noimage.jpeg'); ?>
					<img alt="no-image" title="no-image" src="{{ $backis }}" class="img-responsive" />
				@endif
				<div class="about-content" id="syllabus">            
		    		<h2>{{ $section_syllabus_manager->title }}</h2>
		        	{!! $section_syllabus_manager->body !!}					
				</div>
		
	        </div>
	      
		
		</div>
		
	</div>
	
</div>

@endif
