<div class="dynamic-courses-wrapper">
    <div class="item">
       <div class="left">
        <h2 class=''><a href='{{$post->url($locale)}}'>{{$post->title}}</a></h2>
        @if (config('binshopsblog.show_full_text_at_list'))
            <p>{!! $post->post_body_output() !!}</p>
        @else
            <p>{!! mb_strimwidth($post->post_body_output(), 0, 400, "...") !!}</p>
        @endif
        <div class="bottom">
            <div class="duration"><img width="20" src="/theme/assets/images/icons/icon-calendar.svg" alt="">  {{date('d M Y ', strtotime($post->post->posted_at))}} </div>
        </div>
       </div>
       <div class="right">
        <div class='text-center blog-image'>
            <?=$post->image_tag("medium", true, ''); ?>
        </div>
       </div>
       <!-- ./item -->
    </div>
 </div>
