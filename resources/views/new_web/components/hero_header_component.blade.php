@php
    $hero = [];
    foreach ($column->template->inputs as $input){
        $hero[$input->key] = $input->value ?? "";
    }
@endphp

<section class="section-hero" style="background-image:url({{ $hero["hero_image"] ?? "" }})">
    <div class="overlay"></div>
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