@extends('theme.layouts.master')

@section('content')
@inject('frontHelp', 'Library\FrontendHelperLib')


<div id="main-content-body">

<!-- single-post-page -->

    <div id="single_post_page">
        @include('theme.layouts.breadcrumbs')
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <br />
                    <h2 class="single-post-head" style="color:{{ $cat_dets['primary_color'] }}">{{ $cat_dets->name }}</h2>

                    <div class="post-info-line">{!! $cat_dets->description !!}</div>

                </div>
            </div>

            <div class="row">
                <!-- <div class="col-lg-9 col-md-8 col-sm-7 col-xs-12"> -->
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">

                    <div class="post-content clearfix dpContentEntry" data-dp-content-id="{{ $cat_dets->id }}">

                    @if(isset($categoryGalleries) && !empty($categoryGalleries))
                        @foreach($categoryGalleries as $key => $value)
                            <div class="col-lg-3 col-md-4 col-sm-6">
                                <a title="{{ $value['title'] }}" href="{{ $value['slug'] }}">
                                    @if (!empty($value['featured']) && isset($value['featured'][0]) && isset($value['featured'][0]['media']) && !empty($value['featured'][0]['media']))
                                    <?php $media = $value['featured'][0]['media']; ?>
                                    <img style="width:100%;" class="img-responsive center-block" alt="{{ $value->title }}" src="{{ $frontHelp->pImg($value, 'gallery-cover') }}" />
                                    <h2 class="gallery-list-title">{{ $value->title }}</h5>
                                    @else
                                    <img style="width:100%;" class="img-responsive center-block" alt="{{ $value->title }}" src="{{ $frontHelp->pImg($value, 'gallery-cover') }}" />
                                    <h2 style="color:{{ $cat_dets['primary_color'] }}" class="gallery-list-title">{{ $value->title }}</h5>

                                    @endif

                                </a>
                            </div>
                        @endforeach
                    @endif

                    </div>

               </div>
                <!-- SIDEBAR -->
                <!-- <div class="col-lg-3 col-md-4 col-sm-5 col-xs-12">

                    @include('theme.sidebars.banners-sidebar-gallery')
                    <br />
                    <br />

                </div> -->
                <!-- SIDEBAR END -->
            </div><!-- ROW END -->
        </div>
    </div>
    <!-- single-post-page END -->
</div>

@endsection
