<!-- GALLERY -->
@if(isset($section_gallery))
<div id="section-gallery" class="event-section">
    <div class="container">
    	<div class="row">
    		<div class="col-lg-12 col-md-12 col-sm-12 hidden-xs">
				<div class="gallery_navigation" style="margin-bottom:10px;">
	                <span id="gallery_carousel_back" class="testi-nav"><img src="{{ cdn('theme/assets/img/round_arrow_left.svg') }}" alt="Previous image" title="Previous image" /></span>
	                <span id="gallery_carousel_next" class="testi-nav"><img src="{{ cdn('theme/assets/img/round_arrow_right.svg') }}" alt="Next image" title="Next image" /></span>
	            </div>
    		</div>
    		<div class="col-lg-5 col-md-5 col-sm-6">			
				<h2>{{ $section_gallery->title }}</h2>
				{!! $section_gallery->body !!}				
			</div>
    		<div class="col-lg-7 col-md-7 col-sm-6">
    			<div id="owl-gallery-images" class="owl-carousel">
				    @if(isset($content['c_fields']['multi_dropzone'][0]['value']['media']) && !empty($content['c_fields']['multi_dropzone'][0]['value']['media']))
				        @foreach($content['c_fields']['multi_dropzone'][0]['value']['media'] as $key => $value)
				            <?php
				                if(!isset($value['title'])) :
				                    $value['title'] = 'Photo from '.$frontHelp->pField($content, 'title').' course.';
				                endif;
				                ?>
				                <div class="gallery_item">
				                <a class="popup" href="{{ cdn('uploads/originals/'.$value['relative_path']) }}" title="{!! $value['title'] !!}">
	                            	<img src="{{ cdn('/portal-img/gallery-image/'.$value['relative_path']) }}" alt="{!! $value['title'] !!}" title="{!! $value['title'] !!}" />
	                            </a>
	                        	</div>
				        @endforeach
				    @elseif(isset($content['c_fields']['multi_dropzone']['value']['media']) && !empty($content['c_fields']['multi_dropzone']['value']['media']))
				    	@foreach($content['c_fields']['multi_dropzone']['value']['media'] as $key => $value)
				            <?php
				                if(!isset($value['title'])) :
				                    $value['title'] = 'Photo from '.$frontHelp->pField($content, 'title').' course.';
				                endif;
				                ?>
				                <div class="gallery_item">
				                	<a class="popup" href="{{ cdn('uploads/originals/'.$value['relative_path']) }}" title="{!! $value['title'] !!} @if(isset($value['caption'])) - {!! $value['caption'] !!} @endif">
	                            		<img src="{{ cdn('/portal-img/gallery-image/'.$value['relative_path']) }}" alt="{!! $value['title'] !!}" title="{!! $value['title'] !!}" />
	                            	</a>
	                        	</div>
				        @endforeach
				    @endif
				</div>
			</div>
			<div class="visible-xs center-block">
				<div class="gallery_navigation" style="margin-top:10px;">
	                <span id="gallery_carousel_back" class="testi-nav"><img src="{{ cdn('theme/assets/img/round_arrow_left.svg') }}" alt="Previous image" title="Previous image" /></span>
	                <span id="gallery_carousel_next" class="testi-nav"><img src="{{ cdn('theme/assets/img/round_arrow_right.svg') }}" alt="Next image" title="Next image" /></span>
	            </div>
    		</div>
    	</div>
    </div>
</div>
@endif
<!-- GALLERY END -->
