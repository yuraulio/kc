@if (isset($teamLogos) && !$teamLogos->isEmpty())
<div class="team-logos">
@foreach ($teamLogos as $key => $row)
    @if ($row->image)
    <div class="team-logo">
        <a href="{{ $cat_dets->slug }}/{{ $row->slug }}" title="{{ $row->name }}">
            <img class="img-responsive" alt="{{ $row->name }}" src="{{ $row->image }}" />
        </a>
    </div>
    @endif
@endforeach
</div>
@endif
