@php
    $teaser = [];
    foreach ($column->template->inputs as $input){
        $teaser[$input->key] = $input->value ?? "";
    }
@endphp

<div class="row">
    <div class="col-md-4">
        <div class="section-icon-text p-0 mt-5 mb-5" style="background: transparent;">
            <div class="icon-text-box m-0 text-center">
                <div class="icon-wrapper d-inline-block">
                    <img src="{{ $teaser["inspirational_image1"]->url ?? "" }}" alt="{{ $teaser["inspirational_image1"]->alt_text ?? "" }}"/>
                </div>
                <h3>{{ $teaser["inspiration_title1"] ?? "" }}</h3>
                {{ $teaser["inspiration_description1"] ?? "" }}
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="section-icon-text p-0 mt-5 mb-5" style="background: transparent;">
            <div class="icon-text-box m-0 text-center">
                <div class="icon-wrapper d-inline-block">
                    <img src="{{ $teaser["inspirational_image2"]->url ?? "" }}" alt="{{ $teaser["inspirational_image2"]->alt_text ?? "" }}"/>
                </div>
                <h3>{{ $teaser["inspiration_title2"] ?? "" }}</h3>
                {{ $teaser["inspiration_description2"] ?? "" }}
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="section-icon-text p-0 mt-5 mb-5" style="background: transparent;">
            <div class="icon-text-box m-0 text-center">
                <div class="icon-wrapper d-inline-block">
                    <img src="{{ $teaser["inspirational_image3"]->url ?? "" }}" alt="{{ $teaser["inspirational_image3"]->alt_text ?? "" }}"/>
                </div>
                <h3>{{ $teaser["inspiration_title3"] ?? "" }}</h3>
                {{ $teaser["inspiration_description3"] ?? "" }}
            </div>
        </div>
    </div>

</div>