@php
    $teaser = [];
    foreach ($column->template->inputs as $input){
        $teaser[$input->key] = $input->value ?? "";
    }
@endphp

<div class="section-icon-text p-0 mt-5 mb-5" style="background: transparent;">
    <div class="icon-text-box m-0">
        <div class="icon-wrapper">
            <img src="{{ cdn('/theme/assets/images/icons/knowcrunch-corporate-training-icon-01.svg') }}" alt=""/>
        </div>
        <h3>{{ $teaser["inspiration_title"] ?? "" }}</h3>
        {{ $teaser["inspiration_description"] ?? "" }}
    </div>
</div>