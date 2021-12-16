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
       <div class="container ">
            @if (config('binshopsblog.search.search_enabled') )
                @include('binshopsblog::sitewide.search_form')
            @endif
            <div class="row mb-5">
                <div class="col-lg-12 marbot maralign">
                    {{-- <span>Top Categories:</span> --}}
                    @foreach($subcategories as $subcategory)
                        <a class="badgelink" href="/en/blog/categories/{{$subcategory->slug}}">
                            <label class="badge primary">{{ $subcategory->category_name }}</label>
                        </a>
                    @endforeach
                </div>
            </div>
            <div class="maralign">
            @if($category)
                <h1>{{$category->category_name}}</h1>
                <div class="blogpagex dynamic-courses-wrapper catx">
                    <div class="catx-sub">{!! $category->category_description !!}</div>
                    @forelse(collect($posts)->slice(0, 1) as $post)
                        @php
                            $post->inter = true;
                        @endphp
                        @include("binshopsblog::partials.index_loop")
                    @empty

                    @endforelse
                </div>
            @endif
            </div>
            <div class="blogpagex dynamic-courses-wrapper">
            @forelse($posts as $post)
                @if(!$post->inter)
                    @include("binshopsblog::partials.index_loop")
                @endif
            @empty
                <div class="col-md-12">
                    <div class='alert alert-danger'>No posts!</div>
                </div>
            @endforelse
            </div>
            @if (!is_array($posts) && $posts->hasPages())
                <div class="blog_pagination" >
                    {{ $posts->links('vendor.binshopsblog_admin.custom') }}
                </div>
            @endif
       </div>
    </div>
</main>

@endsection
