<?php

namespace App\Services\Pages;

use App\Model\Admin\Category;
use App\Model\Admin\Page;

class BlogService
{
    public function index($request, $source)
    {
        $c = $request->get('c');
        $categories = null;
        $category = null;

        if ($c) {
            $category = Category::find($c);

            if ($category) {
                $categories = $category->subcategories()->whereHas('pages', function ($q) use ($source) {
                    $q->whereType($source);
                    if ($source == 'Knowledge') {
                        $q->withoutGlobalScope('knowledge')->where('slug', '!=', 'knowledge')->where('slug', '!=', 'knowledge_search');
                    }
                    if (isset($search_term) && $search_term !== null) {
                        $q->where('title', 'like', '%' . $search_term . '%');
                    }
                })->get();
            }
        } else {
            $categories = Category::whereNotNull('parent_id')->whereHas('pages', function ($q) use ($source) {
                $q->whereType($source);
                if ($source == 'Knowledge') {
                    $q = $q->withoutGlobalScope('knowledge')->where('slug', '!=', 'knowledge');
                }
                if (isset($search_term) && $search_term !== null) {
                    $q = $q->where('title', 'like', '%' . $search_term . '%');
                }
            })->get();
        }

        if ($category) {
            $blog = $category->pages()->whereType($source);
        } else {
            $blog = Page::whereType($source);
        }

        if ($source == 'Knowledge') {
            $blog = $blog->withoutGlobalScope('knowledge')
                ->where('slug', '!=', 'knowledge')
                ->where('slug', '!=', 'knowledge_search');
        }

        if (isset($search_term) && $search_term !== null) {
            $blog = $blog->where('title', 'like', '%' . $search_term . '%');
        }

        $blog->with('subcategories');

        if ($source == 'Blog') {
            $blog->orderBy('created_at', 'desc');
        }

        $blog = $blog->paginate(12);

        return [
            'category' => $category,
            'categories' => $categories,
            'blog' => $blog,
        ];
    }
}
