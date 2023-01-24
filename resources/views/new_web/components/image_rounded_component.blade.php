@php
    $image = [];
    foreach ($column->template->inputs as $input){
        $image[$input->key] = $input->value ?? "";
    }
@endphp
<img loading="lazy" src="{{ $image["rounded_image"]->url ?? "" }}" width="{{ $image["rounded_image"]->width }}" height="{{$image["rounded_image"]->height}}" class="center resp-img" alt="{{ $image["rounded_image"]->alt_text ?? "" }}" title="{{ $image["rounded_image"]->name ?? "" }}" style="border-radius: 50%;">
