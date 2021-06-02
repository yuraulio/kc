@extends('theme.layouts.master')

@section('content')
@inject('frontHelp', 'Library\FrontendHelperLib')

<div id="page-banner">
    <img alt="" src="assets/img/banners/executive_team_banner.jpg" class="img-responsive center-block" />
    <div class="container" id="banner-caption">
        <div class="row">
            <div class="col-lg-12">
                <h3 class="introtextpages animatable fadeInUp">Coming together is a beginning, keeping together is progress,
                    working together is success</h3>
            </div>
        </div>
    </div>
</div>

<div class="page-content">
    <div class="container">
        <div class="row">
        @if (!empty($teamSection))
            @foreach ($teamSection as $key => $row)
            <div class="col-lg-4 col-md-4 col-sm-4 project">
                <div class="member-tile">
                    <div class="member-photo">
                        <img class="img-responsive" alt="{{ $frontHelp->pField($row, 'title') }}" src="{{ $frontHelp->pImg($row, 'team-featured') }}?w=230&h=283&fit=fill" />

                        <a class="btn btn-default-no goupabit animatable fadeInUp" href="/contact">CONTACT ME</a>
                    </div>
                    <div class="member-info animatable fadeIn">
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

@include('theme.home.partials.newsletter')
@endsection
