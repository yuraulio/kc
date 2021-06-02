@extends('theme.layouts.master')

@section('content')
@inject('frontHelp', 'Library\FrontendHelperLib')

<div id="page-banner">
    <?php  if (isset($categoryBanner) && count($categoryBanner) > 0) : ?>

    <?php foreach ($categoryBanner as $key => $value) : ?>
        <img alt="About" src="/uploads/categories/<?php echo $value['catid'] . '/' . $value['truefilename']; ?>" class="img-responsive center-block" />
    <?php endforeach; ?>

    <?php else : ?>

        <img alt="" src="assets/img/banners/WHERE_WE_CAN_HELP.jpg" class="img-responsive center-block" />

    <?php endif; ?>

    <!-- <div class="container" id="banner-caption">
        <div class="row">
            <div class="col-lg-12">
                <h3 class="introtextpages animatable fadeInUp">Always Improving!</h3>
            </div>
        </div>
    </div> -->
</div>

<div class="page-content">
    <div class="container">
        <div class="row">
            @if (!empty($subMenuTree))
            @foreach ($subMenuTree as $key => $row)
                @if ($row->type == 0)
                    @if ($row->depth == 1)
                    <div class="col-lg-3 col-md-3 col-sm-6">
                        @if ($row->children->isEmpty())
                            <div class="jurisdictions-tile" style="text-transform: uppercase;">
                                <h1 style="min-height: 32px;">{{ $row->name }}</h1>
                            </div>
                            <ul class="sq-list">
                            @if (!empty($jurisdictionsSection))
                                @foreach ($jurisdictionsSection as $skey => $srow)
                                    @if (!empty($srow['content']['categories']))
                                        @foreach ($srow['content']['categories'] as $ckey => $crow)
                                            @if ($crow['depth'] == 1)
                                                @if ($crow['id'] == $row->id)
                                                    <li>
                                                        <a href="/{{ $frontHelp->pField($srow['content'], 'slug') }}">
                                                            {{ $frontHelp->pField($srow, 'title') }}
                                                        </a>
                                                    </li>
                                                @endif
                                            @endif
                                        @endforeach
                                    @endif
                                @endforeach
                            @endif
                            </ul>
                        @endif
                    @endif
                    @if ($row->depth == 1)
                    </div>
                    @endif
                @endif
            @endforeach
            @endif
        </div><!-- ROW END -->
    </div>
</div>

@include('theme.home.partials.newsletter')
@endsection
