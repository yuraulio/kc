<div class="container blogx-container">
    <h1 class='blog_title' style="margin-top: 20px;">{{$post->title}}</h1>
    <h5 class='blog_subtitle'>{{$post->subtitle}}</h5>
    <div style="float:left;">
        @include("global.social", ['summary' => $post->gen_seo_title(), 'title' => $post->gen_seo_title()])
    </div>
    @if(\Auth::check() && \Auth::user()->canManageBinshopsBlogPosts())
        <div style="float: right;">
            <a href="{{$post->edit_url()}}" class="btn btn--sm btn--primary">Edit Post</a>
        </div>
    @endif
</div>

<?=$post->image_tag("large", false, 'center blog-full-width'); ?>

<div class="container blogx-container">
    <div class="blog_body_content">
        {!! $post->post_body_output() !!}
    </div>

    @include("global.social", ['summary' => $post->gen_seo_title(), 'title' => $post->gen_seo_title()])
    <hr/>
</div>

{{-- @includeWhen($post->author,"binshopsblog::partials.author",['post'=>$post]) --}}
{{-- @includeWhen($post->categories,"binshopsblog::partials.categories",['post'=>$post]) --}}
