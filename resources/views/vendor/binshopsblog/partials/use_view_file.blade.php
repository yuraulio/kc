{{--This is used if a blog post has a 'use_view_file' value.--}}{{--It will (attempt to) load the view from /resources/views/custom_blog_posts/$use_view_file.blade.php. If that file doesn't exist, it'll show an error. --}}
@if(\View::exists($post->full_view_file_path()))

    {{--view file existed, so include it.--}}
    @include("custom_blog_posts." . $post->use_view_file, ['post' =>$post])

@else
    <div class='alert alert-danger'>Sorry, but there is an error showing that blog post. Please come back later.</div>
@endif
