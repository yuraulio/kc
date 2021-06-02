@extends('theme.layouts.master')

@section('content')
@inject('frontHelp', 'Library\FrontendHelperLib')
<div id="main-content-body">
<!-- single-post-page -->
    <div id="gallery">
        <div class="container">
            @include('theme.sections.media.partials.media_type_categories')
            <div class="row">
                <div class="col-lg-9 col-md-8 col-sm-7 col-xs-12">
                    @if (isset($content) && !empty($content))
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="home-featured-video-placeholder">
                                <div id="videoCarousel" class="">
                                    <div class="carousel-inner">
                                        <div class="item active dpContentEntry" data-dp-content-id="{{ $content->id }}">
                                            <div class="video-container">
                                                {!! $frontHelp->pField($content, 'summary') !!}
                                            </div>
                                            <div class="carousel-caption">
                                                {{ $frontHelp->pField($content, 'title') }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                    <!-- other posts list block -->
                    <div class="other-posts-section">
                        <div id="gallery-videos-tab" class="active">
                            <div id="appendNewContent" data-dp-take="{{ $take }}" data-dp-total="{{ $videos_dets['total'] }}" data-dp-skip="<?php echo count($list); ?>" data-dp-section-ids="<?php echo json_encode($category_ids); ?>" data-dp-media-type="videos">
                            @foreach ($list as $key => $row)
                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 nopadding dpContentEntry" data-dp-content-id="{{ $row['id'] }}">
                                    <div class="gallery-video-holder">
                                        <div class="video-container">
                                            <a target="{{ $frontHelp->pTarget($row) }}" title="{{ $frontHelp->pField($row, 'title') }}" href="{{ $frontHelp->pSlug($row) }}">
                                                <img style="width:100%;" class="img-responsive center-block" alt="{{ $frontHelp->pField($row, 'title') }}" src="{{ $frontHelp->pImg($row, 'video-list') }}">
                                            </a>
                                            <a class="play_video_outer" target="{{ $frontHelp->pTarget($row) }}" title="{{ $frontHelp->pField($row, 'title') }}" href="{{ $frontHelp->pSlug($row) }}">
                                                <span class="play_video_inner">
                                                    <i class="fa fa-play"></i>
                                                </span>
                                            </a>
                                        </div>
                                        <h1 class="gallery-video-post-title">
                                            <a target="{{ $frontHelp->pTarget($row) }}" title="{{ $frontHelp->pField($row, 'title') }}" href="{{ $frontHelp->pSlug($row) }}">
                                                {{ $frontHelp->pField($row, 'title') }}
                                            </a>
                                        </h1>
                                    </div>
                                </div>
                            @endforeach
                            @if ($videos_dets['total'] > 14)
                                <div class="row" id="loadMoreContent">
                                    <div class="col-lg-4 col-md-4 col-sm-4"></div>
                                    <div class="col-lg-4 col-md-4 col-sm-4">
                                        <span class="btn btn-normal loadMoreContentBtn">
                                            Δείτε περισσότερα
                                        </span>
                                    </div>
                                    <div class="col-lg-4 col-md-4 col-sm-4"></div>
                                </div>
                            @endif
                            </div>
                        </div>
                    </div>
                </div>
                <!-- SIDEBAR -->
                <div class="col-lg-3 col-md-4 col-sm-5 col-xs-12">
                    @include('theme.sidebars.full-sidebar')
                </div>
                <!-- SIDEBAR END -->
            </div><!-- ROW END -->
        </div>
    </div>
    <!-- single-post-page END -->
</div>
@include('theme.sections.media.partials.media_scripts')
@include('theme.sections.media.partials.video_gallery_load_more')
@endsection
