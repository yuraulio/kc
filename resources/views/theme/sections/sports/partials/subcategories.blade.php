@if (!empty($sportsMenu))
<ul class="sp-menu">
@foreach ($sportsMenu as $key => $row)
    @if ($row->type == 0)
        @if ($row->depth == 1)
        <li class="dropdown">
            @if ($row->children->isEmpty())
            <a href="/{{ $cat_dets->slug }}/{{ $row->slug }}" title="{{ $row->name }}">
                {{ $row->name }}
            </a>
            @else
            <a href="/{{ $cat_dets->slug }}/{{ $row->slug }}" title="{{ $row->name }}" class="dropdown-toggle" type="menu" data-toggle="dropdown">
                {{ $row->name }}
                <span class="caret"></span>
            </a>
            @endif
        @endif
        @if (!$row->children->isEmpty())
            <ul class="dropdown-menu bg-color-sports">
                @foreach ($row->children as $ckey => $crow)
                    @if ($row->type == 0)
                    <li><a href="/{{ $cat_dets->slug }}/{{ $crow->slug }}" title="{{ $crow->name }}">{{ $crow->name }}</a></li>
                    @endif
                @endforeach
            </ul>
        @endif
        @if ($row->depth == 1)
        </li>
        @endif
    @endif
@endforeach
</ul>
@endif
