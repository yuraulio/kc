@extends("theme.layouts.master",['title'=>$title])

@section('blog-custom-css')
    <link type="text/css" href="{{ asset('binshops-blog.css') }}" rel="stylesheet">
@endsection

@section("content")

<main id="main-area" role="main">
    <div class="section section--dynamic-learning">
       <div class="container">
            @php $search_count = 0;@endphp
            @forelse($search_results as $result)
                @if(isset($result->indexable))
                    @php $search_count += $search_count + 1; @endphp
                    @php $post = $result->indexable;@endphp
                    @if($post && is_a($post,\BinshopsBlog\Models\BinshopsPostTranslation::class))
                        @include("binshopsblog::partials.index_loop")
                    @else
                        <div class='alert alert-danger'>Unable to show this search result - unknown type</div>
                    @endif
                @endif
            @endforeach
    </div>
    </div>
</main>

@endsection

