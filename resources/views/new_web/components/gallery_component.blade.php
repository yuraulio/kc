@php
    $gallery = [];
    foreach ($column->template->inputs as $input){
        $gallery[$input->key] = $input->value ?? "";
    }
@endphp

@if ($gallery["gallery"])
    <div class="mb-5">
        @if ($gallery["galery_type"]->id == 1)
            {{-- grid view --}}
            <div class="logos-area content-text-area text-center">
                <h2>{{ $gallery["gallery_title"] ?? "" }}</h2>
            </div>
            <div class="row">
                @foreach ($gallery["gallery"] as $image)
                    <div class="col-md-3 col-6 self-align-center mb-5">
                        <a href="javascript:void(0);">
                            <img src="{{ $image->full_path }}" class="center grayscale image-grid-hover" alt="">
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
                                <img class="center grayscale" style="max-width: 100%;" src="{{ $image->full_path }}" title="{{ $image->name }}" alt="{{ $image->name }}">
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
            <div class="section--partners p-0">
                <h2 class="section-title">{{ $gallery["gallery_title"] ?? "" }}</h2>
            </div>
            <div class="row">
                @foreach ($gallery["gallery"] as $image)
                    <div class="col-md-2 col-6 self-align-center mb-5">
                        <img src="{{ $image->full_path }}" class="center grayscale image-grid-hover" alt="">
                    </div>
                @endforeach
            </div>

            <div class="view-more">
                <a href="{{env('NEW_PAGES_LINK') . '/' .  $gallery["gallery_link"] }}">{{ $gallery["gallery_link_text"] }}</a>
            </div>
            
        @endif
    </div>
@endif