
<div id="main-content-body">

<!-- single-post-page -->

    <div id="single_post">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <h2 class="single-post-head">{{ $content->header }}</h2>
                    <h1 class="single-post-head">{{ $content->title }}</h1>
                    <div class="post-info-line">
                        <div class="post-date">
                            <span>{{ $frontHelp->contentDate($content['published_at']) }}</span>
                        </div>
                        <?php $ga_category = ''; ?>
                        @if (!empty($content->categories))
                        @foreach ($content->categories as $key => $category)
                            <?php if ($key == 0) : ?>
                                <?php if ($category->id == 7) : $is_sports = true; else : $is_sports = false; endif; ?>
                            <?php endif; ?>
                            <?php if (($category->depth == 0) && (strlen($ga_category) == 0)) : ?>
                                <?php $ga_category = $category->name; ?>
                            <?php endif; ?>
                            @if ($is_sports)
                                @if ($key == 0)
                                <a class="post-section section-color-{{ $category->id }}" href="{{ $frontHelp->pSlug($category) }}" target="{{ $frontHelp->pTarget($category) }}" title="{{ $category->name }}">
                                    @if ($category->primary_image)
                                    <img src="{{ $category->primary_image }}" alt="{{ $category->name }}" />
                                    @elseif ($category->secondary_image)
                                    <img src="{{ $category->secondary_image }}" alt="{{ $category->name }}" />
                                    @elseif ($category->image)
                                    <img src="{{ $category->image }}" class="cat_image" alt="{{ $category->name }}" />
                                    @endif
                                    <span>{{ $category->name }}</span>
                                </a>
                                @elseif ($category->depth == 0)
                                <a class="post-section section-color-{{ $category->id }}" href="{{ $frontHelp->pSlug($category) }}" target="{{ $frontHelp->pTarget($category) }}" title="{{ $category->name }}">
                                    @if ($category->primary_image)
                                    <img src="{{ $category->primary_image }}" alt="{{ $category->name }}" />
                                    @elseif ($category->secondary_image)
                                    <img src="{{ $category->secondary_image }}" alt="{{ $category->name }}" />
                                    @endif
                                    <span>{{ $category->name }}</span>
                                </a>
                                @else
                                <a class="post-section-cust sports-cust-section-color" href="{{ $frontHelp->pSlug($category) }}" target="{{ $frontHelp->pTarget($category) }}" title="{{ $category->name }}">
                                    <span>{{ $category->name }}</span>
                                </a>
                                @endif
                            @else
                            <a class="post-section section-color-{{ $category->id }}" href="{{ $frontHelp->pSlug($category) }}" target="{{ $frontHelp->pTarget($category) }}" title="{{ $category->name }}">
                                @if ($category->primary_image)
                                <img src="{{ $category->primary_image }}" alt="{{ $category->name }}" />
                                @endif
                                <span>{{ $category->name }}</span>
                            </a>
                            @endif
                        @endforeach
                        @endif
                        <div class="post-hash-tags">
                            @if (!empty($content->tags))
                            @foreach ($content->tags as $tag)
                            <a href="{{ $frontHelp->pSlug($tag) }}" title="{{ $tag->name }}">#{{ $tag->name }}</a>
                            @endforeach
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-9 col-md-8 col-sm-7 col-xs-12">

                    @if (!empty($content['featured']) && isset($content['featured'][0]) &&isset($content['featured'][0]['media']) && !empty($content['featured'][0]['media']))
                    <?php $media = $content['featured'][0]['media']; ?>
                    <img style="width:100%;" class="img-responsive center-block" alt="{{ $content->title }}" src="{{ $frontHelp->pImg($content, 'main') }}" />
                    @else
                    <img style="width:100%;" class="img-responsive center-block" alt="{{ $content->title }}" src="theme/assets/img/post-featured-image.jpg" />
                    @endif

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

                    <!--h5 class="post-shares-title"><span>SHARES</span></h5-->


                    <div class="post-content clearfix dpContentEntry" data-dp-content-id="{{ $content->id }}">
                        {!! $content->body !!}
                    </div>

                    <div class="share-post hidden-xs">
                    <!-- Go to www.addthis.com/dashboard to customize your tools -->
                    <div class="row">
                        <div class="col-lg-6 col-sm-12">
                            <div class="addthis_sharing_toolbox"></div>
                        </div>
                        <div class="col-lg-6 col-sm-12">
                            <div class="comment_this text-right">
                                <a href="{{ Request::url() }}#disqus_thread"><button>Σχολιάστε</button></a>
                            </div>
                        </div>
                    </div>
                                            <!-- <h2 style="position:relative;top: 37px;font-weight: 400;font-size: 20px;width: 80px; color:#979797;">SHARE:</h2>
                        <div style="display:inline-block; padding-left: 80px;" class="addthis_sharing_toolbox"></div> -->
                    </div>

                    <div class="related-section">
                        <div class="row">
                            <div class="col-lg-4">
                                <!-- /17337359/Slot3 300x250 -->
                                <div id='div-gpt-ad-1451989749397-2' style='height:250px; width:300px;'>
                                <script type='text/javascript'>
                                googletag.cmd.push(function() { googletag.display('div-gpt-ad-1451989749397-2'); });
                                </script>
                                </div>
                            </div>
                            <div class="col-lg-8">
                            @if (!empty($relevantFeed))
                                <h3>Σχετικά θέματα</h3>
                                <ul>
                                    @foreach ($relevantFeed as $key => $row)
                                    <li>
                                        <a target="{{ $frontHelp->pTarget($row) }}" title="{{ $frontHelp->pField($row, 'title') }}" href="{{ $frontHelp->pSlug($row) }}" class="dpContentEntry" data-dp-content-id="{{ $row['content_id'] }}">
                                            {{ $frontHelp->pField($row, 'title') }}
                                        </a>
                                    </li>
                                    @endforeach
                                </ul>
                            @endif
                            </div>
                        </div>
                    </div>

                    @include('theme.partials.newsletter-block')

                    <!-- advertisement block 728x90-->
                    <div class="middle-advertisement-section">
                        <div class="row">
                            <div class="col-lg-12 md_no_padding hidden-sm hidden-xs">
                                <!-- /17337359/Slot4 728x90 -->
                                <div id='div-gpt-ad-1451989749397-3' style='height:90px; width:728px;'>
                                <script type='text/javascript'>
                                googletag.cmd.push(function() { googletag.display('div-gpt-ad-1451989749397-3'); });
                                </script>
                                </div>
                            </div>
                        </div>
                    </div>

                    @include('theme.sections.partials.dont_miss_out')

                    <!-- commenting block -->
                    <div class="commenting-section">
                        @include('theme.partials.disquss_script')
                    </div>

                    <!-- other posts list block -->
                    <div class="other-posts-section" id="appendNewContent" data-dp-take="{{ $take }}" data-dp-section-id="{{ $cat_dets['id'] }}" data-dp-total="{{ $cat_dets['total'] }}">
                    @if (!empty($contentFeed))
                        @foreach ($contentFeed as $key => $row)
                        <div class="other-post-list-item dpContentEntry" data-dp-content-id="{{ $row['content_id'] }}">
                            <div class="row">
                                <div class="col-lg-5 col-md-5 col-sm-12 col-xs-12 other-post-preview-img">
                                    <a target="{{ $frontHelp->pTarget($row) }}" title="{{ $frontHelp->pField($row, 'title') }}" href="{{ $frontHelp->pSlug($row) }}">
                                        <img class="img-responsive top-banner-img" alt="{{ $frontHelp->pField($row, 'title') }}" src="{{ $frontHelp->pImg($row, 'tvone-suggests') }}" />
                                    </a>
                                </div>
                                <div class="col-lg-7 col-md-7 col-sm-12 col-xs-12 other-post-preview">
                                    <span class="small-date">
                                        {{ $frontHelp->contentDate($row['content']['published_at']) }}
                                    </span>
                                    <h1>
                                        <a target="{{ $frontHelp->pTarget($row) }}" title="{{ $frontHelp->pField($row, 'title') }}" href="{{ $frontHelp->pSlug($row) }}">
                                            {{ $frontHelp->truncateOnSpace($frontHelp->pField($row, 'title'), 160) }}
                                        </a>
                                    </h1>
                                    <a class="category_lead_text" target="{{ $frontHelp->pTarget($row) }}" title="{{ $frontHelp->pField($row, 'title') }}" href="{{ $frontHelp->pSlug($row) }}">
                                        {{ $frontHelp->truncateOnSpace($frontHelp->pField($row, 'body'), 200) }}
                                    </a>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    @endif

                    @if ($cat_dets['total'] > 8)
                    <div class="row" id="loadMoreContent">
                        <div class="col-lg-4 col-md-4 col-sm-4"></div>
                        <div class="col-lg-4 col-md-4 col-sm-4">
                            <span class="btn btn-normal loadMoreContentBtn">
                                Δείτε περισσότερα
                            </span>
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-4"></div>
                    </div>
                    @endif

                    </div>
                </div>
                <!-- SIDEBAR -->
                <div class="col-lg-3 col-md-4 col-sm-5 col-xs-12">
                    @include('theme.sidebars.full-sidebar')
                </div>
                <!-- SIDEBAR END -->
            </div><!-- ROW END -->
        </div>
    </div>
    <!-- single-post-page END -->
</div>
<script type="text/javascript">
ga('send', 'pageview', {
  'dimension1':  "{!! $ga_category !!}"
});
</script>
