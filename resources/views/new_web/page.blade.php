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

                <div>
                    @if($column->template->key == "meta_component")
                        @section('header')
                            @foreach ($column->template->inputs as $input)
                                @if($input->key == "meta_title")
                                <title>{{ $input->value }}</title>
                                @endif
                            @endforeach
                        @endsection

                    @elseif($column->template->key == "social_bar_component")
                        <div class="container blogx-container">
                            @include("global.social", ['summary' => 'summary', 'title' => 'summary'])
                        </div>

                    @elseif($column->template->key == "blog_header_component")
                        <div class="container blogx-container">
                            @foreach ($column->template->inputs as $input)
                                @if($input->key == "blog_header_title")
                                    <h1 class='blog_title' style="margin-top: 20px;">{{$input->value}}</h1>
                                @elseif($input->key == "blog_header_subtitle")
                                    <h5 class='blog_subtitle'>{{$input->value}}</h5>
                                @endif
                            @endforeach
                        </div>

                    @elseif($column->template->key == "text_editor_component")
                        @foreach ($column->template->inputs as $input)
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
                        @endforeach

                    @elseif($column->template->key == "full_sixe_image_component")
                        @foreach ($column->template->inputs as $input)
                            @if($data->width == "full")
                                <img src="{{ $input->value }}" class="center blog-full-width" alt="">

                            @elseif($data->width == "blog")
                                <img src="{{ $input->value }}" class="center blogx-container" alt="">
                            @else
                                <img src="{{ $input->value }}" class="center container" alt="">
                            @endif
                        @endforeach

                    @elseif($column->template->key == "comment_box_component")
                            @include("new_web.components.comments")
                    @else
                        @foreach ($column->template->inputs as $input)
                            {{$column->template->key}} {{$column->template->width}}
                            {{ json_encode($input) }}
                        @endforeach
                    @endif
                </div>
                
            @endforeach
        @endforeach
    </main>
@endsection

@section('blog-custom-js')
    <script src="{{asset('binshops-blog.js')}}"></script>
@endsection
