    <div class="blogpagex item {{ $post->inter ? 'item-inter' : ''}}">
        <div class="">
            <div class='text-center blogpagex-blog-image'>
                <?=$post->image_tag("medium", true, ''); ?>
            </div>
        </div>
        <div class="bottom">
            <div class="duration">{{-- <img width="20" src="/theme/assets/images/icons/icon-calendar.svg" alt="">  --}}
                @if($post->post)
                @forelse($post->post->categories as $category)
                @php
                $cat = $category->categoryTranslations->where('lang_id', $lang_id)->first();
                @endphp
                    <a href="{{"/en/blog/categories/$cat->slug"}}">{{ $cat->category_name }}</a>
                @empty
                    Uncategorized
                @endforelse
                | {{date('d M Y ', strtotime($post->post->posted_at))}}
                @endif
            </div>
        </div>
       <div class="">
        <h2 class=''><a href='{{$post->url($locale)}}'>{{mb_strimwidth($post->title, 0, 70, "...") }}</a></h2>
        {{-- @if (config('binshopsblog.show_full_text_at_list'))
            <p>{!! $post->post_body_output() !!}</p>
        @else
            <p>{!! mb_strimwidth($post->post_body_output(), 0, 400, "...") !!}</p>
        @endif --}}
        @if($post->inter)
        <p>{!! mb_strimwidth($post->post_body_output(), 0, 350, "...") !!}</p>
        @endif

       </div>

       <!-- ./item -->
    </div>
