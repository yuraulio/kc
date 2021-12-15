@extends("theme.layouts.master")

@section('blog-custom-css')
    <link type="text/css" href="{{ asset('binshops-blog.css') }}" rel="stylesheet">
@endsection

@section("content")

<main id="main-area" role="main">
    <div class="section section--dynamic-learning">
       <div class="container">
            <div class="search-results-head">
                @php
                    $results = count ($search_results);
                    $search_term = \Request::get('s');
                @endphp
                <h1 class="search-results-title">Search results for <span>{{ $search_term }}</span></h1>
                @if($results > 0 )
                <p class="search-results-text"><span>{{$results}} result(s) </span> found containing the term <span>{{ $search_term }}.</span></p>
                @else
                <p class="search-results-text"><strong>{{$results}} result(s) </strong> were found containing the term <strong>{{ $search_term }}</strong>. Try again.</p>
                @endif
            </div>

            @php $search_count = 0;@endphp
            @forelse($search_results as $result)
                @if(isset($result->indexable))
                    @php $search_count += $search_count + 1; @endphp
                    @php $post = $result->indexable;@endphp
                    <div class="blogpagex dynamic-courses-wrapper">
                    @if($post && is_a($post,\BinshopsBlog\Models\BinshopsPostTranslation::class))
                        @include("binshopsblog::partials.index_loop")
                    @else
                        <div class='alert alert-danger'>Unable to show this search result - unknown type</div>
                    @endif
                    </div>
                @endif
            @endforeach
        </div>
    </div>
</main>

@endsection

