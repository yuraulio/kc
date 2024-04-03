@php
    $image = [];
    foreach ($column->template->inputs as $input){
        $image[$input->key] = $input->value ?? "";
    }
@endphp
@if(isset($image["full_size_image"]->full_path))
<img src="{{ get_image((array)$image["full_size_image"], 'header-image') ?? "" }}" class="center" alt="{{ $image["full_size_image"]->alt_text ?? "" }}">
@endif
