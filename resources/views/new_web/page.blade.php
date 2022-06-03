@extends("new_web.layouts.master")

@section('metas')

    @if(isset($dynamic_page_data['event']) && !$dynamic_page_data['event']['index'] )
        <meta name="robots" content="noindex, nofollow" />
    @endif

@stop

@section('blog-custom-css')
    <link type="text/css" href="{{ asset('binshops-blog.css') }}" rel="stylesheet">
@endsection

@section("content")
    @if(config("binshopsblog.reading_progress_bar"))
        <div id="scrollbar">
            <div id="scrollbar-bg"></div>
        </div>
    @endif

    <main id="main-area" role="main" class="bootstrap-classes">
        <div id="app">
            @foreach ($content as $data)

                @include("new_web.layouts.rows")

            @endforeach
        </div>
    </main>
@endsection

@section('blog-custom-js')
    <script src="{{asset('binshops-blog.js')}}"></script>
    <script src="{{asset('js/blog.js')}}"></script>
@endsection
