@extends("theme.layouts.master")


@section('blog-custom-css')
    <link type="text/css" href="{{ asset('binshops-blog.css') }}" rel="stylesheet">
@endsection

@section("content")
    @if(config("binshopsblog.reading_progress_bar"))
        <div id="scrollbar">
            <div id="scrollbar-bg"></div>
        </div>
    @endif

    <main id="main-area" role="main">
        @foreach ($content as $data)
            @foreach ($data->columns as $column)


                @foreach ($column->template->inputs as $input)
                <div style="">

                    @if($column->template->key == "meta_component")
                        @section('header')
                            @if($input->key == "meta_title")
                            <title>{{ $input->value }}</title>
                            @endif
                        @endsection

                    @elseif($column->template->key == "social_bar_component")
                    {{$column->template->key}}
                        <div class="container blogx-container">
                            @include("global.social", ['summary' => 'summary', 'title' => 'summary'])
                        </div>
                    @elseif($column->template->key == "blog_header_component")
                        <div class="container blogx-container">
                            @if($input->key == "blog_header_title")
                            <h1 class='blog_title' style="margin-top: 20px;">{{$input->value}}</h1>
                            @elseif($input->key == "blog_header_subtitle")
                            <h5 class='blog_subtitle'>{{$input->value}}</h5>
                            @endif
                        </div>

                    {{--  --}}

                    {{-- <div class="container blogx-container">
                        <div class="blog_body_content">
                            {!! $post->post_body_output() !!}
                        </div>

                        @include("global.social", ['summary' => $post->gen_seo_title(), 'title' => $post->gen_seo_title()])
                        <hr/>
                    </div> --}}
                    @elseif($column->template->key == "text_editor_component")
                        @if($data->width == "full")
                            <div class=" blog-full-width">
                                <div class="blog_body_content">
                                    {!! $input->value !!}
                                </div>
                            </div>
                        @elseif($data->width == "blog")
                            <div class="container blogx-container">
                                <div class="blog_body_content">
                                    {!! $input->value !!}
                                </div>
                            </div>
                        @else
                            <div class="container">
                                <div class="">
                                    {!! $input->value !!}
                                </div>
                            </div>
                        @endif

                    @elseif($column->template->key == "full_sixe_image_component")
                        @if($data->width == "full")
                            <img src="{{ $input->value }}" class="center blog-full-width" alt="">

                        @elseif($data->width == "blog")
                            <img src="{{ $input->value }}" class="center blogx-container" alt="">
                        @else
                            <img src="{{ $input->value }}" class="center container" alt="">
                        @endif
                    @else
                    {{$column->template->key}} {{$column->template->width}}
                    {{ json_encode($input) }}
                    @endif
                </div>
                @endforeach
            @endforeach
        @endforeach
    </main>
@endsection

@section('blog-custom-js')
    <script src="{{asset('binshops-blog.js')}}"></script>
@endsection
