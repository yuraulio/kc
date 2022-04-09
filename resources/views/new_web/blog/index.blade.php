@extends("theme.layouts.master")

@section('metas')
    <meta charset="UTF-8">
    <meta name="description" content="KnowCrunch is offering high level professional training and specialized educational courses dedicated to fostering knowledge to others">
    <meta name="keywords" content="training, εκπαίδευση, seminars, σεμινάρια, events, courses, diplomas, certificates, business, marketing, digital marketing, εκπαίδευση στελεχών, executive training, knowcrunch, kc">
    <meta name="author" content="KnowCrunch">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
@endsection

@section('header')
    <title>{{ _('KnowCrunch Blog') }}</title>
@endsection


@section('blog-custom-css')
    <link type="text/css" href="{{ asset('binshops-blog.css') }}" rel="stylesheet">
@endsection

@section("content")

<main id="main-area" role="main">
    <div class="section section--dynamic-learning">
       <div class="container ">
           <div class="row mb-5">
               <div class="col-lg-12 marbot">
                    @if ($category)
                        <h1>{{$category->title}}</h1>
                    @else
                        <h1>{{ _('Our blog') }}</h1>
                    @endif
               </div>
           </div>
           <div class="row mb-5">
                <div class="col-lg-12 marbot">
                    @foreach($categories as $c)
                        <a class="badgelink" href="/v2/blog?c={{$c->id}}">
                            <label class="badge primary">{{ $c->title }}</label>
                        </a>
                    @endforeach
                </div>
            </div>
            <div class="blogpagex dynamic-courses-wrapper">
            @forelse($blog as $post)
                    @include("new_web.blog.index_loop")
            @empty
                <div class="col-md-12">
                    <div class='alert alert-danger'>No posts!</div>
                </div>
            @endforelse
            </div>
            {{-- @if (!is_array($posts) && $posts->hasPages())
                <div class="blog_pagination" >
                    {{ $posts->links('vendor.binshopsblog_admin.custom') }}
                </div>
            @endif --}}
       </div>
    </div>
</main>

@endsection
