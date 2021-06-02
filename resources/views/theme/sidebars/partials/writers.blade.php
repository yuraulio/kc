@if (isset($columnists) && !empty($columnists))
<div class="sidebar-authors">
    <div class="sidebar-authors-with-icon">
        <a href="/apopseis" title="Απόψεις">
            <span class="sidebar-title-icon"><img alt="Αρθρογράφοι" src="theme/assets/img/writers@2x.png" /></span>
            <span class="sidebar-title">Απόψεις</span>
        </a>
    </div>
    @foreach ($columnists as $key => $row)
    <div class="sidebar-author-post">
        <div class="sidebar-author-image">
            <a href="/{{ $row['columnist']['slug'] }}" title="{{ $row['first_name'].' '.$row['last_name'] }}">
            @if (strlen($row['columnist']['image']) > 3)
            <img alt="{{ $row['first_name'].' '.$row['last_name'] }}" src="columnist-img/small/{{ $row['columnist']['image'] }}" />
            @else
            <img alt="{{ $row['first_name'].' '.$row['last_name'] }}" src="http://lorempixel.com/70/70/people/3" />
            @endif
            </a>
        </div>
        <div class="sidebar-author-category">
            <a class="txt-color-finance" href="/{{ $row['columnist']['slug'] }}" title="{{ $row['first_name'].' '.$row['last_name'] }}">
                {{ $row['first_name'].' '.$row['last_name'] }}
            </a>
        </div>
        <div class="sidebar-author-post-title">
            @if (isset($row['content']) && !empty($row['content']))
            <a href="{{ $frontHelp->pSlug($row['content']) }}" title="{{ $frontHelp->pField($row['content'], 'title') }}">
                <span class="sidebar-author-title">{{ $frontHelp->truncateOnSpace($frontHelp->pField($row['content'], 'title'), 150) }}</span>
            </a>
            @endif
        </div>
        <div style="clear: both;"></div>
    </div>
    @endforeach
</div>
@endif
