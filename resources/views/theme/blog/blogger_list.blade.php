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
                    @if (!is_null($featuredColumn))
                    <div class="featured_blogger col-lg-12 col-md-12 col-sm-12">
                        <div class="featured-blogger-head-wrap">
                            <a class="featured_blogger_link" href="/{{ $featuredColumn->author->columnist->slug }}" title="{{ $featuredColumn->author->first_name.' '.$featuredColumn->author->last_name }}">
                                @if (strlen($featuredColumn->author->columnist->image) > 3)
                                <img class="img-responsive author-photo" alt="Author Name Here" src="columnist-img/large/{{ $featuredColumn->author->columnist->image }}" />
                                @else
                                <img class="img-responsive author-photo" alt="Author Name Here" src="http://lorempixel.com/300/300/people/7" />
                                @endif
                            </a>
                            <h2 class="blogger-head">
                                <a href="/{{ $featuredColumn->author->columnist->slug }}" title="{{ $featuredColumn->author->first_name.' '.$featuredColumn->author->last_name }}">
                                    {{ $featuredColumn->author->first_name.' '.$featuredColumn->author->last_name }}
                                </a>
                            </h2>
                            <h2 class="">
                                <a target="{{ $frontHelp->pTarget($featuredColumn) }}" href="{{ $frontHelp->pSlug($featuredColumn) }}" title="{{ $frontHelp->pField($featuredColumn, 'title') }}">
                                    {{ $frontHelp->pField($featuredColumn, 'title') }}
                                </a>
                            </h2>
                            <div class="blog_summary">
                                {!! $frontHelp->truncateOnSpace($frontHelp->pField($featuredColumn, 'body'), 400) !!}
                            </div>
                        </div>

                    </div>
                    @endif
                    <!-- other posts list block -->
                    <div class="other-articles-section">
                    @if (!empty($list))
                        @foreach ($list as $key => $row)
                        <div class="blogger_in_list col-lg-6 col-md-6 col-sm-12">
                            <div class="blogger-head-wrap">
                                <a href="/{{ $row['columnist']['slug'] }}" title="{{ $row['first_name'].' '.$row['last_name'] }}">
                                    @if (strlen($row['columnist']['image']) > 3)
                                    <img class="img-responsive author-photo" alt="Author Name Here" src="columnist-img/large/{{ $row['columnist']['image'] }}" />
                                    @else
                                    <img class="img-responsive author-photo" alt="Author Name Here" src="http://lorempixel.com/150/150/people/7" />
                                    @endif
                                </a>
                                <h2 class="blogger-head">
                                    <a href="/{{ $row['columnist']['slug'] }}" title="{{ $row['first_name'].' '.$row['last_name'] }}">
                                        {{ $row['first_name'].' '.$row['last_name'] }}
                                    </a>
                                </h2>
                                <!--p>{{ $frontHelp->pField($row, 'header') }}</p-->
                                <h2>
                                    <a target="{{ $frontHelp->pTarget($row) }}" href="{{ $frontHelp->pSlug($row) }}" title="{{ $frontHelp->pField($row, 'title') }}">
                                        {{ $frontHelp->pField($row, 'title') }}
                                    </a>
                                </h2>
                                <span class="small-date">
                                    {{ $frontHelp->contentDateBlog($row['content']['published_at']) }}
                                </span>
                            </div>
                        </div>
                        @endforeach
                    @endif
                    </div>
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
