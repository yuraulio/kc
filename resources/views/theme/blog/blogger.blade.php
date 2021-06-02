@extends('theme.layouts.master')

@section('content')
@inject('frontHelp', 'Library\FrontendHelperLib')
<div id="main-content-body">
<!-- single-article-page -->
    <div id="blogger">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                </div>
            </div>
            <div class="row">
                <div class="col-lg-9 col-md-9 col-sm-7">
                    <div class="blogger-head-wrap">
                        @if (strlen($columnist->image) > 3)
                        <img class="img-responsive author-photo" alt="Author Name Here" src="columnist-img/mlarge/{{ $columnist->image }}" />
                        @endif
                        <h2 class="blogger-head" title="{{ $columnist->user->first_name.' '.$columnist->user->last_name }}">
                            {{ $columnist->user->first_name.' '.$columnist->user->last_name }}
                        </h2>
                        {!! $columnist->bio !!}
                    </div>
                    <!-- other posts list block -->
                    <div class="other-articles-section">
                    @if (!empty($list))
                        @foreach ($list as $key => $row)
                        @if ($key < 4)
                        <div class="other-articles-list-item">
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 other-articles-preview">
                                    <span class="small-date">
                                        {{ $frontHelp->contentDateBlog($row['published_at']) }}
                                    </span>
                                    <span class="small-author-name">
                                        <a title="{{ $row->author->first_name.' '.$row->author->last_name }}" href="{{ $columnist->slug }}">
                                            {{ $row->author->first_name.' '.$row->author->last_name }}
                                        </a>
                                    </span>
                                    <h1>
                                        <a href="{{ $frontHelp->pSlug($row) }}" title="{{ $frontHelp->pField($row, 'title') }}" target="{{ $frontHelp->pTarget($row) }}">
                                            {{ $frontHelp->pField($row, 'title') }}
                                        </a>
                                    </h1>
                                </div>
                            </div>
                        </div>
                        @endif
                        @endforeach
                    @endif
                    </div>
                    <!-- advertisement block 728x90-->
                    @include('theme.home.partials.home-advertisement_1')
                    <!-- other posts list block -->
                    <div class="other-articles-section">
                    @if (!empty($list))
                        @foreach ($list as $key => $row)
                        @if ($key >= 4)
                        <div class="other-articles-list-item">
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 other-articles-preview">
                                    <span class="small-date">
                                        {{ $frontHelp->contentDateBlog($row['published_at']) }}
                                    </span>
                                    <span class="small-author-name">
                                        <a title="{{ $row->author->first_name.' '.$row->author->last_name }}" href="{{ $columnist->slug }}">
                                            {{ $row->author->first_name.' '.$row->author->last_name }}
                                        </a>
                                    </span>
                                    <h1>
                                        <a href="{{ $frontHelp->pSlug($row) }}" title="{{ $frontHelp->pField($row, 'title') }}" target="{{ $frontHelp->pTarget($row) }}">
                                            {{ $frontHelp->pField($row, 'title') }}
                                        </a>
                                    </h1>
                                </div>
                            </div>
                        </div>
                        @endif
                        @endforeach
                    @endif
                    </div>
                    @include('theme.partials.newsletter-block')
                </div>
                <!-- SIDEBAR -->
                <div class="col-lg-3 col-md-3 col-sm-5">
                    @include('theme.sidebars.blogger-sidebar')
                </div>
                <!-- SIDEBAR END -->
            </div><!-- ROW END -->
        </div>
    </div>
    <!-- single-post-page END -->
</div>
@endsection
