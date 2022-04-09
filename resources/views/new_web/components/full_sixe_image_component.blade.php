@php
    $image = [];
    foreach ($column->template->inputs as $input){
        $image[$input->key] = $input->value ?? "";
    }
@endphp
<img src="{{ $image["full_size_image"]->url ?? "" }}" class="center" alt="{{ $image["full_size_image"]->alt_text ?? "" }}">