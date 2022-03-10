@php
    if (isset($dynamic_page_data) && $column->template->dynamic) {
        $sections = $dynamic_page_data["sections"] ?? null;
        $section_fullvideo = $dynamic_page_data["section_fullvideo"] ?? null;
        $event = $dynamic_page_data["event"] ?? null;
        $estatus = $event->status ?? null;
    } else {
        $youtube = [];
        foreach ($column->template->inputs as $input){
            $youtube[$input->key] = $input->value ?? "";
        }
    }
@endphp

@if (isset($dynamic_page_data) && $column->template->dynamic)
    @if($estatus == 0 || $estatus == 2)
        @if(isset($section_fullvideo) && $section_fullvideo->body != '')
            <div class="video-wrapper mb-5 mt-5">
                <div class="responsive-fb-video">
                    {!! $section_fullvideo->body !!}
                </div>
            </div>
        @endif
    @endif
@else
    <div class="text-center mb-5 mt-5">
        <iframe 
            width="{{ $youtube["youtube_width"] ? $youtube["youtube_width"] : '100%' }}" 
            height="{{ $youtube["youtube_height"] ? $youtube["youtube_height"] : '400' }}" 
            src="https://www.youtube.com/embed/{{ $youtube["youtube_embed"] ?? "" }}" 
            title="YouTube video player" 
            frameborder="0" 
            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" 
            allowfullscreen
        ></iframe>
    </div>
@endif
