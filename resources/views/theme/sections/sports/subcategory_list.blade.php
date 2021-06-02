@extends('theme.layouts.master')

@section('content')
@inject('frontHelp', 'Library\FrontendHelperLib')
<div id="main-content-body">
    <div id="sports_category_list">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="sp-categories-menu bg-color-sports">
                        @include('theme.sections.sports.partials.subcategories')
                    </div>
                </div>
            </div>
        </div>
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    @include('theme.sections.sports.partials.football_team_logos')
                </div>
            </div>
        </div>
    </div>
    <div id="category_list">
        <div class="container">
            <div class="row">
                <div class="col-lg-9 col-md-8 col-sm-7 col-xs-12">
                    <!-- CATEGORY LIST POSTS SECTION -->
                    <div class="posts-category-sections">
                        <div class="home-category-heading home-category-heading-generic section-heading-{{ $subcat_dets->id }}">
                            <div class="categ-text">
                                @if ($subcat_dets->type == 0)
                                    @if ($subcat_dets->primary_image)
                                    <img alt="{{ $subcat_dets->name }}" src="{{ $frontHelp->icon2x($subcat_dets->primary_image) }}" >
                                    @endif
                                    {{ $subcat_dets->name }}
                                @else
                                <i class="fa fa-hashtag"></i> {{ $subcat_dets->name }}
                                @endif
                            </div>
                        </div>

                        <div class="row" id="appendNewContent" data-dp-take="{{ $take }}" data-dp-total="{{ $cat_dets->total }}" data-dp-skip="{{ $list->count() }}" data-dp-section-id="{{ $subcat_dets->id }}">
                        @if (!empty($list))
                            @foreach ($list as $key => $row)
                                @if($key == 15)
                                    <!-- ADVERTISEMENT BANNER SECTION -->
                                    <div class="row">
                                        <div class="col-lg-12 hidden-md hidden-sm">
                                            <div class="home-middle-adv-banner">
                                                <!-- /17337359/Slot4 728x90 -->
                                                <div id='div-gpt-ad-1451989749397-3' style='height:90px; width:728px; margin: 0 auto;'>
                                                <script type='text/javascript'>
                                                googletag.cmd.push(function() { googletag.display('div-gpt-ad-1451989749397-3'); });
                                                </script>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- ADVERTISEMENT BANNER SECTION END -->
                                    @include('theme.partials.newsletter-block')
                                @elseif (($key == 3) || ($key == 9) || ($key == 13) || ($key == 18) || ($key == 24))
                                <div class="col-lg-4 col-md-6 col-sm-12">
                                    <div class="home-small-photo-tile">
                                        <div class="category-list-middle-adv">
                                            @if ($key == 3)
                                                <!-- /17337359/Slot3 300x250 -->
                                                <div id='div-gpt-ad-1451989749397-2' style='height:250px; width:300px;'>
                                                <script type='text/javascript'>
                                                googletag.cmd.push(function() { googletag.display('div-gpt-ad-1451989749397-2'); });
                                                </script>
                                                </div>
                                            @elseif ($key == 9)
                                                <!-- /17337359/Slot5 300x250 -->
                                                <div id='div-gpt-ad-1451989749397-4' style='height:250px; width:300px;'>
                                                <script type='text/javascript'>
                                                googletag.cmd.push(function() { googletag.display('div-gpt-ad-1451989749397-4'); });
                                                </script>
                                                </div>
                                            @elseif ($key == 13)
                                                <!-- /17337359/Slot7 300x250 -->
                                                <div id='div-gpt-ad-1451989749397-6' style='height:250px; width:300px;'>
                                                <script type='text/javascript'>
                                                googletag.cmd.push(function() { googletag.display('div-gpt-ad-1451989749397-6'); });
                                                </script>
                                                </div>
                                            @elseif ($key == 18)
                                                <!-- /17337359/Slot9 300x250 -->
                                                <div id='div-gpt-ad-1451989749397-8' style='height:250px; width:300px;'>
                                                <script type='text/javascript'>
                                                googletag.cmd.push(function() { googletag.display('div-gpt-ad-1451989749397-8'); });
                                                </script>
                                                </div>
                                            @elseif ($key == 24)
                                                <!-- /17337359/Slot11 300x250 -->
                                                <div id='div-gpt-ad-1451989749397-10' style='height:250px; width:300px;'>
                                                <script type='text/javascript'>
                                                googletag.cmd.push(function() { googletag.display('div-gpt-ad-1451989749397-10'); });
                                                </script>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                @endif
                                <div class="col-lg-4 col-md-6 col-sm-6 dpContentEntry" data-dp-content-id="{{ $row->id }}">
                                    <div class="home-small-photo-tile">
                                        <div class="home-small-photo">
                                            <a href="{{ $frontHelp->pSlug($row) }}" title="{{ $frontHelp->pField($row, 'title') }}" target="{{ $frontHelp->pTarget($row) }}">
                                                <img style="width:100%;" class="img-responsive center-block" alt="{{ $frontHelp->pField($row, 'title') }}" src="{{ $frontHelp->pImg($row, 'latest-posts') }}" />
                                            </a>
                                        </div>
                                        <span class="home-small-post-date">
                                            {{ $frontHelp->contentDate($row['published_at']) }}
                                        </span>
                                        <h2 class="home-small-post-sub-title">
                                            <a class="section-heading-{{ $subcat_dets->id }}>" href="{{ $frontHelp->pSlug($row) }}" title="{{ $frontHelp->pField($row, 'title') }}" target="{{ $frontHelp->pTarget($row) }}">
                                                {{ $frontHelp->truncateOnSpace($frontHelp->pField($row, 'header'), 100) }}
                                            </a>
                                        </h2>
                                        <h1 class="home-small-post-title">
                                            <a href="{{ $frontHelp->pSlug($row) }}" title="{{ $frontHelp->pField($row, 'title') }}" target="{{ $frontHelp->pTarget($row) }}">
                                                {{ $frontHelp->truncateOnSpace($frontHelp->pField($row, 'title'), 100) }}
                                            </a>
                                        </h1>
                                    </div>
                                </div>
                            @endforeach
                        @endif

                        @if ($cat_dets->total > 25)
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
                <!-- SIDEBAR -->
                <div class="col-lg-3 col-md-4 col-sm-5 col-xs-12">
                    @include('theme.sidebars.list-sidebar')
                </div>
                <!-- SIDEBAR END -->
            </div><!-- ROW END -->
        </div>
    </div>
    <!-- single-post-page END -->
</div>
@include('theme.sections.generic.category_list_load_more')
@endsection
