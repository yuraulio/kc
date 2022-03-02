@php
    use App\Model\Admin\Page;
    use App\Model\Admin\Category;

    $c = request()->get('c');

    if ($c) {
        $category = Category::find($c);

        $categories = $category->subcategories()->whereHas("pages", function ($q) {
            $q->whereType("Blog");
        })->get();

        $blog = $category->pages()->whereType("Blog")->get();
    } else {
        $category = null;
        $blog = Page::whereType("Blog")->get();
        $categories = Category::whereNull("parent_id")->whereHas("pages", function ($q) {
            $q->whereType("Blog");
        })->get();
    }

    $blog_display = [];
    foreach ($column->template->inputs as $input){
        $blog_display[$input->key] = $input->value ?? "";
    }

@endphp

<div class="row mb-5">
    <div class="col-lg-12 marbot">
        @if ($category)
            <h1>{{$category->title}}</h1>
        @else
            <h1>{{ _('Our blog') }}</h1>
        @endif
    </div>
</div>
<div class="row mb-5">
    <div class="col-lg-12 marbot">
        @foreach($categories as $c)
            <a class="badgelink" href="{{Request::path()}}?c={{$c->id}}">
                <label class="badge primary">{{ $c->title }}</label>
            </a>
        @endforeach
    </div>
</div>
<div class="blogpagex dynamic-courses-wrapper">
    @forelse($blog as $post)
        @include("new_web.blog.index_loop", ["type" => $blog_display["blog_list"]])
        @empty
        <div class="col-md-12">
            <div class='alert alert-danger'>No posts!</div>
        </div>
    @endforelse
</div>