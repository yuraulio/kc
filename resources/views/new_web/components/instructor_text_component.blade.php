@php
    $instructor = [];
    foreach ($column->template->inputs as $input){
        $instructor[$input->key] = $input->value ?? "";
    }

    $data = $dynamic_page_data;
    
    $title = $data["title"];
    $content = $data["content"];
    $field2 = $content['company'];
@endphp

<div class="instructor-area instructor-profile pb-4 pt-4">
    <div class="text-area">
        <h1>{{ $title }}</h1>
        <h2>{{ $content['header'] }},@if(isset($content['ext_url'])) <a target="_blank" title="{{ $field2 }}" href="{{ $content['ext_url'] }}"> {{ $field2 }}</a> @endif</h2>
        {!! $content['body'] !!}
    </div>
</div>

