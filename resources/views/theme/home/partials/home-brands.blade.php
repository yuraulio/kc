<!-- BRANDS -->
@if(isset($homeBrands) && count($homeBrands) > 0)
<div class="logos-row hidden-xs">
	<div class="container">
		@if(isset($block_brands) && isset($block_brands[0]))
		<div class="row">
			<div class="col-lg-12 col-md-12 col-sm-12">
	            <h2>{{ $block_brands[0]->title }}</h2>
    	   <!--     {!! $block_brands[0]->body !!} -->
		    </div>
		</div>
		@endif
		<div class="row">
			<div class="col-lg-12 col-md-12 col-sm-12 nopadding" style="text-align:center;">
				<div class="swiper-container deskswiper2">
					<div class="swiper-wrapper">
						@foreach ($homeBrands as $key => $value)
							@if(isset($value['image']))
								<div class="part-tile swiper-slide">
								<a target="{{ $value['target'] }}" href="{{ $value['ext_url'] }}" title="{{ $value['title'] }}">
									<img class="img-responsive" alt="{{ $value['title'] }}" title="{{ $value['title'] }}" src="{{ $value['image'] }}" />
								</a>
								</div>
							@endif
						@endforeach
					</div>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-lg-12 col-md-12 col-sm-12 nopadding">
			<div class="btn-alone-wrap">
			<a href="/they-trust-us" title="They trust us" class="btn btn-green see-more">See more</a></div>
			</div>
		</div>
	</div>
</div>
@endif
