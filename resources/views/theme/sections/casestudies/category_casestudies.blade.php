@extends('theme.layouts.master')

@section('content')
@inject('frontHelp', 'Library\FrontendHelperLib')

<div id="page-banner">
    <?php  if (isset($categoryBanner) && count($categoryBanner) > 0) : ?>

    <?php foreach ($categoryBanner as $key => $value) : ?>
        <img alt="About" src="/uploads/categories/<?php echo $value['catid'] . '/' . $value['truefilename']; ?>" class="img-responsive center-block" />
    <?php endforeach; ?>

    <?php else : ?>

        <img alt="" src="assets/img/banners/CASE_STUDY.jpg" class="img-responsive center-block" />

    <?php endif; ?>

    <!-- <div class="container" id="banner-caption">
        <div class="row">
            <div class="col-lg-12">
                <h3 class="introtextpages with-q animatable fadeInUp">
                We all need people who will give us feedback.<br /> That's how we improve.</h3>
            </div>
        </div>
    </div> -->
</div>

<div class="page-content">

    <div class="container">

        <div class="row">
            <div class="col-lg-4 col-md-5 col-sm-6">

                <div class="tabs-sidebar-menu">
                    <h1 class="auto-head">CASE STUDIES</h1>
                    <ul class="nav nav-pills nav-stacked" role="tablist">
                        <li role="presentation" class="@if ($uri_1 == 'corporate-tax-law') active @endif"><a href="#tab1" aria-controls="tab1" role="tab" data-toggle="tab">
                        - Corporate &amp; Tax Law</a></li>
                        <li role="presentation" class="@if ($uri_1 == 'faqs') active @endif"><a href="#tab2" aria-controls="tab2" role="tab" data-toggle="tab">
                        - FAQs</a></li>
                        <li role="presentation" class="@if ($uri_1 == '') active @endif"><a href="#tab3" aria-controls="tab3" role="tab" data-toggle="tab">
                        - Case Studies</a></li>

                     </ul>

                </div>
            </div>
            <div class="col-lg-8 col-md-7 col-sm-6">

                <div class="tab-content">
                     <div role="tabpanel" class="tab-pane fade @if ($uri_1 == 'corporate-tax-law') in active @endif" id="tab1">
                        <h1>Corporate &amp; Tax Law</h1>
                        @if (!empty($corporatetaxlawSection))
                            @foreach ($corporatetaxlawSection as $key => $row)
                                {!! $frontHelp->pField($row, 'body') !!}

                            @endforeach
                        @endif
                    </div>

                    <div role="tabpanel" class="tab-pane fade @if ($uri_1 == 'faqs') in active @endif" id="tab2">
                        <h1>FAQs</h1>
                        <div class="accordion the_collapse" id="accordion2">
                            <div class="accordion-group">
                                @if (!empty($faqsSection))
                                    @foreach ($faqsSection as $key => $row)
                                        <div class="accordion-heading">
                                            <a class="accordion-toggle faquestion" data-toggle="collapse" data-parent="#accordion{{ $key }}" href="#collapse_{{ $key }}">
                                                {{ $frontHelp->pField($row, 'title') }}

                                            </a>
                                        </div>
                                        <div id="collapse_{{ $key }}" class="accordion-body theanswer collapse">
                                            <div class="accordion-inner">
                                            {!! $frontHelp->pField($row, 'body') !!}
                                            </div>
                                        </div>
                                    @endforeach
                                @endif

                            </div>
                        </div>

                    </div>
                    <div role="tabpanel" class="tab-pane fade @if ($uri_1 == '') in active @endif" id="tab3">
                        <!-- <h1>Case Studies</h1> -->

                        @if (!empty($casestudiesSection))
                            @foreach ($casestudiesSection as $key => $row)
                                <h1><a href="{{ $frontHelp->pSlug($row) }}" title="{{ $frontHelp->pField($row, 'title') }}">{{ $frontHelp->pField($row, 'title') }}</a></h1>
                                {!! $frontHelp->truncateOnSpace($frontHelp->pField($row, 'body'), 150) !!}
                                <p class="mobileR">
                                    <a class="read-more-news" title="{{ $frontHelp->pField($row, 'title') }}" href="{{ $frontHelp->pSlug($row) }}">- continue reading</a>
                                </p>
                                <hr />
                            @endforeach
                        @endif

                    </div>

                </div>


            </div>
        </div>
    </div>
</div>

@include('theme.home.partials.newsletter')
@endsection
