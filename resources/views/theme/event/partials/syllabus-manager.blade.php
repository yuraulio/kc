@if(isset($section_syllabus_manager))

			
	       
	        	@if (!empty($section_syllabus_manager['featured']) && isset($section_syllabus_manager['featured'][0]) && isset($section_syllabus_manager['featured'][0]['media']) && !empty($section_syllabus_manager['featured'][0]['media']))
				    <img alt="{{ $frontHelp->pField($section_syllabus_manager, 'title') }}" title="{{ $frontHelp->pField($section_syllabus_manager, 'title') }}" src="{{ $frontHelp->pImg($section_syllabus_manager, 'instructors-small') }}" class="syllabus-manager img-responsive" />
				@else
					<?php $backis = cdn('/theme/assets/img/noimage.jpeg'); ?>
					<img alt="no-image" title="no-image" src="{{ $backis }}" class="syllabus-manager img-responsive" />
				@endif
				<div class="about-content" id="syllabus">            
		    		<p>{{ $section_syllabus_manager->title }}</p>
		        	{!! $section_syllabus_manager->body !!}					
				</div>

			
	      
	

@endif
