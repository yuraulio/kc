@extends('theme.layouts.master')

@section('content')
@inject('frontHelp', 'Library\FrontendHelperLib')
@include('theme.preview.preview_warning', ["id" => $content->id, "type" => "content", "status" => $content->status])

@if ($parent_cat == 0)
<div id="page-banner">
    <?php  if (isset($categoryBanner) && count($categoryBanner) > 0) : ?>

    <?php foreach ($categoryBanner as $key => $value) : ?>
        <img alt="About" src="/uploads/categories/<?php echo $value['catid'] . '/' . $value['truefilename']; ?>" class="img-responsive center-block" />
    <?php endforeach; ?>

    <?php else : ?>

        <img alt="" src="assets/img/banners/NEWS.jpg" class="img-responsive center-block" />

    <?php endif; ?>
    <!-- <img alt="" src="assets/img/banners/news_banner.jpg" class="img-responsive center-block" />
    <div class="container" id="banner-caption">
        <div class="row">
            <div class="col-lg-12">
                <h3 class="introtextpages-center animatable fadeInUp">NEWS CENTER - Articles Worth Reading</h3>
            </div>
        </div>
    </div> -->
</div>
@else
<div id="page-banner">

    <?php  if (isset($categoryBannerCS) && count($categoryBannerCS) > 0) : ?>

    <?php foreach ($categoryBannerCS as $key => $value) : ?>
        <img alt="About" src="/uploads/categories/<?php echo $value['catid'] . '/' . $value['truefilename']; ?>" class="img-responsive center-block" />
    <?php endforeach; ?>

    <?php else : ?>

        <img alt="" src="assets/img/banners/CASE_STUDY.jpg" class="img-responsive center-block" />

    <?php endif; ?>



    <!-- <img alt="" src="assets/img/banners/about_banner.jpg" class="img-responsive center-block" />
    <div class="container" id="banner-caption">
        <div class="row">
            <div class="col-lg-12">
                <h3 class="introtextpages animatable fadeInUp">CASE STUDIES</h3>
            </div>
        </div>
    </div> -->
