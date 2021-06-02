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
                    @if (!empty($videoFeatured))
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="home-featured-video-placeholder">
                                <div id="videoCarousel" class="carousel slide">
                                    <div class="carousel-inner">
                                        @foreach ($videoFeatured as $key => $row)
                                        <div class="item dpContentEntry <?php if ($key == 0) { echo 'active'; } ?>" data-dp-content-id="{{ $row['content_id'] }}">
                                            <div class="video-container">
                                                <!--a target="{{ $frontHelp->pTarget($row) }}" title="{{ $frontHelp->pField($row, 'title') }}" href="{{ $frontHelp->pSlug($row) }}">
                                                    <img style="width:100%;" class="img-responsive center-block" alt="{{ $frontHelp->pField($row, 'title') }}" src="{{ $frontHelp->pImg($row, 'video-featured') }}">
                                                </a-->
                                                {!! $frontHelp->pField($row, 'summary') !!}
                                            </div>
                                            <div class="carousel-caption">
                                                {{ $frontHelp->pField($row, 'title') }}
                                            </div>
                                        </div>
                                        @endforeach
                                        @if (count($videoFeatured) > 1)
                                        <div class="home-video-controls">
                                            <a class="left carousel-control" href="#videoCarousel" data-slide="prev">
                                                <span class="glyphicon glyphicon-chevron-left"></span>
                                            </a>
                                            <div id="caption-holder"></div>
                                            <a class="right carousel-control" href="#videoCarousel" data-slide="next">
                                                <span class="glyphicon glyphicon-chevron-right"></span>
                                            </a>
                                        </div>
                                        @endif
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
                            @if (!empty($list))
                                @foreach ($list as $key => $row)
                                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 nopadding dpContentEntry" data-dp-content-id="{{ $row['id'] }}">
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
                            @else
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <div class="no_content">
                                        Δεν βρέθηκε περιέχομενο
                                    </div>
                                </div>
                            @endif
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
                    @include('theme.sidebars.media-sidebar')
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
