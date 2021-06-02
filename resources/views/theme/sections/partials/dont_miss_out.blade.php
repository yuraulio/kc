@if (isset($dontMissOutFeed) && !empty($dontMissOutFeed))
<!-- promoted posts block -->
<div class="promoted-posts-section">
    <h2 class="promoted-posts-section-title"><span>Μην τα χάσετε...</span></h2>
    <div class="row">
    @foreach ($dontMissOutFeed as $key => $row)
        @if ($key == 4)
        <div class="col-lg-4 col-md-6 col-sm-12 col-xs-12">
            <div class="">
                <!-- /17337359/Slot7 300x250 -->
                <div id='div-gpt-ad-1451989749397-6' style='height:250px; width:300px;'>
                <script type='text/javascript'>
                googletag.cmd.push(function() { googletag.display('div-gpt-ad-1451989749397-6'); });
                </script>
                </div>
            </div>
        </div>
        @endif
        <div class="col-lg-4 col-md-6 col-sm-12 col-xs-12 dpContentEntry" data-dp-content-id="{{ $row['id'] }}">
            <a target="{{ $frontHelp->pTarget($row) }}" title="{{ $frontHelp->pField($row, 'title') }}" href="{{ $frontHelp->pSlug($row) }}">
                <img class="img-responsive top-banner-img" alt="{{ $frontHelp->pField($row, 'title') }}" src="{{ $frontHelp->pImg($row, 'latest-posts') }}" />
            </a>
            <h1>{{ $frontHelp->pField($row, 'title') }}</h1>
        </div>
    @endforeach
    </div>
</div>
@endif
