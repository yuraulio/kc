@php
    $hero = [];
    foreach ($column->template->inputs as $input){
        $hero[$input->key] = $input->value ?? "";
    }

    if ($column->template->dynamic) {
        $event = $dynamic_page_data["event"] ?? null;

        $image = cdn(get_image($event->mediable,'header-image')) ?? "";
        $title = $event->title ?? "";
        $subtitle = $event->subtitle ?? "";
        $small = false;
    } else {
        $image = $hero["hero_image"]->url ?? "";
        $title = $hero["hero_title"] ?? "";
        $subtitle = $hero["hero_subtitle"] ?? "";
        $small = $hero['hero_small'] ?? null;
    }
@endphp

<section class="section-hero {{ $small ? 'section-hero-small' : '' }}" style="background-image:url({{ $image }})">
    @if (!$small)
        <div class="overlay"></div>
    @endif
    <div class="container">
        <div class="hero-message">
            <h1>{{ $title }}</h1>
            <h2>{{ $subtitle }}</h2>
        </div>
    </div>
</section>

@push('components-scripts')
    <script>
        document.getElementById('header').classList.add('header-transparent');
        var main = document.getElementById('main-area');
        main.id = "main";
    </script>
@endpush