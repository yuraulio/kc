@php
    $youtube = [];
    foreach ($column->template->inputs as $input){
        $youtube[$input->key] = $input->value;
    }
@endphp

<div class="text-center">
    <iframe 
        width="{{ $youtube["youtube_width"] ? $youtube["youtube_width"] : '100%' }}" 
        height="{{ $youtube["youtube_height"] ? $youtube["youtube_height"] : '400' }}" 
        src="https://www.youtube.com/embed/{{ $youtube["youtube_embed"] }}" 
        title="YouTube video player" 
        frameborder="0" 
        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" 
        allowfullscreen
    ></iframe>
</div>