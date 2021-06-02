@extends('theme.layouts.master')

@section('content')
@inject('frontHelp', 'Library\FrontendHelperLib')

<div id="page-banner">
    <?php  if (isset($categoryBanner) && count($categoryBanner) > 0) : ?>

    <?php foreach ($categoryBanner as $key => $value) : ?>
        <img alt="About" src="/uploads/categories/<?php echo $value['catid'] . '/' . $value['truefilename']; ?>" class="img-responsive center-block" />
    <?php endforeach; ?>

    <?php else : ?>

        <img alt="" src="assets/img/banners/NEWS.jpg" class="img-responsive center-block" />

    <?php endif; ?>

    <!-- <div class="container" id="banner-caption">
        <div class="row">
            <div class="col-lg-12">
                <h3 class="introtextpages-center with-q animatable fadeInUp">Today Knowledge has power.<br /> It controls the opportunity and advancement.</h3>
            </div>
        </div>
    </div> -->
</div>

<div class="page-content">
    <div class="container">
        <div class="row">
        @if (!empty($newsSection))
            @foreach ($newsSection as $key => $row)
            <div class="col-lg-4 col-md-4 col-sm-4">
                <div class="news-tile">
                    <div class="news-photo">
                        <a target="{{ $frontHelp->pTarget($row) }}" href="{{ $frontHelp->pSlug($row) }}" title="{{ $frontHelp->pField($row, 'title') }}">
                            <img class="img-responsive" alt="{{ $frontHelp->pField($row, 'title') }}" src="{{ $frontHelp->pImg($row, 'news-list') }}?w=370&h=260&fit=fill" />
                           <!--  <img class="img-responsive" alt="{{ $frontHelp->pField($row, 'title') }}" src="{{ $frontHelp->pImg($row) }}?w=370&h=260&fit=crop" /> -->
                        </a>
                    </div>
                    <div class="news-info">
                        <div class="news-post-date">{{ $frontHelp->contentDate($row['content']['published_at']) }}</div>
                        <h1>{!! $frontHelp->truncateOnSpace($frontHelp->pField($row, 'title'), 55) !!}</h1>
                        {!! $frontHelp->truncateOnSpace($frontHelp->pField($row, 'body'), 380) !!}
                        <p class="mobileR">
                            <a class="read-more-news" target="{{ $frontHelp->pTarget($row) }}" href="{{ $frontHelp->pSlug($row) }}" title="{{ $frontHelp->pField($row, 'title') }}">
                            - continue reading</a>
                        </p>

                    </div>
                </div>
            </div>
            @endforeach
        @endif
        </div>

        {!! $newsSection->render() !!}

<!--         <div class="custom_pagination">

        <nav id="cust_pagi">
            <ul>

              <li><a href="#">1</a></li>
              <li><a href="#">2</a></li>
              <li><a href="#" class="active">3</a></li>
              <li><a href="#">4</a></li>
              <li><a href="#">5</a></li>

            </ul>
          </nav>

        </div> -->
    </div>
</div>

@include('theme.home.partials.newsletter')
@endsection
