@php
    use App\Model\Admin\Page;
    use App\Model\Admin\Category;

    $c = request()->get('c');

    if ($c) {
        $category = Category::find($c);

        $categories = $category->subcategories()->whereHas("pages", function ($q) {
            $q->whereType("Knowledge");
            $q->withoutGlobalScope("knowledge")->where("slug", "!=", "knowledge")->where("slug", "!=", "knowledge_search");
            if (isset($search_term) && $search_term !== null) {
                $q = $q->where('title', 'like', '%' . $search_term . '%');
            }
        })->with("image")->get();
    } else {
        $category = null;
        $categories = Category::whereNull("parent_id")->whereHas("pages", function ($q) {
            $q->whereType("Knowledge");
            $q = $q->withoutGlobalScope("knowledge")->where("slug", "!=", "knowledge");
            if (isset($search_term) && $search_term !== null) {
                $q = $q->where('title', 'like', '%' . $search_term . '%');
            }
        })->with("image")->get();
    }

    if ($category) {
        $blog = $category->pages();
    } else {
        $blog = Page::whereType("Knowledge");
    }

    $blog = $blog->withoutGlobalScope("knowledge")->where("slug", "!=", "knowledge")->where("slug", "!=", "knowledge_search");

    if (isset($search_term) && $search_term !== null) {
        $blog = $blog->where('title', 'like', '%' . $search_term . '%');
    }

    $blog = $blog->paginate(10);

    $source = new stdClass();
    $source->title = "Knowledge";

    $type = new stdClass();
    $type->id = 3;

    //dd($categories[0]->parent()->first()->image()->first()->url);
@endphp

@if (count($categories))
    <div class="row mb-5 mt-5">
        @foreach($categories as $c)
            <div class="col-lg-3 col-md-4 col-sm-6 marbot knowledge-card-column mb-2 mt-2">
                <div class="knowledge-card">
                    <a class="" href="{{Request::path()}}?c={{$c->id}}">
                        <div class="card-body">
                            <h5 class="text-center knowledge-card-title">{{ $c->title }}</h5>
                            @php
                                $image = $c->image()->first()->url ?? "";
                                if (!$image) {
                                    $image = $c->parent()->first()->image()->first()->url ?? "";
                                }
                            @endphp

                            <img class="knowledge-card-image mb-5" src="{{ $image ?? '' }}">
                            
                        </div>
                    </a>
                </div>
            </div>
        @endforeach
    </div>
@else

    <h3 class="mt-5">{{ $category->title ?? "" }}</h3>

    <div class="blogpagex dynamic-courses-wrapper mb-3">
        @forelse($blog as $post)
            @include("new_web.blog.index_loop", ["type" => $type, "source" => $source])
            @empty
            <div class="col-md-12">
                <div class='alert alert-danger'>No posts!</div>
            </div>
        @endforelse
    </div>

    @if($blog->links())
    <div class="mb-5 blog-list-pagination">
        {{ $blog->links() }}
    </div>
    @endif
@endif