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
            </div
       </div>
    </div>
</main>

@endsection
