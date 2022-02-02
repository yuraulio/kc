@extends("new_web.layouts.master")

@section('meta')
    {{-- Basic --}}
    {{-- <meta name="description" content="{{ $post->meta_desc }}">
    <meta name="keywords" content="{{ $post->short_description }}">
    <meta name="image" content="{{ $post->image_url() }}">
    <meta name="author" content="KnowCrunch">

    <!--  Essential META Tags -->
    <meta property="og:title" content="{{ $post->gen_seo_title() }}">
    <meta property="og:type" content="article" />
    <meta property="og:image" content="{{ $post->image_url() }}">
    <meta property="og:url" content="{{ $post->url('en') }}">
    <meta name="twitter:card" content="summary_large_image">

    <!--  Non-Essential, But Recommended -->
    <meta property="og:description" content="{{ $post->meta_desc }}">
    <meta property="og:site_name" content="{{ $post->title }}">
    <meta name="twitter:image:alt" content="{{ $post->title }}"> --}}
@endsection

@section('css')
    <link type="text/css" href="{{ asset('binshops-blog.css') }}" rel="stylesheet">
@endsection

@section("content")

    <main id="main-area" role="main">
        <div class="section section--dynamic-learning blog">
            <div class="container blogx-container">
                <?php $test1="test data"; ?>
                @include("new_web.components.blog_header", ['test2' => $test1])






                {{-- <div style="float:left;">
                    @include("global.social", ['summary' => $post->gen_seo_title(), 'title' => $post->gen_seo_title()])
                </div>
                @if(\Auth::check() && \Auth::user()->canManageBinshopsBlogPosts())
                    <div style="float: right;">
                        <a href="{{$post->edit_url()}}" class="btn btn--sm btn--primary">Edit Post</a>
                    </div>
                @endif --}}
            </div>
        </div>
    </main>

@endsection
