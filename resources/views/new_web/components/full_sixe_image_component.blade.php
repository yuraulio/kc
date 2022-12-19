@php
    $image = [];
    foreach ($column->template->inputs as $input){
        $image[$input->key] = $input->value ?? "";
    }
@endphp
{{--<img src="{{ $image["full_size_image"]->url ?? "" }}" class="center" alt="{{ $image["full_size_image"]->alt_text ?? "" }}">--}}
<img src="{{ get_image($image["full_size_image"]->full_path) ?? "" }}" class="center" alt="{{ $image["full_size_image"]->alt_text ?? "" }}">
