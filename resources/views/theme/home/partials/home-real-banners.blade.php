{{-- HOME BANNER SECTION {{ !Request::is('/') ? "hidden" : "" }} --}}
<header id="myCarousel" class="carousel slide">
    <!-- Indicators -->
    <ol class="carousel-indicators">
        <?php if (isset($homeBanners)) : ?>
        <?php foreach ($homeBanners as $key => $value) : ?>
            <li data-target="#myCarousel" data-slide-to="{{ $key }}" class="@if ($key == 0) active @endif"></li>
        <?php endforeach; ?>
        <?php else : ?>
            <li data-target="#myCarousel" data-slide-to="0" class="active"></li>
        <?php endif; ?>
    </ol>

    <!-- Wrapper for slides -->
    <div class="carousel-inner">
        <?php if (isset($homeBanners)) : ?>
        <?php foreach ($homeBanners as $key => $value) : ?>
            <div class="item @if ($key == 0) active @endif">
                <div class="fill" style="background-image:url('{{ $value['image'] }}');"></div>
                <div class="carousel-overlay"></div>               
                <div class="carousel-caption">
                    <h1>{{ $value['title'] }}</h1>
                    <h2>{{ $value['subtitle'] }}</h2>
                     @if($value['ext_url']  != '')
	                <a href="{{ $value['ext_url'] }}" title="BOOK NOW" class="btn btn-booknow">BOOK NOW</a>
			        @endif
                </div>
            </div>
        <?php endforeach; ?>
        <?php else : ?>
            <div class="item active">
	            <div class="fill" style="background-image:url('theme/assets/img/homepage_main_banner.jpg');"></div>
	            <div class="carousel-caption">
	                <h2>Knowcrunch</h2>
	            </div>
        	</div>
        <?php endif; ?>
        {{-- Carousel &lsaquo;  &rsaquo; nav banner_arrow_left.svg --}}
        <a class="carousel-control left" href="#myCarousel" data-slide="prev"><img src="{{ cdn('theme/assets/img/banner_arrow_left.svg') }}" alt="Previous Event" title="Previous Event" /></a>
        <a class="carousel-control right" href="#myCarousel" data-slide="next"><img src="{{ cdn('theme/assets/img/banner_arrow_right.svg') }}" alt="Next Event" title="Next Event" /></a>
    </div>
</header>
<!-- HOME BANNER END -->