@php
    $event = $dynamic_page_data["event"] ?? null;
    $image = cdn(get_image($event->mediable,'header-image'))
@endphp

<section class="section-hero" style="background-image:url({{ cdn(get_image($event->mediable,'header-image')) ?? "" }})">
    <div class="overlay"></div>
    <div class="container">
        <div class="hero-message">
            <h1>{{ $event->title ?? "" }}</h1>
            <h2>{{ $event->subtitle ?? "" }}</h2>
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