@extends("theme.layouts.master")

@section('metas')
    <meta charset="UTF-8">
    <meta name="description" content="{{ $post->meta_desc }}">
    <meta name="keywords" content="KnowCrunch, education, blog">
    <meta name="author" content="KnowCrunch">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
@endsection

@section('header')
    <title>KnowCrunch | {{ $post->gen_seo_title() }}</title>
@endsection


@section('blog-custom-css')
    <link type="text/css" href="{{ asset('binshops-blog.css') }}" rel="stylesheet">
@endsection

@section("content")

    @if(config("binshopsblog.reading_progress_bar"))
        <div id="scrollbar">
            <div id="scrollbar-bg"></div>
        </div>
    @endif

    {{--https://binshops.binshops.com/laravel/packages/binshopsblog-blog-system-for-your-laravel-app/help-documentation/laravel-blog-package-binshopsblog#guide_to_views--}}

    <main id="main-area" role="main">
        <div class="section section--dynamic-learning blog">
           <div class="container">

                @include("binshopsblog::partials.show_errors")
                @include("global.social", ['summary' => $post->gen_seo_title(), 'title' => $post->gen_seo_title()])
                @include("binshopsblog::partials.full_post_details")


                @if(config("binshopsblog.comments.type_of_comments_to_show","built_in") !== 'disabled')
                    <div class="" id='maincommentscontainer'>
                        <h2 class='text-center' id='binshopsblogcomments'>Comments</h2>
                        @include("binshopsblog::partials.show_comments")
                    </div>
                @else
                    {{--Comments are disabled--}}
                @endif

            </div>
        </div>
    </main>

@endsection

@section('blog-custom-js')
    <script src="{{asset('binshops-blog.js')}}"></script>
@endsection
