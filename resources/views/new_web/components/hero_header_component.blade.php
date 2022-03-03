@php
    $hero = [];
    foreach ($column->template->inputs as $input){
        $hero[$input->key] = $input->value ?? "";
    }

    $small = $hero['hero_small'] ?? null;
@endphp

<section class="section-hero {{ $small ? 'section-hero-small' : '' }}" style="background-image:url({{ $hero["hero_image"] ?? "" }})">
    @if (!$small)
        <div class="overlay"></div>
    @endif
    <div class="container">
        <div class="hero-message">
            <h1>{{ $hero["hero_title"] ?? "" }}</h1>
            <h2>{{ $hero["hero_subtitle"] ?? "" }}</h2>
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