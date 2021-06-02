@extends('theme.layouts.master')

@section('content')
@inject('frontHelp', 'Library\FrontendHelperLib')

@if (!empty($content['featured']) && isset($content['featured'][0]) && isset($content['featured'][0]['media']) && !empty($content['featured'][0]['media']))
    <?php $media = $content['featured'][0]['media']; ?>
    <div id="page-banner">
        <img class="img-responsive center-block" alt="{{ $content->title }}" src="{{ $frontHelp->pImg($content, 'juris') }}" />
        <div class="container" id="banner-caption">
            <div class="row">
                <div class="col-lg-12">
                    <h3 class="introtextpages animatable fadeInUp">{{ $content->title }}</h3>
                </div>
            </div>
        </div>
    </div>


@else

    <div id="page-banner">
        <img alt="" src="assets/img/banners/juristrictions_banner.jpg" class="img-responsive center-block" />
        <div class="container" id="banner-caption">
            <div class="row">
                <div class="col-lg-12">
                    <h3 class="introtextpages animatable fadeInUp">Jurisdictions</h3>
                </div>
            </div>
        </div>
    </div>

@endif




<div class="page-content">

<!-- single-post-page -->

    <div id="juris">
        <div class="container">
            <div class="row">
                <div class="col-lg-4 col-md-5 col-sm-6">
                    <div class="juris-sidebar">
                        <h1 class="auto-head">@if ($content->header != '') {{ $content->header }} @else {{ $content->title }} @endif</h1>

                        {!! $content->summary !!}
                    </div>
                </div>

                <div class="col-lg-8 col-md-7 col-sm-6">
                    <div class="juris-content">
                        {!! $content->body !!}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- single-post-page END -->
</div>

@include('theme.home.partials.newsletter')
@endsection
