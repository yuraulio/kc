{{-- HOME BANNER SECTION {{ !Request::is('/') ? "hidden" : "" }} --}}
<header id="myCarousel" class="carousel slide carousel-fade hidden-xs" data-interval="6000" data-ride="carousel">
    <!-- Indicators -->
     <?php $nabb = 0; ?>
    @if(isset($featured) && isset($homeBanners) && (count($homeBanners) + count($featured)) > 1)
    <ol class="carousel-indicators">
        <?php if (isset($featured) && count($featured) > 0) : ?>
            <?php foreach ($featured as $key => $value) : ?>

                <li data-target="#myCarousel" data-slide-to="{{ $nabb }}" class="@if ($nabb == 0) active @endif"></li>

            <?php $nabb++; endforeach;
            endif; ?>
        <?php if (isset($homeBanners) && count($homeBanners) > 0) : ?>
            <?php foreach ($homeBanners as $key => $value) : ?>
            	<?php if(isset($value['image'])) : ?>
                <li data-target="#myCarousel" data-slide-to="{{ $nabb }}" class="@if ($nabb == 0) active @endif"></li>
            <?php $nabb++; endif; endforeach; ?>
        <?php endif; ?>
    </ol>
    @endif

    <!-- Wrapper for slides -->
     <?php $nabb = 0; ?>
    <div class="carousel-inner">
        <?php if (isset($featured) && count($featured) > 0) : ?>
            <?php foreach ($featured as $key => $value) : ?>

	                <div class="item @if ($nabb == 0) active @endif">
	                    <div class="fill" style="background-image:url('{{ $frontHelp->pImg($value, 'header-image') }}');"></div>
	                    <div class="carousel-overlay"></div>
	                    <div class="container">
	                        <div class="row">
	                            <div class="col-lg-12">
	                                <div class="carousel-caption">
	                                    <h1>{{ $value['title'] }}</h1>
	                                    <h2>{{ $value['subtitle'] }}</h2>
	                	                <a href="{{ $value['slug'] }}" title="BOOK NOW" class="btn btn-booknow-full fbpix">BOOK NOW</a>
	                                </div>
	                            </div>
	                        </div>
	                    </div>
	                </div>
            <?php $nabb++;
            endforeach;
        endif;
        ?>
        <?php if (isset($homeBanners) && count($homeBanners) > 0) : ?>
            <?php foreach ($homeBanners as $key => $value) : ?>
            	<?php if(isset($value['image'])) : ?>
	                <div class="item @if ($nabb == 0) active @endif">
	                    <div class="fill" style="background-image:url('{{ $value['image'] }}');"></div>
	                    <div class="carousel-overlay"></div>
	                    <div class="container">
	                        <div class="row">
	                            <div class="col-lg-12">
	                                <div class="carousel-caption">
	                                    <h1>{{ $value['title'] }}</h1>
	                                    <!--<h2>{{ $value['subtitle'] }}</h2>-->
                                        <p class="homepage-subtitle"> {{ $value['subtitle'] }} </p>
	                                     <!--@if($value['ext_url']  != '')
	                                    <a href="{{ $value['ext_url'] }}" title="LEARN MORE" class="btn btn-green-invert">LEARN MORE</a>
	                                    @endif-->
	                                </div>
	                            </div>
	                        </div>
	                    </div>
	                </div>
            <?php $nabb++;
            	endif;
            endforeach;
        endif; ?>
         @if(isset($featured) && isset($homeBanners) && (count($homeBanners) + count($featured)) > 1)

        {{-- Carousel &lsaquo;  &rsaquo; nav banner_arrow_left.svg --}}
        <a class="carousel-control left" href="#myCarousel" data-slide="prev"><img src="{{ cdn('theme/assets/img/banner_arrow_left.svg') }}" alt="Previous" title="Previous" /></a>
        <a class="carousel-control right" href="#myCarousel" data-slide="next"><img src="{{ cdn('theme/assets/img/banner_arrow_right.svg') }}" alt="Next" title="Next" /></a>
        @endif
    </div>
</header>
<!-- HOME BANNER END -->


<header id="myCarousel" class="carousel slide carousel-fade hidden-sm  hidden-md hidden-lg" data-interval="6000" data-ride="carousel">

    <!-- Wrapper for slides -->
     <?php $nabb = 0; ?>
    <div class="carousel-inner">
       
        <?php if (isset($homeBanners) && count($homeBanners) > 0) : ?>
            <?php foreach ($homeBanners as $key => $value) : ?>
            	<?php if(isset($value['image'])) : ?>
	                <div class="item @if ($nabb == 0) active @endif">
	            
	                    <div class="container">
	                        <div class="row">
	                            <div class="col-lg-12">
	                                <div class="carousel-caption">
	                                    <h1>{{ $value['title'] }}</h1>
	                                    <!--<h2>{{ $value['subtitle'] }}</h2>-->
                                        <p class="homepage-subtitle"> {{ $value['subtitle'] }} </p>
	                                     <!--@if($value['ext_url']  != '')
	                                    <a href="{{ $value['ext_url'] }}" title="LEARN MORE" class="btn btn-green-invert">LEARN MORE</a>
	                                    @endif-->
	                                </div>
	                            </div>
	                        </div>
	                    </div>
	                </div>
            <?php $nabb++;
            	endif;
            endforeach;
        endif; ?>
       
    </div>
</header>
