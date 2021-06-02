<!-- LATEST POSTS SECTION -->
<div class="row">
    <!-- SIDEBAR LEFT -->
    <div class="col-lg-3 col-md-4 col-sm-6">
        @include('theme.sidebars.partials.timeline')
        <div class="sidebar-middle-adv">
            <!-- /17337359/Slot3 300x250 -->
            <div id='div-gpt-ad-1451989749397-2' class="visiblity_sm_hidden" style='height:250px; width:300px;'>
            <script type='text/javascript'>
            googletag.cmd.push(function() { googletag.display('div-gpt-ad-1451989749397-2'); });
            </script>
            </div>
        </div>
    </div>

    <!-- MAIN SECTION -->
    <div class="col-lg-6 col-md-4 col-sm-6">
    <!-- MAIN BIG SQUARE POST -->
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12">
                <div class="home-big-photo-tile">
                    <div class="color-helper"></div>
                    @if (!empty($homeLatestPostsCenter))
                        @foreach ($homeLatestPostsCenter as $key => $row)
                        <a href="{{ $frontHelp->pSlug($row) }}" title="{{ $frontHelp->pField($row, 'title') }}" target="{{ $frontHelp->pTarget($row) }}">
                            <img style="width:100%;" class="img-responsive center-block" alt="{{ $frontHelp->pField($row, 'title') }}" title="{{ $frontHelp->pField($row, 'title') }}" src="{{ $frontHelp->pImg($row, 'latest-posts-center') }}" />
                        </a>
                        <div class="home-big-photo-captions">
                            <h2 class="home-big-photo-post-sub-title">
                                <a class="" href="{{ $frontHelp->pSlug($row) }}" title="{{ $frontHelp->pField($row, 'title') }}" target="{{ $frontHelp->pTarget($row) }}">
                                    <div class="category-tag section-color-{{ $frontHelp->fistCatId($row) }} clearfix">
                                        {{ $frontHelp->pField($row, 'header') }}
                                    </div>
                                </a>
                            </h2>
                            <h1 class="home-big-photo-post-title">
                                <a class="home-in-photo-post-title" href="{{ $frontHelp->pSlug($row) }}" title="{{ $frontHelp->pField($row, 'title') }}" target="{{ $frontHelp->pTarget($row) }}">
                                    {{ $frontHelp->pField($row, 'title') }}
                                </a>
                            </h1>
                        </div>
                        @if (isset($row['content']) && isset($row['content']['categories']) && !empty($row['content']['categories']))
                            @foreach ($row['content']['categories'] as $category)
                            <div class="home-post-badge section-color-{{ $category['id'] }}" title="{{ $category['name'] }}">
                                <img alt="{{ $category['name'] }}" title="{{ $category['name'] }}" src="{{ $category['primary_image'] }}" />
                            </div>
                            <?php break; ?>
                            @endforeach
                        @endif
                        <?php break; ?>
                        @endforeach
                    @endif
                </div>
            </div>
        </div>

        <!-- MAIN 4 POSTS UNDER SQUARE POST -->
        <div class="small-posts-section">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 nopadding">
                @if (!empty($homeLatestPosts))
                    @foreach ($homeLatestPosts as $key => $row)
                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 <?php if ($key >= 4) { echo 'hidden-lg hidden-sm hidden-xs'; } ?>">
                        <div class="home-small-photo-tile home_latest_news_minh">
                            <div class="home-small-photo">
                                <a target="{{ $frontHelp->pTarget($row) }}" href="{{ $frontHelp->pSlug($row) }}" title="{{ $frontHelp->pField($row, 'title') }}">
                                    <img style="width:100%;" class="img-responsive center-block" alt="{{ $frontHelp->pField($row, 'title') }}" title="{{ $frontHelp->pField($row, 'title') }}" src="{{ $frontHelp->pImg($row, 'latest-posts-center') }}" />
                                </a>
                                @if (isset($row['content']) && isset($row['content']['categories']) && !empty($row['content']['categories']))
                                    @foreach ($row['content']['categories'] as $category)
                                    <div class="home-post-badge section-color-{{ $category['id'] }}" title="{{ $category['name'] }}">
                                        <img alt="{{ $category['name'] }}" title="{{ $category['name'] }}" src="{{ $category['primary_image'] }}" />
                                    </div>
                                    <?php break; ?>
                                    @endforeach
                                @endif
                            </div>
                            <h2 class="home-small-post-sub-title txt-color-default-head">
                                <a target="{{ $frontHelp->pTarget($row) }}" class="txt-color-default-head" href="{{ $frontHelp->pSlug($row) }}" title="{{ $frontHelp->pField($row, 'title') }}">
                                    {{ $frontHelp->pField($row, 'header') }}
                                </a>
                            </h2>
                            <h1 class="home-small-post-title">
                                <a target="{{ $frontHelp->pTarget($row) }}" href="{{ $frontHelp->pSlug($row) }}" title="{{ $frontHelp->pField($row, 'title') }}">
                                    {{ $frontHelp->pField($row, 'title') }}
                                </a>
                            </h1>
                        </div>
                    </div>
                    @endforeach
                @endif
                </div>
            </div>
        </div>
    </div>

    <!-- HOME FIRST MAIN SIDEBAR RIGHT -->
    <div class="col-lg-3 col-md-4 col-sm-6 home_special_portrait_cont">
        <div class="home-portrait-post">
            <div class="home-portrait-photo">
                <div class="color-helper"></div>
                @if (!empty($homeLatestPostsSide))
                    @foreach ($homeLatestPostsSide as $key => $row)
                    <a target="{{ $frontHelp->pTarget($row) }}" href="{{ $frontHelp->pSlug($row) }}" title="{{ $frontHelp->pField($row, 'title') }}">
                        <img style="width:100%;" class="img-responsive center-block" alt="{{ $frontHelp->pField($row, 'title') }}" title="{{ $frontHelp->pField($row, 'title') }}" src="{{ $frontHelp->pImg($row, 'latest-posts-side') }}" />
                    </a>
                    @if (isset($row['content']) && isset($row['content']['categories']) && !empty($row['content']['categories']))
                        @foreach ($row['content']['categories'] as $category)
                        <div class="home-post-badge section-color-{{ $category['id'] }}" title="{{ $category['name'] }}">
                            <img alt="{{ $category['name'] }}" title="{{ $category['name'] }}" src="{{ $category['primary_image'] }}" />
                        </div>
                        <?php break; ?>
                        @endforeach
                    @endif
                    <div class="home-portrait-photo-captions">
                        <h2 class="home-big-photo-post-sub-title">
                            <a target="{{ $frontHelp->pTarget($row) }}" class="txt-color-default-head" href="{{ $frontHelp->pSlug($row) }}" title="{{ $frontHelp->pField($row, 'title') }}">
                                <div class="category-tag section-color-{{ $frontHelp->fistCatId($row) }} clearfix">
                                    {{ $frontHelp->pField($row, 'header') }}
                                </div>
                            </a>
                        </h2>
                        <h1 class="home-big-photo-post-title">
                            <a target="{{ $frontHelp->pTarget($row) }}" class="home-in-photo-post-title" href="{{ $frontHelp->pSlug($row) }}" title="{{ $frontHelp->pField($row, 'title') }}">
                                {{ $frontHelp->pField($row, 'title') }}
                            </a>
                        </h1>
                    </div>
                    <?php break; ?>
                    @endforeach
                @endif
            </div>
        </div>
        <div class="sidebar-top-adv">
            <!-- /17337359/Slot2 300x600 -->
            <div id='div-gpt-ad-1451989749397-1' style='height:600px; width:300px;'>
            <script type='text/javascript'>
            googletag.cmd.push(function() { googletag.display('div-gpt-ad-1451989749397-1'); });
            </script>
            </div>
        </div>
    </div>
    <!-- SIDEBAR END -->
</div><!-- ROW END -->

<!-- LATEST POSTS SECTION END -->
