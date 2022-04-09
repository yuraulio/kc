<?php

namespace App\Http\Controllers\Admin_api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateAdminPageRequest;
use App\Http\Requests\UpdateAdminPageRequest;
use App\Http\Resources\PageResource;
use App\Jobs\DeleteMultiplePages;
use App\Model\Admin\Category;
use App\Model\Admin\Comment;
use App\Model\Admin\MediaFile;
use App\Model\Admin\Page;
use App\Model\Admin\Redirect;
use App\Model\Admin\Template;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Ramsey\Uuid\Uuid;

class PagesController extends Controller
{
    /**
     * Get pages
     *
     * @return AnonymousResourceCollection
     */
    public function index(Request $request)
    {
        $this->authorize('viewAny', Page::class, Auth::user());
        try {
            $pages = Page::withoutGlobalScope('published')
                ->with('template', 'categories.subcategories')
                ->tableSort($request);

            $pages = $this->filters($request, $pages);
            
            $pages = $pages->paginate($request->per_page ?? 50);
            return PageResource::collection($pages);
        } catch (Exception $e) {
            Log::error("Failed to get pages. " . $e->getMessage());
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }

    private function filters($request, $pages)
    {
        $pages->lookForOriginal($request->filter);
        if ($request->dynamic !== null) {
            $pages->where("dynamic", $request->dynamic == "true" ? true : false);
        }
        if ($request->template !== null) {
            $pages->whereHas("template", function ($q) use ($request) {
                $q->where("id", $request->template);
            });
        }
        if ($request->published !== null) {
            $pages->wherePublished($request->published);
        }
        if ($request->type !== null) {
            $pages->whereType($request->type);
        }
        if ($request->category) {
            $pages->whereHas("categories", function ($q) use ($request) {
                $q->where("id", $request->category);
            });
        }
        if ($request->subcategory) {
            $pages->whereHas("subcategories", function ($q) use ($request) {
                $q->where("id", $request->subcategory);
            });
        }
        return $pages;
    }

    /**
     * Add page
     *
     * @return PageResource
     */
    public function store(CreateAdminPageRequest $request)
    {
        $this->authorize('create', Page::class, Auth::user());

        try {
            $page = new Page();
            $page->title = $request->title;
            $page->template_id = $request->template_id;
            $page->content = $request->content;
            $page->published = $request->published;
            $page->indexed = $request->indexed;
            $page->dynamic = $request->dynamic;
            $page->user_id = Auth::user()->id;
            $page->published_from = $request->published_from;
            $page->published_to = $request->published_to;
            $page->type = $request->type;
            $page->type_slug = Str::slug($request->type, '-');
            $page->uuid = Uuid::uuid4();
            $page->save();

            if ($request->slug) {
                $page->slug = $request->slug;
                $page->save();
            }

            $this->syncImages($page);

            $categories = $request->categories ?? [];
            $subcategories = $request->subcategories ?? [];

            $page->categories()->sync(collect(array_merge($categories, $subcategories) ?? [])->pluck('id')->toArray());

            $page->load('template', 'categories.subcategories');

            return new PageResource($page);
        } catch (Exception $e) {
            Log::error("Failed to add new page. " . $e->getMessage());
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }

    /**
     * Get page
     *
     * @return PageResource
     */
    public function show(int $id)
    {
        try {
            $page = Page::withoutGlobalScope('published')->whereId($id)->with('template')->first();

            $this->authorize('view', $page, Auth::user());

            return new PageResource($page);
        } catch (Exception $e) {
            Log::error("Failed to get page. " . $e->getMessage());
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }

    /**
     * Edit page
     *
     * @return PageResource
     */
    public function update(UpdateAdminPageRequest $request, int $id)
    {
        try {
            $page = Page::withoutGlobalScope('published')->find($id);

            $this->authorize('update', $page, Auth::user());

            $old_slug = $page->slug;

            $page->title = $request->title;
            $page->template_id = $request->template_id;
            $page->content = $request->content;
            $page->published = $request->published;
            $page->indexed = $request->indexed;
            $page->dynamic = $request->dynamic;
            $page->published_from = $request->published_from;
            $page->published_to = $request->published_to;
            $page->type = $request->type;
            $page->type_slug = Str::slug($request->type, '-');
            $page->slug = $request->slug;
            $page->uuid = $page->uuid ?? Uuid::uuid4();
            $page->save();

            $this->syncImages($page);

            $categories = $request->categories ?? [];
            $subcategories = $request->subcategories ?? [];

            $page->categories()->sync(collect(array_merge($categories, $subcategories) ?? [])->pluck('id')->toArray());

            $page->load('template', 'categories');

            $new_slug = $page->slug;
            if ($old_slug && $new_slug != $old_slug) {
                $redirect = new Redirect();
                $redirect->page_id = $page->id;
                $redirect->old_slug = $old_slug;
                $redirect->save();
            }

            return new PageResource($page);
        } catch (Exception $e) {
            Log::error("Failed to edit page. ", [$e]);
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }

    /**
     * Delete page
     *
     * @return JsonResponse
     */
    public function destroy(int $id): JsonResponse
    {
        try {
            $page = Page::withoutGlobalScope('published')->find($id);

            $this->authorize('delete', $page, Auth::user());

            $page->categories()->detach();
            $page->subcategories()->detach();
            $page->files()->detach();

            Comment::where("page_id", $page->id)->delete();

            $page->delete();

            return response()->json(['message' => 'success'], 200);
        } catch (Exception $e) {
            Log::error("Failed to delete page. " . $e->getMessage());
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }

    public function updatePublished(int $id): JsonResponse
    {
        try {
            $page = Page::withoutGlobalScope('published')->find($id);

            $this->authorize('publish', $page, Auth::user());

            $page->published = !$page->published;
            $page->save();

            return response()->json(['message' => 'success'], 200);
        } catch (Exception $e) {
            Log::error("Failed to publish page. " . $e->getMessage());
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }

    public function uploadImage(Request $request): JsonResponse
    {
        $this->authorize('imgUpload', Page::class, Auth::user());

        try {
            $path = Storage::disk('public')->putFile('page_files', $request->file('file'), 'public');
            $url = config('app.url'). "/uploads/" . $path;
            return response()->json(['url' => $url], 200);
        } catch (Exception $e) {
            Log::error("Failed update file . " . $e->getMessage());
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }

    public function getDisplayOptions()
    {
        return [
            "data" => [
                [
                    "id" => 1,
                    "title" => "List"
                ],
                [
                    "id" => 2,
                    "title" => "Grid"
                ]
            ]
        ];
    }

    public function getGaleryDisplayOptions()
    {
        return [
            "data" => [
                [
                    "id" => 1,
                    "title" => "Grid"
                ],
                [
                    "id" => 2,
                    "title" => "Carousel"
                ],
                [
                    "id" => 3,
                    "title" => "Row"
                ]
            ]
        ];
    }

    public function getFormTypes()
    {
        return [
            "data" => [
                [
                    "id" => 1,
                    "title" => "Contact"
                ],
                [
                    "id" => 2,
                    "title" => "Corporate training"
                ],
                [
                    "id" => 3,
                    "title" => "Become an instructor"
                ]
            ]
        ];
    }

    public function getEventTypes()
    {
        return [
            "data" => [
                [
                    "id" => 1,
                    "title" => "In class Events"
                ],
                [
                    "id" => 2,
                    "title" => "Elearning Events"
                ],
                [
                    "id" => 3,
                    "title" => "Elearning Free Events"
                ],
                [
                    "id" => 4,
                    "title" => "In class Free Events"
                ]
            ]
        ];
    }

    public function getHomepageGalleryOptions()
    {
        return [
            "data" => [
                [
                    "id" => 1,
                    "title" => "Trusted Brands"
                ],
                [
                    "id" => 2,
                    "title" => "Media Logos"
                ]
            ]
        ];
    }

    private function syncImages($page)
    {
        $data = collect(json_decode($page->content, true))->flatten();

        $images = [];

        foreach ($data as $item) {
            if (is_string($item)) {
                if (strpos($item, env('APP_URL') . '/uploads/pages_media') !== false) {
                    $image = MediaFile::whereUrl($item)->first();
                    if ($image) {
                        array_push($images, $image->id);
                    }
                }
                // sometimes there a two slashed
                if (strpos($item, env('APP_URL') . '/uploads//pages_media') !== false) {
                    $image = MediaFile::whereUrl($item)->first();
                    if ($image) {
                        array_push($images, $image->id);
                    }
                }
            }
        }

        $page->files()->sync($images);
    }

    public function deleteMultiple(Request $request)
    {
        try {
            $ids = $request->selected;
        
            // authorize action
            $categories = Page::findOrFail($ids);
            foreach ($categories as $category) {
                $this->authorize('delete', $category, Auth::user());
            }

            // start job
            DeleteMultiplePages::dispatch($ids, Auth::user());

            return response()->json(['message' => 'success'], 200);
        } catch (Exception $e) {
            Log::error("Failed to bulk delete templates. " . $e->getMessage());
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }

    public function widgets(Request $request)
    {
        return [
            [
                "Pages",
                $this->pagesCount($request),
            ],
            [
                "Published pages",
                $this->publishedPagesCount($request),
            ],
            [
                "Unpublished pages",
                $this->unpublishedPagesCount($request),
            ],
            [
                "Blog articles",
                $this->articlePagesCount($request),
            ]

        ];
    }

    public function pagesCount($request)
    {
        try {
            $pages = Page::withoutGlobalScope('published');
            $pages = $this->filters($request, $pages);
            return $pages->count();
        } catch (Exception $e) {
            Log::warning("(pages widget) Failed to get pages count. " . $e->getMessage());
            return "0";
        }
    }

    public function publishedPagesCount($request)
    {
        try {
            $pages = Page::withoutGlobalScope('published')->wherePublished(true);
            $pages = $this->filters($request, $pages);
            return $pages->count();
        } catch (Exception $e) {
            Log::warning("(pages widget) Failed to get published pages count. " . $e->getMessage());
            return "0";
        }
    }

    public function unpublishedPagesCount($request)
    {
        try {
            $pages = Page::withoutGlobalScope('published')->wherePublished(false);
            $pages = $this->filters($request, $pages);
            return $pages->count();
        } catch (Exception $e) {
            Log::warning("(pages widget) Failed to get ubpublished pages count. " . $e->getMessage());
            return "0";
        }
    }

    public function articlePagesCount($request)
    {
        try {
            $pages = Page::withoutGlobalScope('published')->whereType("Blog");
            $pages = $this->filters($request, $pages);
            return $pages->count();
        } catch (Exception $e) {
            Log::warning("(pages widget) Failed to get ubpublished pages count. " . $e->getMessage());
            return "0";
        }
    }
}
