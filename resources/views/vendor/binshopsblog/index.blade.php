@extends("theme.layouts.master")

@section('metas')
    <meta charset="UTF-8">
    <meta name="description" content="KnowCrunch is offering high level professional training and specialized educational courses dedicated to fostering knowledge to others">
    <meta name="keywords" content="training, εκπαίδευση, seminars, σεμινάρια, events, courses, diplomas, certificates, business, marketing, digital marketing, εκπαίδευση στελεχών, executive training, knowcrunch, kc">
    <meta name="author" content="KnowCrunch">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
@endsection

@section('header')
    <title>KnowCrunch Blog</title>
@endsection


@section('blog-custom-css')
    <link type="text/css" href="{{ asset('binshops-blog.css') }}" rel="stylesheet">
@endsection

@section("content")

<main id="main-area" role="main">
    <div class="section section--dynamic-learning">
       <div class="container">
            @if (config('binshopsblog.search.search_enabled') )
                @include('binshopsblog::sitewide.search_form')
            @endif
            @forelse($posts as $post)
                @include("binshopsblog::partials.index_loop")
            @empty
                <div class="col-md-12">
                    <div class='alert alert-danger'>No posts!</div>
                </div>
            @endforelse
            @if ($posts->hasPages())
                <div class="blog_pagination">
                    @if (!$posts->onFirstPage())
                        <a  href="{{ $posts->previousPageUrl() }}">&laquo;</a>
                    @endif

                    @foreach ( $posts->getUrlRange(max(1, $posts->currentPage() - 2), min($posts->count() + 3, $posts->count() + 1)) as $page => $url)
                        <a @if($posts->currentPage() == $page) class="active" @endif href="{{ $url }}">{{ $page }}</a>
                    @endforeach

                    @if ($posts->hasMorePages())
                        <a href="{{  $posts->nextPageUrl()  }}">&raquo;</a>
                    @endif
                </div>
            @endif
       </div>
    </div>
</main>

@endsection
