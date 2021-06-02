@extends('theme.layouts.master')

@section('content')
@inject('frontHelp', 'Library\FrontendHelperLib')

<div id="page-banner">



    <?php  if (isset($categoryBanner) && count($categoryBanner) > 0) : ?>

    <?php foreach ($categoryBanner as $key => $value) : ?>
        <img alt="About" src="/uploads/categories/<?php echo $value['catid'] . '/' . $value['truefilename']; ?>" class="img-responsive center-block" />
    <?php endforeach; ?>

    <?php else : ?>

        <img alt="" src="assets/img/banners/THE_GROUP.jpg" class="img-responsive center-block" />

    <?php endif; ?>


    <div class="container" id="banner-caption">
        <div class="row">
            <div class="col-lg-12">
                <h3 class="introtextpages animatable fadeInUp">FLEXI  FAMILY</h3>
            </div>
        </div>
    </div>
</div>

<div class="container">
    <div class="home-flexi-family">
        <div class="row">
            @if (!empty($flexifamilySection))
                    @foreach ($flexifamilySection as $key => $row)
                        @if ($key == 0)
                            <div class="col-lg-4 col-md-4 col-sm-4">
                                <h1 class="home-section-head animatable bounceInLeft">Flexi<br />Family</h1>
                                <a target="{{ $frontHelp->pTarget($row) }}" href="{{ $frontHelp->pSlug($row) }}" title="{{ $frontHelp->pField($row, 'title') }}">
                                <div class="family-tile">
                                    <img class="img-responsive coverp" alt="{{ $frontHelp->pField($row, 'title') }}" src="{{ $frontHelp->pImg($row, 'post-general-medium') }}" />
                                    <div class="family-tile-title animatable moveUp">
                                        {{ $frontHelp->pField($row, 'title') }}
                                    </div>
                                </div>
                                </a>
                            </div>
                    @endif
                @endforeach
            @endif

            <div class="col-lg-8 col-md-8 col-sm-8">
                <div class="row">
                    @if (!empty($flexifamilySection))
                        @foreach ($flexifamilySection as $key => $row)
                            @if ($key == 1)
                                <div class="col-lg-7 col-md-7 col-sm-7">
                                    <a target="{{ $frontHelp->pTarget($row) }}" href="{{ $frontHelp->pSlug($row) }}" title="{{ $frontHelp->pField($row, 'title') }}">
                                    <div class="family-tile">
                                        <img class="img-responsive cover" alt="{{ $frontHelp->pField($row, 'title') }}" src="{{ $frontHelp->pImg($row, 'post-general-medium') }}" />
                                        <div class="family-tile-title animatable fadeInUp">
                                            {{ $frontHelp->pField($row, 'title') }}
                                        </div>
                                    </div>
                                    </a>
                                </div>
                            @endif
                        @endforeach
                    @endif

                    @if (!empty($flexifamilySection))
                        @foreach ($flexifamilySection as $key => $row)
                            @if ($key == 2)

                                <div class="col-lg-5 col-md-5 col-sm-5">
                                    <a target="{{ $frontHelp->pTarget($row) }}" href="{{ $frontHelp->pSlug($row) }}" title="{{ $frontHelp->pField($row, 'title') }}">
                                    <div class="family-tile">
                                        <img class="img-responsive cover" alt="{{ $frontHelp->pField($row, 'title') }}" src="{{ $frontHelp->pImg($row, 'post-general-medium') }}" />
                                        <div class="family-tile-title animatable fadeInUp">
                                            {{ $frontHelp->pField($row, 'title') }}
                                        </div>
                                    </div>
                                    </a>
                                </div>

                            @endif
                        @endforeach
                    @endif
                </div>
                <div class="row">
                    @if (!empty($flexifamilySection))
                        @foreach ($flexifamilySection as $key => $row)
                            @if ($key == 3 || $key == 4)
                                <div class="col-lg-6 col-md-6 col-sm-6">
                                    <a target="{{ $frontHelp->pTarget($row) }}" href="{{ $frontHelp->pSlug($row) }}" title="{{ $frontHelp->pField($row, 'title') }}">
                                    <div class="family-tile">
                                        <img class="img-responsive cover" alt="{{ $frontHelp->pField($row, 'title') }}" src="{{ $frontHelp->pImg($row, 'post-general-medium') }}" />
                                        <div class="family-tile-title animatable fadeInUp">
                                            {{ $frontHelp->pField($row, 'title') }}
                                        </div>
                                    </div>
                                    </a>
                                </div>
                            @endif
                        @endforeach
                    @endif
                    <!-- <div class="col-lg-6 col-md-6 col-sm-6">
                        <a href="single_company.php">
                        <div class="family-tile">
                            <img class="img-responsive cover" src="assets/img/flexi-2_10.jpg" />
                            <div class="family-tile-title animatable fadeInUp">
                                Frixos Zembylas Audit
                            </div>
                        </div>
                        </a>
                    </div> -->
                </div>
            </div>
        </div>
    </div>
</div>

@include('theme.home.partials.newsletter')
@endsection
