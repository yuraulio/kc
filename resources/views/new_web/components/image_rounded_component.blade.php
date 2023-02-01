@php
    $image = [];
    foreach ($column->template->inputs as $input){
        $image[$input->key] = $input->value ?? "";
    }
@endphp
<img loading="lazy" src='{{ $image["rounded_image"]->url ?? "" }}' width="{{ get_image_version_details('instructors-testimonials')['w'] }}" height="{{get_image_version_details('instructors-testimonials')['h']}}" class="center resp-img" alt="{{ $image["rounded_image"]->alt_text ?? "" }}" title="{{ $image["rounded_image"]->name ?? "" }}" style="border-radius: 50%;">
