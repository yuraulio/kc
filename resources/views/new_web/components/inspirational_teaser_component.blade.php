@php
    $teaser = [];
    foreach ($column->template->inputs as $input){
        $teaser[$input->key] = $input->value ?? "";
    }
@endphp

<div class="section-icon-text p-0 mt-5 mb-5" style="background: transparent;">
    <div class="icon-text-box m-0 text-center">
        <div class="icon-wrapper d-inline-block">
            <img src="{{ $teaser["inspirational_image"]->url ?? "" }}" alt="{{ $teaser["inspirational_image"]->alt_text ?? "" }}"/>
        </div>
        <h3>{{ $teaser["inspiration_title"] ?? "" }}</h3>
        {{ $teaser["inspiration_description"] ?? "" }}
    </div>
</div>