@extends('theme.layouts.master')

@section('content')
@inject('frontHelp', 'Library\FrontendHelperLib')
<div id="main-content-body">

<!-- single-post-page -->

    <div id="single_post">
        <div class="container">
            <div class="row">
                <div class="col-lg-9 col-md-8 col-sm-7">
                    <div class="blogger-head-wrap">
                        <a href="{{ $columnist->slug }}" title="{{ $columnist->user->first_name.' '.$columnist->user->last_name }}">
                            @if (strlen($columnist->image) > 3)
                            <img class="img-responsive author-photo" alt="Author Name Here" src="columnist-img/large/{{ $columnist->image }}" />
                            @else
                            <img class="img-responsive author-photo" alt="Author Name Here" src="http://lorempixel.com/150/150/people/7" />
                            @endif
                        </a>
                        <h2 class="blogger-head">
                            <a href="{{ $columnist->slug }}" title="{{ $columnist->user->first_name.' '.$columnist->user->last_name }}">
                                {{ $columnist->user->first_name.' '.$columnist->user->last_name }}
                            </a>
                        </h2>
                        {!! $columnist->bio !!}
                    </div>

                    <h2 class="single-post-head">{{ $content->header }}</h2>
                    <h1 class="single-post-head">{{ $content->title }}</h1>

                    <div class="post-content clearfix dpContentEntry" data-dp-content-id="{{ $content->id }}">
                        {!! $content->body !!}
                    </div>

                    <div class="post-social-line clearfix">
                        <!-- <div class="post-shares">
                            <span class="post-shares-number"></span>
                        </div>
                        -->
                        <div class="post-social">
                            <div class="social_icon social_icon_fb"><a target="_blank" href="http://www.facebook.com/sharer.php?u={{ Request::url() }}" onclick="javascript:window.open(this.href,
          '', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=600,width=600');return false;"><i class="fa fa-facebook"> </i> facebook
                                </a>
                            </div>
                            <div class="social_icon social_icon_tw"><a target="_blank" href="http://twitter.com/share?text={{ $content->title }}&amp;url={{ Request::url() }}&amp;via=TVOneNews_CY" onclick="javascript:window.open(this.href,
      '', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=600,width=600');return false;"><i class="fa fa-twitter"> </i> twitter
                                </a>
                            </div>
                            <div class="social_icon social_icon_gp">
                                <a target="_blank" href="https://plus.google.com/share?url={{ Request::url() }}" onclick="javascript:window.open(this.href,
      '', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=600,width=600');return false;"><i class="fa fa-google-plus"> </i> google+
                                </a>
                            </div>
                        </div>
                        <div class="post-comments-number">
                            <span><a title="Σχόλια" href="{{ Request::url() }}#disqus_thread">0</a></span>
                        </div>
                    </div>

                    <!-- <div class="related-section">
                        <div class="row">
                            <div class="col-lg-4">
                                <img style="width:100%;" class="img-responsive center-block" alt="Post Title Here" src="theme/assets/img/related-banner.jpg" />

                            </div>
                            <div class="col-lg-8">
                            @if (!empty($relevantFeed))
                                <h3>Σχετικά θέματα</h3>
                                <ul>
                                    @foreach ($relevantFeed as $key => $row)
                                    <li>
                                        <a target="{{ $frontHelp->pTarget($row) }}" title="{{ $frontHelp->pField($row, 'title') }}" href="{{ $frontHelp->pSlug($row) }}" class="dpContentEntry" data-dp-content-id="">
                                            {{ $frontHelp->pField($row, 'title') }}
                                        </a>
                                    </li>
                                    @endforeach
                                </ul>
                            @endif
                            </div>
                        </div>
                    </div>
                    -->
                    @include('theme.home.partials.home-advertisement_1')

                    @include('theme.partials.newsletter-block')

                </div>
                <!-- SIDEBAR -->
                <div class="col-lg-3 col-md-4 col-sm-5">
                    @include('theme.sidebars.blogger-sidebar')
                </div>
                <!-- SIDEBAR END -->
            </div><!-- ROW END -->
        </div>
    </div>
    <!-- single-post-page END -->
</div>
@endsection
