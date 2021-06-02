@extends('theme.layouts.master')

@section('content')
@inject('frontHelp', 'Library\FrontendHelperLib')

<div id="page-banner">
    <?php  if (isset($categoryBanner) && count($categoryBanner) > 0) : ?>

    <?php foreach ($categoryBanner as $key => $value) : ?>
        <img alt="About" src="/uploads/categories/<?php echo $value['catid'] . '/' . $value['truefilename']; ?>" class="img-responsive center-block" />
    <?php endforeach; ?>

    <?php else : ?>

        <img alt="" src="assets/img/banners/SERVICES.jpg" class="img-responsive center-block" />

    <?php endif; ?>

    <!-- <div class="container" id="banner-caption">
        <div class="row">
            <div class="col-lg-12">
                <h3 class="introtextpages animatable fadeInUp">Complex Challenges<br /> Demand Flexible Solutions</h3>
            </div>
        </div>
    </div> -->
</div>

<div class="page-content">
    <div class="container">
        <div class="row">
            <div class="col-lg-4 col-md-5 col-sm-6">
                <div class="tabs-sidebar-menu">
                    <h1 class="side-head-neg c-oursectors">Our Sectors</h1>
                    <ul class="nav nav-pills nav-stacked" role="tablist">
                    @if (!empty($sectorsSection))
                        @foreach ($sectorsSection as $key => $row)
                        <li role="presentation" class="@if (Request::segment(2) != '') @if ($frontHelp->pSlug($row) == '/' .  Request::segment(2) ) active @endif @else
                     @if ($key == 0)active @endif @endif"><a href="#tab{{ $key }}" aria-controls="tab{{ $key }}" role="tab" data-toggle="tab">
                        {{ $frontHelp->pField($row, 'subtitle') }}</a></li></a></li>
                        @endforeach
                    @endif
                    </ul>
                </div>
            </div>
            <div class="col-lg-8 col-md-7 col-sm-6">
                <div class="tab-content">
                @if (!empty($sectorsSection))
                        @foreach ($sectorsSection as $key => $row)
                        <div role="tabpanel" class="tab-pane fade @if (Request::segment(2) != '') @if ($frontHelp->pSlug($row) == '/' .  Request::segment(2) ) in active @endif @else
                     @if ($key == 0) in active @endif @endif" id="tab{{ $key }}">
                            <h1 class="bebig">
                            <!--@if (isset($row['content']) && isset($row['content']['categories']) && !empty($row['content']['categories']))
                                @foreach ($row['content']['categories'] as $category)
                                    @if ($category['primary_image'] != '')
                                        <img align="left" class="img-responsive" alt="{{ $category['name'] }}" src="{{ $category['primary_image'] }}" />
                                        <?php break; ?>
                                    @endif
                                @endforeach
                                @if (Request::segment(2) == $frontHelp->pSlug($row)) active @endif
                            @endif -->
                            <img align="left" class="img-responsive" alt="{{ $frontHelp->pField($row, 'title') }}" src="{{ $frontHelp->pImg($row, 'sector-icons') }}" />
                            {{ $frontHelp->pField($row, 'title') }}</h1>

                            {!! $frontHelp->pField($row, 'body') !!}

                        </div>
                        @endforeach
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="container">
        <div class="buttons-line">
            <div class="row">
                <div class="col-lg-4 col-md-5 col-sm-6">
                    <div class="extra-buttons">
                        <a href="case-studies/corporate-tax-law">
                        <img src="theme/assets/img/corporatetaxlaw.png" />CORPORATE &amp; TAX LAW
                        </a>
                    </div>

                </div>
                <div class="col-lg-4 col-md-5 col-sm-6">
                    <div class="extra-buttons">
                        <a href="case-studies/faqs">
                            <img src="theme/assets/img/FAQS.png" />
                            FAQS
                        </a>

                    </div>
                </div>
                <div class="col-lg-4 col-md-5 col-sm-6">
                    <div class="extra-buttons">
                    <a href="/case-studies">
                        <img  src="theme/assets/img/casestudies.png" />
                        CASE STUDIES
                    </a>

                    </div>
                </div>
            </div>
        </div>
    </div>

</div>


@include('theme.home.partials.newsletter')
@endsection
