    <div class="blogpagex item">
        <div class="">
            <div class='text-center blogpagex-blog-image'>
                <img src="{{$post->featureData()["feature_image"]}}">
            </div>
        </div>
        <div class="bottom">
            <div class="duration">
                @forelse($post->categories as $category)
                @php
                @endphp
                    @if(!$category->parent_id)
                        <a href="{{"/v2/blog?c=$category->id"}}">{{ $category->title }}</a>
                    @endif
                @empty
                    Uncategorized
                @endforelse
                | {{date('d M Y ', strtotime($post->created_at))}}
            </div>
        </div>
       <div class="">
            <h2 class=''><a href="{{"/v2/blog/$post->slug"}}">{{$post->featureData()["feature_title"]}}</a></h2>
            <p>{!! mb_strimwidth($post->featureData()["feature_description"], 0, 350, "...") !!}</p>
       </div>

       <!-- ./item -->
    </div>
