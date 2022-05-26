@php
    $metaData = $post->metaData();
    $source = strtolower($source->title);
    $title = $post->getTitle() ?? "";
    if (!$title) {
        $title = "<h2>" . $post->title . "</h2>";
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
                @forelse($post->categories as $category)
                    @if(!$category->parent_id)
                        <a href="{{env("NEW_PAGES_LINK") . "/$source?c=$category->id"}}">{{ $category->title }}</a>
                    @endif
                @empty
                    Uncategorized
                @endforelse
                | {{date('d M Y ', strtotime($post->created_at))}}
            </div>
        </div>
        <div class="color-reset">
            <a href="{{env("NEW_PAGES_LINK") . "/$source/$post->slug"}}">{!!$title!!}</a>
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
                        @forelse($post->categories as $category)
                            @if(!$category->parent_id)
                                <a href="{{env("NEW_PAGES_LINK") . "/$source?c=$category->id"}}">{{ $category->title }}</a>
                            @endif
                        @empty
                            Uncategorized
                        @endforelse
                        | {{date('d M Y ', strtotime($post->created_at))}}
                    </div>
                </div>
                <div class="color-reset">
                    <a href="{{env("NEW_PAGES_LINK") . "/$source/$post->slug"}}">{!!$title!!}</a>
                    {{-- <p>{!! mb_strimwidth($featureData["feature_description" ?? ''], 0, 350, "...") !!}</p> --}}
                </div>
            </div>
        </div>
    </div>
@endif

