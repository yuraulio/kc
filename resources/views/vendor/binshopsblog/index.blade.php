@extends("theme.layouts.master",['title'=>$title])

@section('blog-custom-css')
    <link type="text/css" href="{{ asset('binshops-blog.css') }}" rel="stylesheet">
@endsection

@section("content")

<main id="main-area" role="main">
    <div class="section section--dynamic-learning">
       <div class="container">
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