</div>
@endif

   <div id="main-content-body">

    <!-- single-post-page -->

        <div id="single_post">
            <div class="container">
                <div class="row">
                    <div class="col-lg-4 col-md-5 col-sm-6 sidebar">
                        <div class="news-sidebar-menu">
                            @if ($parent_cat == 0)
                            <h1 class="side-head-neg c-single-post">Articles Worth Reading</h1>
                            <div id="newsmenu">
                                <div class="list-group panel">

                                @if (!empty($subMenuTree))
                                    <?php $idx = 0; ?>
                                    @foreach ($subMenuTree as $key => $row)

                                        @if ($row->type == 0)
                                            @if ($row->depth == 1)

                                                @if ($row->children->isEmpty())

                                                    <a href="#news{{ $row->name }}" class="list-group-item list-group-item" data-toggle="collapse" data-parent="#newsmenu">{{ $row->name }}</a>

                                                    <div class="collapse @if ($idx == 0) in @endif newsgroup" id="news{{ $row->name }}">

                                                    @if (!empty($newsSection))
                                                        @foreach ($newsSection as $skey => $srow)
                                                            @if (!empty($srow['content']['categories']))
                                                                @foreach ($srow['content']['categories'] as $ckey => $crow)
                                                                    @if ($crow['depth'] == 1)
                                                                        @if ($crow['id'] == $row->id)

                                                                                <a href="/{{ $frontHelp->pField($srow['content'], 'slug') }}" class="list-group-item subme @if ($frontHelp->pSlug($srow) == '/'.Request::segment(1) ) active @endif">{{ $frontHelp->pField($srow, 'title') }}</a>

                                                                        @endif
                                                                    @endif
                                                                @endforeach
                                                            @endif
                                                        @endforeach
                                                    @endif


                                                    </div>

                                                @endif
                                            @endif

                                            @if ($row->depth == 1)
                                            <!-- </div> -->
                                            @endif

                                        @endif
                                        <?php $idx++; ?>
                                    @endforeach
                                @endif

                                </div>
                            </div>
                            @else
                            <!-- <h1 class="side-head-neg c-single-post">CASE STUDIES</h1> -->
                            <h1 class="auto-head">CASE STUDIES</h1>
                            <div id="newsmenu">
                                <div class="list-group panel">

                                @if (!empty($subMenuTreeCases))
                                    <?php $idx = 0; ?>
                                    @foreach ($subMenuTreeCases as $key => $row)

                                        @if ($row->type == 0)
                                            @if ($row->depth == 1)

                                                @if ($row->children->isEmpty())

                                                    <a href="#case{{ $row->name }}" class="list-group-item list-group-item" data-toggle="collapse" data-parent="#newsmenu">{{ $row->name }}</a>

                                                    <div class="collapse @if ($idx == 0) in @endif newsgroup" id="case{{ $row->name }}">

                                                    @if (!empty($casestudiesSection))
                                                        @foreach ($casestudiesSection as $skey => $srow)
                                                            @if (!empty($srow['content']['categories']))
                                                                @foreach ($srow['content']['categories'] as $ckey => $crow)
                                                                    @if ($crow['depth'] == 1)
                                                                        @if ($crow['id'] == $row->id)

                                                                                <a href="/{{ $frontHelp->pField($srow['content'], 'slug') }}" class="list-group-item subme @if ($frontHelp->pSlug($srow) == '/'.Request::segment(1) ) active @endif">{{ $frontHelp->pField($srow, 'title') }}</a>

                                                                        @endif
                                                                    @endif
                                                                @endforeach
                                                            @endif
                                                        @endforeach
                                                    @endif


                                                    </div>

                                                @endif
                                            @endif

                                            @if ($row->depth == 1)
                                            <!-- </div> -->
                                            @endif

                                        @endif
                                        <?php $idx++; ?>
                                    @endforeach
                                @endif

                                </div>
                            </div>


                            @endif


                        </div>

                        @if ($parent_cat == 1)

                        <br /> <br />

                            <div class="no-extra-buttons">
                                <a href="case-studies/corporate-tax-law">
                                <img src="theme/assets/img/corporatetaxlaw.png" />CORPORATE &amp; TAX LAW
                                </a>
                            </div>


                            <div class="no-extra-buttons">
                                <a href="case-studies/faqs">
                                    <img src="theme/assets/img/FAQS.png" />
                                    FAQS
                                </a>

                            </div>
                            @endif


                    </div>

                    <div class="col-lg-8 col-md-7 col-sm-6">

                        <div class="post-date"><span>{{ $frontHelp->contentDate($content['published_at']) }}</span></div>
                        <h1 class="single-post-head">{{ $content->title }}</h1>
                        <div class="post-info-line">
                           {{ $content->subtitle }}

                        </div>

                        @if (!empty($content['featured']) && isset($content['featured'][0]) &&isset($content['featured'][0]['media']) && !empty($content['featured'][0]['media']))
                        <?php $media = $content['featured'][0]['media']; ?>
                        <img style="width:100%; margin-top: 14px; margin-bottom: 14px;" class="img-responsive center-block" alt="{{ $content->title }}" src="{{ $frontHelp->pImg($content, 'main') }}" />

                        @endif

                        <div class="post-social-line clearfix">
                            <div class="sharestory">
                                SHARE THIS STORY

                            </div>

                            <div class="sharestorylinks">
                                <div class="social-circle">
                                    <a target="_blank" href="http://www.facebook.com/sharer.php?u={{ Request::fullUrl() }}" onclick="javascript:window.open(this.href,
                      '', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=600,width=600');return false;">
                                        <i class="fa fa-facebook"></i>
                                    </a>
                                </div>
                                <div class="social-circle">
                                    <a target="_blank" href="https://plus.google.com/share?url={{ Request::fullUrl() }}" onclick="javascript:window.open(this.href,
                  '', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=600,width=600');return false;">
                                        <i class="fa fa-google-plus"></i>
                                    </a>
                                </div>
                                <div class="social-circle">
                                    <a target="_blank" href="http://twitter.com/share?text=Darkpony&amp;url={{ Request::fullUrl() }}" onclick="javascript:window.open(this.href,
                  '', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=600,width=600');return false;">
                                        <i class="fa fa-twitter"></i>
                                    </a>
                                </div>

                            </div>
                        </div>

                        <div class="post-content clearfix">
                            {!! $content->body !!}

                        </div>

                        <div class="post-social-line clearfix">
                            <div class="sharestory">
                                SHARE THIS STORY

                            </div>

                            <div class="sharestorylinks">
                                <div class="social-circle">
                                    <a target="_blank" href="http://www.facebook.com/sharer.php?u={{ Request::fullUrl() }}" onclick="javascript:window.open(this.href,
                      '', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=600,width=600');return false;">
                                        <i class="fa fa-facebook"></i>
                                    </a>
                                </div>
                                <div class="social-circle">
                                    <a target="_blank" href="https://plus.google.com/share?url={{ Request::fullUrl() }}" onclick="javascript:window.open(this.href,
                  '', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=600,width=600');return false;">
                                        <i class="fa fa-google-plus"></i>
                                    </a>
                                </div>
                                <div class="social-circle">
                                    <a target="_blank" href="http://twitter.com/share?text=Darkpony&amp;url={{ Request::fullUrl() }}" onclick="javascript:window.open(this.href,
                  '', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=600,width=600');return false;">
                                        <i class="fa fa-twitter"></i>
                                    </a>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- single-post-page END -->
   </div>


@include('theme.home.partials.newsletter')
@endsection
