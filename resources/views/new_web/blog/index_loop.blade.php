@php
    if (! function_exists('get_string_between')) {
        function get_string_between($string, $start, $end){
            $string = ' ' . $string;
            $ini = strpos($string, $start);
            if ($ini == 0) return '';
            $ini += strlen($start);
            $len = strpos($string, $end, $ini) - $ini;
            return substr($string, $ini, $len);
        }
    }

    $metaData = $post->metaData();
    $source = strtolower($source->title);
    $title = $post->getTitle() ?? "";

    $title = get_string_between($title, "<h1>", "</h1>");

    if (!$title) {
        $title = $post->title;
    }
@endphp

@if ($type->id == 2)
    {{-- grid view --}}
    <div class="blogpagex item">
        <div class="">
            <div class='text-center blogpagex-blog-image'>
                <a href="{{env("NEW_PAGES_LINK") . "/$source/$post->slug"}}">
                    <img src="{{$metaData["meta_image"]->url ?? ''}}" alt="{{$metaData["meta_image"]->alt_text ?? ''}}">
                </a>
            </div>
        </div>
        <div class="bottom">
            <div class="duration">
                @forelse($post->subcategories as $category)
                    <a class="me-2" href="{{env("NEW_PAGES_LINK") . "/$source?c=$category->id"}}">{{ $category->title }}</a>
                @empty
                    Uncategorized
                @endforelse
                | {{date('d M Y ', strtotime($post->created_at))}}
            </div>
        </div>
        <div class="blog-list-title">
            <a href="{{env("NEW_PAGES_LINK") . "/$source/$post->slug"}}"><h2>{!!$title!!}</h2></a>
            {{-- <p>{!! mb_strimwidth($featureData["feature_description"] ?? '', 0, 350, "...") !!}</p> --}}
        </div>
    </div>
@elseif ($type->id == 1)
    {{-- list view --}}
    <div class="col-12 item mb-5">
        <div class="row">
            <div class="col-md-6">
                <div class='text-center blogpagex-blog-image'>
                    <a href="{{env("NEW_PAGES_LINK") . "/$source/$post->slug"}}">
                        <img src="{{$metaData["meta_image"]->url ?? ''}}" alt="{{$metaData["meta_image"]->alt_text ?? ''}}">
                    </a>
                </div>
            </div>
            <div class="col-md-6">
                <div class="bottom">
                    <div class="duration">
                        @forelse($post->subcategories as $category)
                            <a class="me-2" href="{{env("NEW_PAGES_LINK") . "/$source?c=$category->id"}}">{{ $category->title }}</a>
                        @empty
                            Uncategorized
                        @endforelse
                        | {{date('d M Y ', strtotime($post->created_at))}}
                    </div>
                </div>
                <div class="color-reset">
                    <a href="{{env("NEW_PAGES_LINK") . "/$source/$post->slug"}}"><h2>{!!$title!!}</h2></a>
                    {{-- <p>{!! mb_strimwidth($featureData["feature_description" ?? ''], 0, 350, "...") !!}</p> --}}
                </div>
            </div>
        </div>
    </div>
@elseif ($type->id == 3)
    {{-- List 2 view --}}
    <div class="col-12 item mb-5 course-list-item-green">
        <div class="row">
            <div class="col-auto">
                <div class='text-center list2-image'>
                    <img src="{{$metaData["meta_image"]->url ?? ''}}" alt="{{$metaData["meta_image"]->alt_text ?? ''}}">
                </div>
            </div>
            <div class="col">
                <div class="bottom">
                    <div class="duration">
                        @forelse($post->subcategories as $category)
                            <a class="me-2" href="{{env("NEW_PAGES_LINK") . "/$source?c=$category->id"}}">{{ $category->title }}</a>
                        @empty
                            Uncategorized
                        @endforelse
                        | {{date('d M Y ', strtotime($post->created_at))}}
                    </div>
                </div>
                <div class="">
                    <h2 class=''><a href="{{env("NEW_PAGES_LINK") . "/$source/$post->slug"}}">{{$post->title ?? ''}}</a></h2>
                    {{-- <p>{!! mb_strimwidth($featureData["feature_description" ?? ''], 0, 350, "...") !!}</p> --}}
                </div>
            </div>
        </div>
    </div>
@endif

