@php
    $gallery = [];
    foreach ($column->template->inputs as $input){
        $gallery[$input->key] = $input->value ?? "";
    }

    $homeBrands = $dynamic_page_data["homeBrands"] ?? [];
    $homeLogos = $dynamic_page_data["homeLogos"] ?? [];

    if ($gallery["homepage_gallery_type"]->id == 1) {

        $gallery = $homeBrands;
        $link = "/brands-trained";
        $title = "Knowcrunch is trusted by hundreds of companies";
    } else if ($gallery["homepage_gallery_type"]->id == 2) {
        $gallery = $homeLogos;

        $link = "/in-the-media";
        $title = "Knowcrunch mentioned in the media";
    }
@endphp

<div class="mb-5 mt-5">
    <div class="section--partners p-0 mt-5">
        <h2 class="section-title">{{ $title }}</h2>
    </div>
    <div class="row">
        @foreach ($gallery as $image)
        <?php

            if(!isset($image['url'])){
                continue;
            }

            $path = $image['url'];
            $path = str_replace('https://knowcrunch.com', '', $path);

        ?>
            <div class="col-md-2 col-6 self-align-center mb-5">
                {{--<img loading="lazy" src="{{ get_image($image['medias'])}}" width="{{ $image['medias']['width'] != 0 ? $image['medias']['width'] : 10 }}" height="{{ $image['medias']['height'] != 0 ? $image['medias']['height'] : 10}}" class="center grayscale image-grid-hover resp-img" alt="{{ $image['name'] ?? "" }}"> --}}
                <img loading="lazy" src="{{ get_image($path) }}" width="{{ 10 }}" height="{{ 10 }}" class="center grayscale image-grid-hover resp-img" alt="{{ $image['name'] ?? "" }}">
            </div>
        @endforeach
    </div>

    <div class="view-more">
        <a href="{{ $link }}">See more</a>
    </div>
</div>
