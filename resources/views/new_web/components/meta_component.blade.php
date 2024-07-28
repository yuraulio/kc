
@php
    $meta = [];
    foreach ($column->template->inputs as $input){
        $meta[$input->key] = $input->value ?? "";
    }

    $event = $dynamic_page_data["event"] ?? null;
    $instructor = $dynamic_page_data["instructor"] ?? null;
    /** @var \App\Model\User $publicProfileUser */
    $publicProfileUser = $dynamic_page_data["user"] ?? null;
@endphp

@if($event)
    @section('header')
        {!! $event->metable->getMetas() !!}

        @if(isset($meta['meta_schema']) && $meta['meta_schema'])
            <script type="application/ld+json">
                {!! $meta['meta_schema'] ?? "" !!}
            </script>
        @endif
    @endsection

    @include('new_web.layouts.countdown', ['event' => $event])

@elseif ($instructor)
    @section('header')
        {{-- <title>{{ $instructor->title . " " . $instructor->subtitle }}</title> --}}
        {!! $instructor->metable->getMetas() !!}

            @if(isset($meta['meta_schema']) && $meta['meta_schema'])

            <script type="application/ld+json">
                {!! $meta['meta_schema'] ?? "" !!}
            </script>

            @endif
    @endsection
@elseif ($publicProfileUser)
  {{-- Meta to show for the user's public profile page /profile/{user}--}}
  <title>{{ $publicProfileUser->name }}</title>
  <meta name="author" content="Knowcrunch">
  <meta name="description" content="' . $this->meta_description . '">
  <meta property="fb:app_id" content="961275423898153">
  <meta property="og:title" content="{{ $publicProfileUser->name }}">
  <meta property="og:type" content="website">
  <meta property="og:url" content="{{ route('public-profile', $publicProfileUser) }}">
  <meta property="og:image" content="{{ $publicProfileUser?->profile_image?->url }}">
  <meta property="og:site_name" content="' . $this->meta_title . '">
  <meta property="og:description" content="{{ $publicProfileUser->biography }}">
  <meta name="twitter:card" content="summary_large_image">
  <meta name="twitter:title" content="{{ $publicProfileUser->name }}">
  <meta name="twitter:description" content="{{ $publicProfileUser->biography }}">
  <meta name="twitter:image" content="{{ $publicProfileUser?->profile_image?->url }}">
@else
    @section('header')

        <title>{{ $meta['meta_title'] ?? "" }}</title>

        <meta name="description" content="{{ $meta['meta_description'] ?? "" }}">
        <meta name="image" content="{{ $meta['meta_image']->url ?? "" }}" alt="{{ $meta['meta_image']->alt_text ?? "" }}">
        <meta name="author" content="{{ $meta['meta_author'] ?? "" }}">

        <!--  Essential META Tags -->
        <meta property="og:title" content="{{ $meta['meta_title'] ?? "" }}">
        <meta property="og:type" content="{{ $meta['meta_type'] ?? "" }}" />
        <meta property="og:image" content="{{ $meta['meta_image']->url ?? "" }}" alt="{{ $meta['meta_image']->alt_text ?? "" }}">
        <meta property="og:url" content="{{ $_SERVER['REQUEST_URI'] }}">
        <meta name="twitter:card" content="summary_large_image">

        <!--  Non-Essential, But Recommended -->
        <meta property="og:description" content="{{ $meta['meta_description'] ?? "" }}">
        <meta property="og:site_name" content="Knowcrunch’s professional diploma in digital and social media marketing">
        <meta name="twitter:image" content="{{ $meta['meta_image']->url ?? "" }}">
        <meta name="twitter:image:alt" content="Knowcrunch’s professional diploma in digital and social media marketing">

        <meta property="fb:app_id" content="961275423898153">

        @if(isset($meta['meta_schema']) && $meta['meta_schema'])
            <script type="application/ld+json">
                {!! $meta['meta_schema'] !!}
            </script>
        @endif

    @endsection
@endif
