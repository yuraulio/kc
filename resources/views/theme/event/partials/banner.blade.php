<div id="event-banner"  class="hidden-xs">
    @if (!empty($content['featured']) && isset($content['featured'][0]) &&isset($content['featured'][0]['media']) && !empty($content['featured'][0]['media']))
        <?php //$media = $content['featured'][0]['media']; ?>
        <!-- <div class="fill" style="background-image:url('{{ $frontHelp->pImg($content, 'main') }}');"></div> -->
        <img id="dima" class="fullme" alt="{{ $content->title }}" title="{{ $content->title }}" src="{{ $frontHelp->pImg($content, 'home-carousel') }}" />
        <div class="carousel-overlay" id="event-image"></div>
        @endif
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
            	<div class="carousel-caption event" >
                <h1 class="animatable fadeInDown">{{ $content->title }}</h1>
                <h2 class="animatable fadeInUp">{{ $content->subtitle }}</h2>
            </div>
            </div>
        </div>
    </div>
</div>

<div id="event-banner"  class="hidden-lg hidden-md hidden-sm ">
   
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
            	<div class="carousel-caption event" >
                <h1 class="animatable fadeInDown">{{ $content->title }}</h1>
                <h2 class="animatable fadeInUp">{{ $content->subtitle }}</h2>
            </div>
            </div>
        </div>
    </div>
</div>

