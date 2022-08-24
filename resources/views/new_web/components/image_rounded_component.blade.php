@php
    $image = [];
    foreach ($column->template->inputs as $input){
        $image[$input->key] = $input->value ?? "";
    }
@endphp
<img src="{{ $image["rounded_image"]->url ?? "" }}" class="center" alt="{{ $image["rounded_image"]->alt_text ?? "" }}" style="border-radius: 50%;">