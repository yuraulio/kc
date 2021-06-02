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

        <div class="container">
            <!-- CAROUSEL -->
            <div id="cat-carousel">
                 <div class="row nopadding">
                    <div class="col-sm-12 col-md-9 col-md-push-3 col-lg-9 col-lg-push-3 nopadding">
                        <div class="carousel slide article-slide" id="article-photo-carousel" data-ride="carousel" data-interval="5000">
                            <!-- Wrapper for slides -->
                            <div class="carousel-inner cont-slider">
                            @if (!empty($sportsTopCarousel))
                                @foreach ($sportsTopCarousel as $key => $row)
                                <div class="item dpContentEntry <?php if ($key == 0) { echo 'active'; } ?>" data-dp-content-id="{{ $row['content_id'] }}">
                                    <a href="{{ $frontHelp->pSlug($row) }}" target="{{ $frontHelp->pTarget($row) }}" title="{{ $frontHelp->pField($row, 'title') }}">
                                        <img class="img-responsive" alt="{{ $frontHelp->pField($row, 'title') }}" src="{{ $frontHelp->pImg($row, 'top-carousel') }}" />
                                    </a>
                                    <div class="color-helper"></div>
                                    <div class="carousel-caption">
                                        <h2>
                                            <a href="{{ $frontHelp->pSlug($row) }}" target="{{ $frontHelp->pTarget($row) }}" title="{{ $frontHelp->pField($row, 'title') }}" class="carousel-head-link txt-color-sports">
                                                {{ $frontHelp->pField($row, 'title') }}
                                            </a>
                                        </h2>
                                        <h1 class="lead">
                                            <a href="{{ $frontHelp->pSlug($row) }}" target="{{ $frontHelp->pTarget($row) }}" class="carousel-link" title="{{ $frontHelp->pField($row, 'title') }}">
                                                {{ $frontHelp->truncateOnSpace($frontHelp->pField($row, 'excerpt'), 150) }}
                                            </a>
                                        </h1>
                                    </div>
                                </div>
                                @endforeach
                            @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-12 col-md-3 col-md-pull-9 col-lg-3 col-lg-pull-9 nopadding">
                        <!-- Indicators -->
                        <ol class="cat-carousel-indicators">
                        @if (!empty($sportsTopCarousel))
                            @foreach ($sportsTopCarousel as $key => $row)
                            <li class="<?php if ($key == 0) { echo 'active'; } ?>" data-slide-to="<?php echo $key; ?>" data-target="#article-photo-carousel">
                                <div class="tbl_wrap">
                                    <div class="tbl_img">
                                        <img class="hidden-xs" alt="{{ $frontHelp->pField($row, 'title') }}" src="{{ $frontHelp->pImg($row, 'new-top-carousel-side') }}">
                                    </div>
                                    <div class="tbl_img_film"></div>
                                    <div class="tbl_text">
                                        <span>

                                            <a href="#" title="{{ $frontHelp->pField($row, 'title') }}">{{ $frontHelp->truncateOnSpace($frontHelp->pField($row, 'title'), 50) }}</a>

                                        </span>

                                    </div>
                                </div>
                            </li>
                            @endforeach
                        @endif
                        </ol>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-9 col-md-8 col-sm-7 col-xs-12">
                    <!-- CATEGORY SPORTS POSTS SECTION -->
                    <div class="posts-category-sections">
                        <div class="row">
                            <div class="col-lg-4 col-md-6 col-sm-12">
                                <div class="sidebar-middle-adv" style="margin-top:0px;">
                                    <!-- /17337359/Slot3 300x250 -->
                                    <div id='div-gpt-ad-1451989749397-2' style='height:250px; width:300px;'>
                                    <script type='text/javascript'>
                                    googletag.cmd.push(function() { googletag.display('div-gpt-ad-1451989749397-2'); });
                                    </script>
                                    </div>
                                </div>

                                @if (!empty($list))
                                    @foreach ($list as $key => $row)
                                        @if ($key == 0)
                                        <div class="home-small-photo-tile dpContentEntry hidden-sm" data-dp-content-id="{{ $row->id }}">
                                            <div class="home-small-photo">
                                                <a target="{{ $frontHelp->pTarget($row) }}" title="{{ $frontHelp->pField($row, 'title') }}" href="{{ $frontHelp->pSlug($row) }}">
                                                    <img style="width:100%;" class="img-responsive center-block" alt="{{ $frontHelp->pField($row, 'title') }}" src="{{ $frontHelp->pImg($row, 'latest-posts') }}" />
                                                </a>
                                                <?php $pSubCat = $frontHelp->firstCatOfDepth($row, 1); ?>
                                                @if ($pSubCat)
                                                <div class="sp-post-badge bg-color-sports">
                                                    {{ $pSubCat }}
                                                </div>
                                                @endif
                                            </div>
                                            <span class="home-small-post-date">
                                                {{ $frontHelp->contentDate($row['published_at']) }}
                                            </span>
                                            <h2 class="home-small-post-sub-title">
                                                <a target="{{ $frontHelp->pTarget($row) }}" class="txt-color-sports" title="{{ $frontHelp->pField($row, 'title') }}" href="{{ $frontHelp->pSlug($row) }}">
                                                    {{ $frontHelp->pField($row, 'header') }}
                                                </a>
                                            </h2>
                                            <h1 class="home-small-post-title">
                                                <a target="{{ $frontHelp->pTarget($row) }}" title="{{ $frontHelp->pField($row, 'title') }}" href="{{ $frontHelp->pSlug($row) }}">
                                                    {{ $frontHelp->pField($row, 'title') }}
                                                </a>
                                            </h1>
                                        </div>
                                        <?php break; ?>
                                        @endif
                                    @endforeach
                                @endif
                            </div>

                            <div class="col-lg-4 col-md-3 col-sm-6">
                            @if (!empty($list))
                                @foreach ($list as $key => $row)
                                    @if ($key == 1)
                                    <div class="home-small-photo-tile dpContentEntry" data-dp-content-id="{{ $row->id }}">
                                        <div class="home-small-photo">
                                            <a target="{{ $frontHelp->pTarget($row) }}" title="{{ $frontHelp->pField($row, 'title') }}" href="{{ $frontHelp->pSlug($row) }}">
                                                <img style="width:100%;" class="img-responsive center-block" alt="{{ $frontHelp->pField($row, 'title') }}" src="{{ $frontHelp->pImg($row, 'latest-posts') }}" />
                                            </a>
                                            <?php $pSubCat = $frontHelp->firstCatOfDepth($row, 1); ?>
                                            @if ($pSubCat)
                                            <div class="sp-post-badge bg-color-sports">
                                                {{ $pSubCat }}
                                            </div>
                                            @endif
                                        </div>
                                        <span class="home-small-post-date">
                                            {{ $frontHelp->contentDate($row['published_at']) }}
                                        </span>
                                        <h2 class="home-small-post-sub-title">
                                            <a target="{{ $frontHelp->pTarget($row) }}" class="txt-color-sports" title="{{ $frontHelp->pField($row, 'title') }}" href="{{ $frontHelp->pSlug($row) }}">
                                                {{ $frontHelp->pField($row, 'header') }}
                                            </a>
                                        </h2>
                                        <h1 class="home-small-post-title">
                                            <a target="{{ $frontHelp->pTarget($row) }}" title="{{ $frontHelp->pField($row, 'title') }}" href="{{ $frontHelp->pSlug($row) }}">
                                                {{ $frontHelp->pField($row, 'title') }}
                                            </a>
                                        </h1>
                                        <a target="{{ $frontHelp->pTarget($row) }}" title="{{ $frontHelp->pField($row, 'title') }}" href="{{ $frontHelp->pSlug($row) }}">
                                            {{ $frontHelp->truncateOnSpace($frontHelp->pField($row, 'body'), 350) }}
                                        </a>
                                    </div>
                                    <?php break; ?>
                                    @endif
                                @endforeach
                            @endif
                            </div>
                            <!-- CATEGORY sports SIDEBAR RIGHT -->
                            <div class="col-lg-4 col-md-3 col-sm-6">
                            @if (!empty($list))
                                @foreach ($list as $key => $row)
                                    @if (($key >= 2) && ($key <= 3))
                                    <div class="home-small-photo-tile dpContentEntry" data-dp-content-id="{{ $row->id }}">
                                        <div class="home-small-photo">
                                            <a target="{{ $frontHelp->pTarget($row) }}" title="{{ $frontHelp->pField($row, 'title') }}" href="{{ $frontHelp->pSlug($row) }}">
                                                <img style="width:100%;" class="img-responsive center-block" alt="{{ $frontHelp->pField($row, 'title') }}" src="{{ $frontHelp->pImg($row, 'latest-posts') }}" />
                                            </a>
                                            <?php $pSubCat = $frontHelp->firstCatOfDepth($row, 1); ?>
                                            @if ($pSubCat)
                                            <div class="sp-post-badge bg-color-sports">
                                                {{ $pSubCat }}
                                            </div>
                                            @endif
                                        </div>
                                        <span class="home-small-post-date">
                                            {{ $frontHelp->contentDate($row['published_at']) }}
                                        </span>
                                        <h2 class="home-small-post-sub-title">
                                            <a target="{{ $frontHelp->pTarget($row) }}" class="txt-color-sports" title="{{ $frontHelp->pField($row, 'title') }}" href="{{ $frontHelp->pSlug($row) }}">
                                                {{ $frontHelp->pField($row, 'header') }}
                                            </a>
                                        </h2>
                                        <h1 class="home-small-post-title">
                                            <a target="{{ $frontHelp->pTarget($row) }}" title="{{ $frontHelp->pField($row, 'title') }}" href="{{ $frontHelp->pSlug($row) }}">
                                                {{ $frontHelp->pField($row, 'title') }}
                                            </a>
                                        </h1>
                                    </div>
                                    @elseif ($key > 3)
                                    <?php break; ?>
                                    @else
                                    @endif
                                @endforeach
                            @endif
                            </div>
                        </div>

                        <div class="row">
                            <!-- LARGE PHOTO POST -->
                            <div class="col-lg-12 col-md-12 col-sm-12">
                                <div class="home-small-photo" style="margin-bottom: 20px; margin-top:20px;">
                                    <div class="color-helper"></div>
                                    @if (!empty($sportsFeatured))
                                        @foreach ($sportsFeatured as $key => $row)
                                        <a target="{{ $frontHelp->pTarget($row) }}" title="{{ $frontHelp->pField($row, 'title') }}" href="{{ $frontHelp->pSlug($row) }}">
                                            <img style="width:100%;" class="img-responsive center-block" alt="{{ $frontHelp->pField($row, 'title') }}" src="{{ $frontHelp->pImg($row, 'sports-featured') }}">
                                        </a>
                                        <?php $pSubCat = $frontHelp->firstCatOfDepth($row, 1); ?>
                                        @if ($pSubCat)
                                        <div class="sp-post-badge bg-color-sports">
                                            {{ $pSubCat }}
                                        </div>
                                        @endif
                                        <div class="sport-half-text dpContentEntry" data-dp-content-id="{{ $row['content_id'] }}">
                                            <h2 class="home-small-post-sub-title">
                                                <a target="{{ $frontHelp->pTarget($row) }}" class="txt-color-sports" title="{{ $frontHelp->pField($row, 'title') }}" href="{{ $frontHelp->pSlug($row) }}">
                                                    {{ $frontHelp->pField($row, 'header') }}
                                                </a>
                                            </h2>
                                            <h1 class="home-small-post-title">
                                                <a target="{{ $frontHelp->pTarget($row) }}" title="{{ $frontHelp->pField($row, 'title') }}" href="{{ $frontHelp->pSlug($row) }}">
                                                    {{ $frontHelp->pField($row, 'title') }}
                                                </a>
                                            </h1>
                                        </div>
                                        @endforeach
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="bottom-full-width-posts">

                            <div class="row">
                                <!-- CATEGORY sports ΒΟΤΤΟΜ 4 POSTS -->
                                <!-- <div class="col-lg-3 col-md-6 col-sm-6">
                                    <div class="sidebar-middle-adv" style="margin-top:0px;">
                                        SLOT XXX
                                        <a title="ADV TITLE" href="#">
                                            <img style="width:100%;" class="img-responsive center-block" alt="Post Title Here" src="theme/assets/img/related-banner.jpg" />
                                        </a>
                                    </div>
                                </div>
                                -->
                                @if (!empty($list))
                                    @foreach ($list as $key => $row)
                                        @if (($key >= 4) && ($key <= 7))
                                        <div class="col-lg-3 col-md-2 col-sm-6 dpContentEntry" data-dp-content-id="{{ $row->id }}">
                                            <div class="home-small-photo-tile sports_special_height">
                                                <div class="home-small-photo">
                                                    <a target="{{ $frontHelp->pTarget($row) }}" title="{{ $frontHelp->pField($row, 'title') }}" href="{{ $frontHelp->pSlug($row) }}">
                                                        <img style="width:100%;" class="img-responsive center-block" alt="{{ $frontHelp->pField($row, 'title') }}" src="{{ $frontHelp->pImg($row, 'latest-posts') }}" />
                                                    </a>
                                                    <?php $pSubCat = $frontHelp->firstCatOfDepth($row, 1); ?>
                                                    @if ($pSubCat)
                                                    <div class="sp-post-badge bg-color-sports">
                                                        {{ $pSubCat }}
                                                    </div>
                                                    @endif
                                                </div>
                                                <span class="home-small-post-date">
                                                    {{ $frontHelp->contentDate($row['published_at']) }}
                                                </span>
                                                <h2 class="home-small-post-sub-title">
                                                    <a target="{{ $frontHelp->pTarget($row) }}" class="txt-color-sports" title="{{ $frontHelp->pField($row, 'title') }}" href="{{ $frontHelp->pSlug($row) }}">
                                                        {{ $frontHelp->pField($row, 'header') }}
                                                    </a>
                                                </h2>
                                                <h1 class="home-small-post-title">
                                                    <a target="{{ $frontHelp->pTarget($row) }}" title="{{ $frontHelp->pField($row, 'title') }}" href="{{ $frontHelp->pSlug($row) }}">
                                                        {{ $frontHelp->pField($row, 'title') }}
                                                    </a>
                                                </h1>
                                            </div>
                                        </div>
                                        @elseif ($key > 7)
                                        <?php break; ?>
                                        @else
                                        @endif
                                    @endforeach
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- ADVERTISEMENT BANNER SECTION -->
                    @include('theme.home.partials.home-advertisement_1')
                    <!-- ADVERTISEMENT BANNER SECTION END -->

                    <!-- NEWSLETTER block 930x185-->
                    @include('theme.partials.newsletter-block')

                    @include('theme.sections.sports.partials.sports_photo_carousel')

                    @if (!empty($list))
                        @foreach ($list as $key => $row)
                            @if ($key == 8)
                            <div class="row dpContentEntry" data-dp-content-id="{{ $row->id }}">
                                <div class="col-lg-8 col-md-8 col-sm-12">
                                    <div class="home-small-photo-tile">
                                        <div class="home-small-photo">
                                            <a target="{{ $frontHelp->pTarget($row) }}" title="{{ $frontHelp->pField($row, 'title') }}" href="{{ $frontHelp->pSlug($row) }}">
                                                <img style="width:100%;" class="img-responsive center-block alone-row" alt="{{ $frontHelp->pField($row, 'title') }}" src="{{ $frontHelp->pImg($row, 'international-featured') }}" />
                                            </a>
                                            <?php $pSubCat = $frontHelp->firstCatOfDepth($row, 1); ?>
                                            @if ($pSubCat)
                                            <div class="sp-post-badge bg-color-sports">
                                                {{ $pSubCat }}
                                            </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-4 col-md-4 col-sm-12">
                                    <div class="home-small-photo-tile">
                                        <span class="home-small-post-date">
                                            {{ $frontHelp->contentDate($row['published_at']) }}
                                        </span>
                                        <h2 class="home-small-post-sub-title">
                                            <a target="{{ $frontHelp->pTarget($row) }}" class="txt-color-sports" title="{{ $frontHelp->pField($row, 'title') }}" href="{{ $frontHelp->pSlug($row) }}">
                                                {{ $frontHelp->pField($row, 'header') }}
                                            </a>
                                        </h2>
                                        <h1 class="home-small-post-title">
                                            <a target="{{ $frontHelp->pTarget($row) }}" title="{{ $frontHelp->pField($row, 'title') }}" href="{{ $frontHelp->pSlug($row) }}">
                                                {{ $frontHelp->pField($row, 'title') }}
                                            </a>
                                        </h1>
                                        <a class="category_lead_text" target="{{ $frontHelp->pTarget($row) }}" title="{{ $frontHelp->pField($row, 'title') }}" href="{{ $frontHelp->pSlug($row) }}">
                                            {{ $frontHelp->truncateOnSpace($frontHelp->pField($row, 'body'), 500) }}
                                        </a>
                                    </div>
                                </div>
                            </div>
                            @elseif ($key > 8)
                            <?php break; ?>
                            @else
                            @endif
                        @endforeach
                    @endif


                    <div class="row">
                        <div class="col-lg-4 col-md-3 col-sm-12">
                        @if (!empty($list))
                            @foreach ($list as $key => $row)
                                @if ($key == 9)
                                <div class="home-small-photo-tile dpContentEntry" data-dp-content-id="{{ $row->id }}">
                                    <div class="home-small-photo">
                                        <a target="{{ $frontHelp->pTarget($row) }}" title="{{ $frontHelp->pField($row, 'title') }}" href="{{ $frontHelp->pSlug($row) }}">
                                            <img style="width:100%;" class="img-responsive center-block" alt="{{ $frontHelp->pField($row, 'title') }}" src="{{ $frontHelp->pImg($row, 'latest-posts') }}" />
                                        </a>
                                        <?php $pSubCat = $frontHelp->firstCatOfDepth($row, 1); ?>
                                        @if ($pSubCat)
                                        <div class="sp-post-badge bg-color-sports">
                                            {{ $pSubCat }}
                                        </div>
                                        @endif
                                    </div>
                                    <h1 class="home-small-post-title">
                                        <a target="{{ $frontHelp->pTarget($row) }}" title="{{ $frontHelp->pField($row, 'title') }}" href="{{ $frontHelp->pSlug($row) }}">
                                            {{ $frontHelp->pField($row, 'title') }}
                                        </a>
                                    </h1>
                                </div>
                                @elseif ($key > 9)
                                <?php break; ?>
                                @else
                                @endif
                            @endforeach
                        @endif
                        </div>

                        <div class="col-lg-4 col-md-6 col-sm-12">
                            <div class="sidebar-middle-adv" style="margin-top:0px;">
                                <!-- /17337359/Slot6 300x250 -->
                                <div id='div-gpt-ad-1451989749397-5' style='height:250px; width:300px;'>
                                <script type='text/javascript'>
                                googletag.cmd.push(function() { googletag.display('div-gpt-ad-1451989749397-5'); });
                                </script>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-4 col-md-3 col-sm-12">
                        @if (!empty($list))
                            @foreach ($list as $key => $row)
                                @if ($key == 10)
                                <div class="home-small-photo-tile dpContentEntry" data-dp-content-id="{{ $row->id }}">
                                    <div class="home-small-photo">
                                        <a target="{{ $frontHelp->pTarget($row) }}" title="{{ $frontHelp->pField($row, 'title') }}" href="{{ $frontHelp->pSlug($row) }}">
                                            <img style="width:100%;" class="img-responsive center-block" alt="{{ $frontHelp->pField($row, 'title') }}" src="{{ $frontHelp->pImg($row, 'latest-posts') }}" />
                                        </a>
                                        <?php $pSubCat = $frontHelp->firstCatOfDepth($row, 1); ?>
                                        @if ($pSubCat)
                                        <div class="sp-post-badge bg-color-sports">
                                            {{ $pSubCat }}
                                        </div>
                                        @endif
                                    </div>
                                    <h1 class="home-small-post-title">
                                        <a target="{{ $frontHelp->pTarget($row) }}" title="{{ $frontHelp->pField($row, 'title') }}" href="{{ $frontHelp->pSlug($row) }}">
                                            {{ $frontHelp->pField($row, 'title') }}
                                        </a>
                                    </h1>
                                </div>
                                 @elseif ($key > 10)
                                <?php break; ?>
                                @else
                                @endif
                            @endforeach
                        @endif
                        </div>
                    </div>

                    <div id="appendNewContent" data-dp-take="{{ $take }}" data-dp-total="{{ $cat_dets->total }}" data-dp-skip="{{ $list->count() }}" data-dp-section-id="{{ $cat_dets->id }}" class="sportsAppendMore">
                        @if (!empty($list))
                        @foreach ($list as $key => $row)
                            @if ($key > 10)
                            <div class="last-post-list dpContentEntry" data-dp-content-id="{{ $row->id }}">
                                <div class="row">
                                    <!-- CATEGORY sports SIDEBAR LEFT -->
                                    <div class="col-lg-5 col-md-5 col-sm-12">
                                        <div class="home-small-photo-tile">
                                            <div class="home-small-photo lifestyle_img_no_marging">
                                                <a target="{{ $frontHelp->pTarget($row) }}" title="{{ $frontHelp->pField($row, 'title') }}" href="{{ $frontHelp->pSlug($row) }}">
                                                    <img style="width:100%;" class="img-responsive center-block" alt="{{ $frontHelp->pField($row, 'title') }}" src="{{ $frontHelp->pImg($row, 'tvone-suggests') }}" />
                                                </a>
                                                <?php $pSubCat = $frontHelp->firstCatOfDepth($row, 1); ?>
                                                @if ($pSubCat)
                                                <div class="sp-post-badge bg-color-sports">
                                                    {{ $pSubCat }}
                                                </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-7 col-md-7 col-sm-12">
                                        <div class="home-small-photo-tile last-post-list-post lifestyle_fix_spacing">
                                            <span class="home-small-post-date">
                                                {{ $frontHelp->contentDate($row['published_at']) }}
                                            </span>
                                            <h2 class="home-small-post-sub-title">
                                                <a target="{{ $frontHelp->pTarget($row) }}" class="txt-color-sports" title="{{ $frontHelp->pField($row, 'title') }}" href="{{ $frontHelp->pSlug($row) }}">
                                                    {{ $frontHelp->pField($row, 'header') }}
                                                </a>
                                            </h2>
                                            <h1 class="home-small-post-title">
                                                <a target="{{ $frontHelp->pTarget($row) }}" title="{{ $frontHelp->pField($row, 'title') }}" href="{{ $frontHelp->pSlug($row) }}">
                                                    {{ $frontHelp->pField($row, 'title') }}
                                                </a>
                                            </h1>
                                            <a class="category_lead_text" target="{{ $frontHelp->pTarget($row) }}" title="{{ $frontHelp->pField($row, 'title') }}" href="{{ $frontHelp->pSlug($row) }}">
                                                {{ $frontHelp->truncateOnSpace($frontHelp->pField($row, 'body'), 200) }}
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endif
                        @endforeach
                        @endif
                        @if ($cat_dets->total > 14)
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
                    <!-- CATEGORY sports POSTS SECTION END -->
                </div>
                <!-- SIDEBAR -->
                <div class="col-lg-3 col-md-4 col-sm-5 col-xs-12">
                    @include('theme.sidebars.sports-sidebar')
                </div>
                <!-- SIDEBAR END -->
            </div>
        </div>
    </div>
</div>
@include('theme.sections.sports.partials.category_sports_load_more')
@endsection
