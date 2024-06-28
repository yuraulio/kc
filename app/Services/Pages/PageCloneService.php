<?php

namespace App\Services\Pages;

use App\Model\Admin\MediaFile;
use App\Model\Admin\Page;
use Illuminate\Support\Str;
use Ramsey\Uuid\Uuid;

class PageCloneService
{
    public function execute(Page $page): Page
    {
        $newPage = new Page;

        $newPage->title = 'Copy of ' . $page->title;
        $newPage->template_id = $page->template_id;
        $newPage->content = $page->content;
        $newPage->published = false;
        $newPage->indexed = $page->indexed;
        $newPage->dynamic = $page->dynamic;
        $newPage->published_from = $page->published_from;
        $newPage->published_to = $page->published_to;
        $newPage->type = (gettype($page->type) == 'string') ? $page->type : $page->type['title'];
        $newPage->type_slug = Str::slug((gettype($page->type) == 'string') ? $page->type : $page->type['title'], '-');
        $newPage->slug = $page->slug . '-copy';
        $newPage->uuid = Uuid::uuid4();

        if ($newPage->type_slug === 'blog') {
            $priority = Page::where('type_slug', $newPage->type_slug)
                ->orderBy('priority', 'desc')
                ->value('priority');
            $newPage->priority = $priority ? $priority + 1 : 1;
        }

        $newPage->save();

        $this->syncImages($newPage);

        $categories = $page->categories->pluck('id')->merge($page->subcategories->pluck('id'))->toArray();

        $newPage->categories()->sync($categories);

        return $newPage;
    }

    private function syncImages(Page $page)
    {
        $data = collect(json_decode($page->content, true))->flatten();

        $images = [];

        foreach ($data as $item) {
            if (is_string($item)) {
                if (strpos($item, config('app.url') . '/uploads/') !== false) {
                    $image = MediaFile::whereUrl($item)->first();
                    if ($image) {
                        array_push($images, $image->id);
                    }
                }
            }
        }

        $page->files()->sync($images);
    }
}
