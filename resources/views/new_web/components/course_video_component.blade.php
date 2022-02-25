@php
    $sections = $dynamic_page_data["sections"] ?? null;
    $section_fullvideo = $dynamic_page_data["section_fullvideo"] ?? null;
    $event = $dynamic_page_data["event"] ?? null;
    $estatus = $event->status ?? null;
@endphp

@if($estatus == 0 || $estatus == 2)
    @if(isset($section_fullvideo) && $section_fullvideo->body != '')
        <div class="video-wrapper mb-5 mt-0">
            <div class="responsive-fb-video">
                {!! $section_fullvideo->body !!}
            </div>
        </div>
    @endif
@endif