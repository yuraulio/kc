@php
    $gallery = [];
    if ($column->template->dynamic) {
        $brands = $dynamic_page_data["brands"];
        $logos = $dynamic_page_data["logos"];
    } 
    foreach ($column->template->inputs as $input){
        $gallery[$input->key] = $input->value ?? "";
    }

@endphp

@if ($column->template->dynamic)
    @if($page->slug == "in-the-media") 
        <div class="logos-area content-text-area text-center">
            <h2>{{ $gallery["gallery_title"] ?? "" }}</h2>
        </div>
        <div class="row">
            @foreach ($logos as $image)
                <div class="col-md-3 col-6 self-align-center mb-5">
                    <a href="{{ $image['ext_url'] ?? "" }}" target="_blank">
                        <img src="{{ cdn(get_image($image['medias']))}}" class="center grayscale image-grid-hover" alt="{{ $image['name'] }}">
                    </a>
                </div>
            @endforeach
        </div>
    @elseif ($page->slug == "brands-trained")
        <div class="logos-area content-text-area text-center">
            <h2>{{ $gallery["gallery_title"] ?? "" }}</h2>
        </div>
        <div class="row">
            @foreach ($brands as $image)
                <div class="col-md-3 col-6 self-align-center mb-5">
                    <a href="{{ $image['ext_url'] ?? "" }}" target="_blank">
                        <img src="{{ cdn(get_image($image['medias']))}}" class="center grayscale image-grid-hover" alt="{{ $image['name'] }}">
                    </a>
                </div>
            @endforeach
        </div>
    @endif
@elseif ($gallery["gallery"])
    <div class="mb-5">
        @if ($gallery["galery_type"]->id == 1)
            {{-- grid view --}}
            <div class="logos-area content-text-area text-center">
                <h2>{{ $gallery["gallery_title"] ?? "" }}</h2>
            </div>
            <div class="row">
                @foreach ($gallery["gallery"] as $image)
                    <div class="col-md-3 col-6 self-align-center mb-5">
                        <a href="{{ $image->link ?? "" }}" target="_blank">
                            <img src="{{ $image->url ?? "" }}" class="center grayscale image-grid-hover" alt="{{ $image->alt_text ?? "" }}">
                        </a>
                    </div>
                @endforeach
            </div>
        @elseif ($gallery["galery_type"]->id == 2)
            {{-- carousel view --}}
            <div class="logos-area content-text-area text-center">
                <h2>{{ $gallery["gallery_title"] ?? "" }}</h2>
            </div>
            <div class="section-trusted-us p-0">
                <div class="logos-carousel-wrapper">
                    <div class="logos-carousel owl-carousel">
                        @foreach ($gallery["gallery"] as $image)
                            <div class="slide">
                                <img class="center grayscale" style="max-width: 100%;" src="{{ $image->url ?? "" }}" title="{{ $image->name }}" alt="{{ $image->alt_text ?? "" }}">
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @section('blog-custom-js')
                <script>
                    $(document).ready(function(){
                        $(".owl-carousel").owlCarousel({
                            nav: true,
                            center: true,
                        });
                    });
                </script>
            @endsection
        @elseif ($gallery["galery_type"]->id == 3)
            {{-- row view --}}
            @php
                $gallery["gallery"] = array_slice($gallery["gallery"], 0, 6);
            @endphp
            <div class="section--partners p-0 mt-5">
                <h2 class="section-title">{{ $gallery["gallery_title"] ?? "" }}</h2>
            </div>
            <div class="row">
                @foreach ($gallery["gallery"] as $image)
                    <div class="col-md-2 col-6 self-align-center mb-5">
                        <img src="{{ $image->url ?? "" }}" class="center grayscale image-grid-hover" alt="{{ $image->alt_text ?? "" }}">
                    </div>
                @endforeach
            </div>

            <div class="view-more">
                <a href="{{env('NEW_PAGES_LINK') . '/' .  $gallery["gallery_link"] }}">{{ $gallery["gallery_link_text"] }}</a>
            </div>
            
        @endif
    </div>
@endif