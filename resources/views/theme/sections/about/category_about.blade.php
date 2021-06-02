@extends('theme.layouts.master')

@section('content')
@inject('frontHelp', 'Library\FrontendHelperLib')


<div id="page-banner">
    <?php if (isset($categoryBanner) && count($categoryBanner) > 0) :  ?>

    <?php foreach ($categoryBanner as $key => $value) : ?>
        <img alt="About" src="/uploads/categories/<?php echo $value['catid'] . '/' . $value['truefilename']; ?>" class="img-responsive center-block" />
    <?php endforeach; ?>

    <?php else : ?>

        <img alt="About" src="assets/img/banners/ABOUT.jpg" class="img-responsive center-block" />

    <?php endif; ?>

    <!-- <div class="container" id="banner-caption">
        <div class="row">
            <div class="col-lg-12">
                <h3 class="introtextpages animatable fadeInUp">Hello World, We are Flexi</h3>
            </div>
        </div>
    </div> -->
</div>

<div class="page-content">

    <div class="container">

        <div class="row">
            <div class="col-lg-4 col-md-5 col-sm-6">
                <div class="tabs-sidebar-menu">
                    <h1 class="side-head-neg">About </h1>
                    <ul class="nav nav-pills nav-stacked" role="tablist">
                        @if (!empty($aboutSection))
                            @foreach ($aboutSection as $key => $row)
                            <li role="presentation" class="@if (Request::segment(2) != '') @if ($frontHelp->pSlug($row) == '/' .  Request::segment(2) ) active @endif @else
                     @if ($key == 0) active @endif @endif"><a href="#tab{{ $key }}" aria-controls="tab{{ $key }}" role="tab" data-toggle="tab">
                            {{ $frontHelp->pField($row, 'title') }} </a></li>
                            @endforeach
                        @endif
                        <li role="presentation" class="@if (Request::segment(2) != '') @if ($frontHelp->pSlug($row) == '/' .  Request::segment(2) ) active @endif @endif"><a href="#tab999" aria-controls="tab999" role="tab" data-toggle="tab">
                            Leaders </a></li>
                     </ul>

                </div>
            </div>
            <div class="col-lg-8 col-md-7 col-sm-6">

                <div class="tab-content">
                    @if (!empty($aboutSection))
                        @foreach ($aboutSection as $key => $row)
                            <div role="tabpanel" class="tab-pane fade @if (Request::segment(2) != '') @if ($frontHelp->pSlug($row) == '/' .  Request::segment(2) ) in active @endif @else
                     @if ($key == 0) in active @endif @endif" id="tab{{ $key }}">
                                <h1 class="bebig">{{ $frontHelp->pField($row, 'title') }}</h1>
                                {!! $frontHelp->pField($row, 'body') !!}
                            </div>
                        @endforeach
                    @endif

                    <div role="tabpanel" class="tab-pane fade @if (Request::segment(2) != '') @if ($frontHelp->pSlug($row) == '/' .  Request::segment(2) ) in active @endif @endif"  id="tab999">

                        <div class="row">
                        @if (!empty($teamSection))
                            @foreach ($teamSection as $key => $row)
                            <div class="col-lg-4 col-md-4 col-sm-12 project">
                                <div class="member-tiles">
                                    <div class="member-photo">
                                        <img class="img-responsive" alt="{{ $frontHelp->pField($row, 'title') }}" src="{{ $frontHelp->pImg($row, 'team-featured') }}?w=230&h=283&fit=fill" />
                                        <a class="btn btn-default-no goupabit animatable fadeInUp" href="/contact">CONTACT ME</a>
                                    </div>
                                    <div class="members-info animatable fadeIn">
                                        <h2>{{ $frontHelp->pField($row, 'title') }}</h2>
                                        <strong>{{ $frontHelp->pField($row, 'subtitle') }}</strong>
                                        {!! $frontHelp->pField($row, 'body') !!}
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        @endif
                        </div>

                    </div>


                </div>
            </div>
        </div>
    </div>
</div>
@include('theme.home.partials.newsletter')
@endsection
