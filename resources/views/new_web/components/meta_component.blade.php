@php
    $meta = [];
    foreach ($column->template->inputs as $input){
        $meta[$input->key] = $input->value ?? "";
    }

    $event = $dynamic_page_data["event"] ?? null;
    $instructor = $dynamic_page_data["instructor"] ?? null;
@endphp

@if($event)
    @section('header')
        <title>{{ $event['title'] }}</title>
        {!! $event->metable->getMetas() !!}
    @endsection
@endif

@if ($instructor)
    @section('header')
        <title>{{ $instructor->title . " " . $instructor->subtitle }}</title>
        {!! $instructor->metable->getMetas() !!}
    @endsection
@endif

@section('header')

    <title>{{ $meta['meta_title'] ?? "" }}</title>

    <meta name="description" content="{{ $meta['meta_description'] ?? "" }}">
    <meta name="keywords" content="{{ $meta['meta_keyword'] ?? "" }}">
    <meta name="image" content="{{ $meta['meta_image'] ?? "" }}">
    <meta name="author" content="{{ $meta['meta_author'] ?? "" }}">

    <!--  Essential META Tags -->
    <meta property="og:title" content="{{ $meta['meta_title'] ?? "" }}">
    <meta property="og:type" content="{{ $meta['meta_type'] ?? "" }}" />
    <meta property="og:image" content="{{ $meta['meta_image'] ?? "" }}">
    <meta property="og:url" content="{{ $_SERVER['REQUEST_URI'] }}">
    <meta name="twitter:card" content="summary_large_image">

    <!--  Non-Essential, But Recommended -->
    <meta property="og:description" content="{{ $meta['meta_description'] ?? "" }}">
    <meta property="og:site_name" content="Knowcrunch’s professional diploma in digital and social media marketing">
    <meta name="twitter:image:alt" content="Knowcrunch’s professional diploma in digital and social media marketing">

@endsection