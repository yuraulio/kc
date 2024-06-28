<?php

namespace App\Http\Controllers\Admin_api;

use App\Http\Controllers\Controller;
use App\Http\Resources\PageResource;
use App\Model\Admin\Page;
use App\Services\Pages\PageCloneService;
use Illuminate\Http\Request;

class ClonePageController extends Controller
{
    public function __invoke(Request $request, Page $page, PageCloneService $pageCloneService)
    {
        $this->authorize('create', Page::class, $request->user());

        $newPage = $pageCloneService->execute($page);

        return new PageResource($newPage);
    }
}
