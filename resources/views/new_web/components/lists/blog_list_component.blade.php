@php
$blog_display = [];
foreach ($column->template->inputs as $input) {
  $blog_display[$input->key] = $input->value ?? "";
}

$source = $blog_display["list_source"]->title;

list(
  'category' => $category,
  'categories' => $categories,
  'blog' => $blog
) = app(\App\Services\Pages\BlogService::class)->index(request(), $source);

@endphp

<div class="row mb-5">
    <div class="col-lg-12 marbot">
        @if ($category)
            <h1>{{$category->title}}</h1>
        @endif
    </div>
</div>

@if (!isset($show_categories) && isset($categories))
<div class="row mb-5">
    <div class="col-lg-12 marbot">
        @foreach($categories as $c)
            <a class="badgelink" href="{{Request::path()}}?c={{$c->id}}">
                <label class="badge primary">{{ $c->title }}</label>
            </a>
        @endforeach
    </div>
</div>
@endif

<div class="blogpagex dynamic-courses-wrapper mb-3">
    @forelse($blog as $post)
        @include("new_web.blog.index_loop", ["type" => $blog_display["list_type"], "source" => $blog_display["list_source"]])
        @empty
        <div class="col-md-12">
            <div class='alert alert-danger'>No posts!</div>
        </div>
    @endforelse
</div>

<div class="mb-5 blog-list-pagination">
    {{ $blog->links() }}
</div>
