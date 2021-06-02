<div class="sidebar-latest-news">
    <h3>ΤΕΛΕΥΤΑΙΑ ΝΕΑ</h3>
    <ul class="latest-news-sidebar">
    @if (!empty($timeline))
        @foreach ($timeline as $key => $row)
        <li class="clearfix">
            <div class="latest-news-time">
                {{ \Carbon::parse($row['published_at'])->format('H:i') }}
            </div>
            @if (isset($row['categories']) && !empty($row['categories']))
                @foreach ($row['categories'] as $category)
                <h5 class="latest-news-category txt-color-default-head">{{ $category['name'] }}</h5>
                <?php break; ?>
                @endforeach
            @endif
            <p class="latest-news-title">
                <a href="{{ $frontHelp->pSlug($row) }}" title="{{ $frontHelp->pField($row, 'title') }}">
                    {{ $frontHelp->truncateOnSpace($frontHelp->pField($row, 'title'), 150) }}
                </a>
            </p>
            @if (isset($timeline[$key+1]))
            <div class="latest-news-spacer"></div>
            @endif
        </li>
        @endforeach
    @endif
    </ul>
</div>
