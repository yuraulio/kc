<div class="heading-small text-primary mb-1 mt-1">
  @if(Carbon::parse($page->published_at)->startOfDay()->lt(Carbon::parse($page->updated_at)->startOfDay()))
  Published: {{ Carbon::parse($page->published_at)->format('d M Y') }},
  Last updated: {{ Carbon::parse($page->updated_at)->format('d M Y') }}
  @else
  Published: {{ Carbon::parse($page->published_at)->format('d M Y') }}
  @endif
</div>
