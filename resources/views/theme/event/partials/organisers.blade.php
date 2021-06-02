<!-- ORGANISERS -->
@if(isset($section_organisers))

   
		
			 

		 
		        @if(isset($evorg))
					@foreach($evorg as $vkey => $vvalue)
						@if(isset($vvalue->allMedia[0]['media_id']))
							<?php $media = PostRider\Media::select('id','path','name','ext')->findOrFail($vvalue->allMedia[0]['media_id'])->toArray(); ?>
							@if($vvalue->abbr != '')
								<a href="{{ $vvalue->abbr }}" target="_blank" title="{{ $vvalue->name }}">
							@endif
							<img alt="{{ $vvalue->name }}" title="{{ $vvalue->name }}" src="/uploads/originals/<?php echo $media['path'] . '/' . $media['name'] . $media['ext'] ; ?>" class="organisers img-responsive" />
							@if($vvalue->abbr != '')
								</a>
							@endif
						@else
						{ !!$vvalue->name!! }
						@endif
						
			
						{!! $section_organisers->htmlTitle !!}
				
					@endforeach
				
				@endif
			 
				
		
	

@endif
<!-- ORGANISERS END -->
