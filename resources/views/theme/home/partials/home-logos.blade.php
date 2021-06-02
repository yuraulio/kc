<!-- PARTNERS -->

<div class="logos-row hidden-xs">
	<div class="container">
		@if(isset($block_logos) && isset($block_logos[0]))
		<div class="row">
			<div class="col-lg-12 col-md-12 col-sm-12">
            	<h2>{{ $block_logos[0]->title }}</h2>
            	<!--@if($block_logos[0]->body != '')
            	{!! $block_logos[0]->body !!}
            	@else
            	<p></p>
            	@endif-->
			</div>
		</div>
		@endif
		<div class="row">
			<div class="col-lg-12 col-md-12 col-sm-12 nopadding" style="text-align:center;">

				<div class="swiper-container deskswiper">

				@if(isset($homeLogos))
				<div class="swiper-wrapper">
			        @foreach ($homeLogos as $key => $value)
			        	@if(isset($value['image']))
				            <div class="part-tile swiper-slide">
							<a target="{{ $value['target'] }}" href="{{ $value['ext_url'] }}" title="{{ $value['title'] }}">
								<img class="img-responsive" alt="{{ $value['title'] }}" title="{{ $value['title'] }}" src="{{ $value['image'] }}" />
							</a>
							</div>
						@endif
			        @endforeach
			    </div>
		        @endif

		    	</div>


			</div>
		</div>
		<div class="row">
			<div class="col-lg-12 col-md-12 col-sm-12 nopadding">
			<div class="btn-alone-wrap"><a href="/regularly-mentioned-in-media" title="Regularly mentioned in media" class="btn btn-green see-more">See more</a></div>
			</div>
		</div>
	</div>
</div>

