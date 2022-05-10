@php
    use App\Model\Admin\Page;
    use App\Model\Admin\Category;

    $blog_display = [];
    foreach ($column->template->inputs as $input){
        $blog_display[$input->key] = $input->value ?? "";
    }

    $source = $blog_display["blog_source"]->title;

    $c = request()->get('c');

    if ($c) {
        $category = Category::find($c);

        $categories = $category->subcategories()->whereHas("pages", function ($q) use ($source) {
            $q->whereType($source);
            if ($source == "Knowledge") {
                $q->withoutGlobalScope("knowledge")->where("slug", "!=", "knowledge");
            }
            if (isset($search_term) && $search_term !== null) {
                $q = $q->where('title', 'like', '%' . $search_term . '%');
            }
        })->get();
    } else {
        $category = null;
        $categories = Category::whereNull("parent_id")->whereHas("pages", function ($q) use ($source) {
            $q->whereType($source);
            if ($source == "Knowledge") {
                $q = $q->withoutGlobalScope("knowledge")->where("slug", "!=", "knowledge");
            }
            if (isset($search_term) && $search_term !== null) {
                $q = $q->where('title', 'like', '%' . $search_term . '%');
            }
        })->get();
    }
    $blog = Page::whereType($source);

    if ($source == "Knowledge") {
        $blog = $blog->withoutGlobalScope("knowledge")->where("slug", "!=", "knowledge");
    }

    if (isset($search_term) && $search_term !== null) {
        $blog = $blog->where('title', 'like', '%' . $search_term . '%');
    }

    $blog = $blog->get();
    
@endphp

<div class="row mb-5">
    <div class="col-lg-12 marbot">
        @if ($category)
            <h1>{{$category->title}}</h1>
        @else
            @if (isset($blog_display["blog_title"]))
                {!! $blog_display["blog_title"] !!}
            @endif
        @endif
    </div>
</div>

@if (!isset($show_categories))
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

<div class="blogpagex dynamic-courses-wrapper">
    @forelse($blog as $post)
        @include("new_web.blog.index_loop", ["type" => $blog_display["blog_list"], "source" => $blog_display["blog_source"]])
        @empty
        <div class="col-md-12">
            <div class='alert alert-danger'>No posts!</div>
        </div>
    @endforelse
</div>