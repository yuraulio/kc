@extends('theme.layouts.master')

@section('content')
@inject('frontHelp', 'Library\FrontendHelperLib')
<div id="main-content-body">
<!-- single-post-page -->
    <div id="gallery">
        <div class="container">
            @include('theme.sections.media.partials.media_type_categories')
        </div>
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12">
                    <div class="row">
                        <div class="">
                            @if (!empty($photoGallery))
                            <div id='carousel-custom' class='carousel slide dpContentEntry' data-ride='carousel' data-dp-content-id="{{ $content->id }}">
                                <div class='carousel-outer'>
                                    <!-- Wrapper for slides -->
                                    <div class='carousel-inner'>
                                        @foreach ($photoGallery as $key => $media)
                                        <div class='item <?php if ($key == 0) echo "active"; ?>'>
                                            <div class="carousel-caption-top">
                                                <h2>
                                                    <a href="javascript:void(0);" title="{{ $frontHelp->pField($content, 'title') }}" class="carousel-head-link">
                                                        {{ $frontHelp->pField($content, 'title') }}
                                                    </a>
                                                </h2>
                                            </div>
                                            <img class="img-responsive" src="portal-img/default/{{ $media['path'] }}/{{ $media['name'].$media['ext'] }}" alt="{{ $media['name'] }}" />
                                            @if (isset($media['caption']) && strlen($media['caption']))
                                            <div class="carousel-caption-bottom">
                                                <h1 class="lead">
                                                    <a href="javascript:void(0);" class="carousel-link">
                                                        {{ $media['caption'] }}
                                                    </a>
                                                </h1>
                                            </div>
                                            @endif
                                        </div>
                                        @endforeach
                                    </div>
                                    @if (count($photoGallery) > 1)
                                    <!-- Controls -->
                                    <a class='left carousel-control' href='#carousel-custom' data-slide='prev'>
                                        <span class="fa fa-angle-left"></span>
                                    </a>
                                    <a class='right carousel-control' href='#carousel-custom' data-slide='next'>
                                        <span class="fa fa-angle-right"></span>
                                    </a>
                                    @endif
                                </div>
                                <!-- Indicators -->
                                <ol class='carousel-indicators mCustomScrollbar'>
                                @foreach ($photoGallery as $key => $media)
                                    <li data-target='#carousel-custom' data-slide-to='<?php echo $key; ?>' class='active'>
                                        <img class="img-responsive" src="portal-img/photo-gallery-thumb/{{ $media['path'] }}/{{ $media['name'].$media['ext'] }}" alt="{{ $media['name'] }}" />
                                    </li>
                                @endforeach
                                </ol>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div><!-- ROW END -->
        </div>
    </div>
    <!-- single-post-page END -->
</div>
@include('theme.sections.media.partials.media_scripts')
@endsection
