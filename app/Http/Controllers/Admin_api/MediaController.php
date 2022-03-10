<?php

namespace App\Http\Controllers\Admin_api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateAdminPageRequest;
use App\Http\Requests\CreateMediaFolderRequest;
use App\Http\Resources\MediaFileResource;
use App\Http\Resources\MediaFolderResource;
use App\Http\Resources\PageResource;
use App\Model\Admin\Category;
use App\Model\Admin\MediaFile;
use App\Model\Admin\MediaFolder;
use App\Model\Admin\Page;
use App\Model\Admin\Template;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Image;

class MediaController extends Controller
{
    /**
     * Get folders
     *
     * @return AnonymousResourceCollection
     */
    public function index(Request $request)
    {
        //$this->authorize('viewAny', Page::class, Auth::user());

        try {
            $folders = MediaFolder::lookForOriginal($request->filter)
                                ->with('children')->whereParentId($request->folder_id ?? null)
                                ->orderBy('created_at', 'desc')->get();

            return MediaFolderResource::collection($folders);
        } catch (Exception $e) {
            Log::error("Failed to get folders. " . $e->getMessage());
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }

    public function files(Request $request)
    {
        //$this->authorize('viewAny', Page::class, Auth::user());

        try {
            $files = MediaFile::lookForOriginal($request->filter)
                                ->when($request->parent != false && $request->parent != 'false', function ($q) {
                                    return $q->whereNull('parent_id');
                                })
                                ->with('user')
                                ->withCount('pages')
                                ->when($request->folder_id != null, function ($q) use ($request) {
                                    return $q->where('folder_id', $request->folder_id);
                                })
                                ->orderBy('created_at', 'desc')->paginate(20);
            if ($request->parent != false && $request->parent != 'false') {
                $files->load('subfiles');
            }
            return MediaFileResource::collection($files);
        } catch (Exception $e) {
            Log::error("Failed to get files. " . $e->getMessage());
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }

    /**
     * Add mediaFolder
     *
     * @return PageResource
     */
    public function store(CreateMediaFolderRequest $request)
    {
        //$this->authorize('create', Page::class, Auth::user());

        try {
            $cname = Str::slug($request->name, '_');
            $path = "/pages_media/$cname";

            Storage::disk('public')->makeDirectory($path);

            $mediaFolder = new MediaFolder();
            $mediaFolder->name = $request->name;
            $mediaFolder->path = $path;
            $mediaFolder->url = config('app.url'). "/uploads" . $path;
            $mediaFolder->user_id = Auth::user()->id;
            $mediaFolder->save();

            return new MediaFolderResource($mediaFolder);
        } catch (Exception $e) {
            Log::error("Failed to add new mediaFolder. " . $e->getMessage());
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }

    public function uploadImage(Request $request): JsonResponse
    {
        $image = $request->file('file');

        try {
            if ($request->directory) {
                $mediaFolder = MediaFolder::findOrFail($request->directory);
                $path = $mediaFolder->path . "/";
            } else {
                $path = "/pages_media/random/";

                if (!Storage::disk('public')->exists($path)) {
                    Storage::disk('public')->makeDirectory($path);
                }

                $mediaFolder = MediaFolder::whereName("random")->first();
                if (!$mediaFolder) {
                    $mediaFolder = new MediaFolder();
                    $mediaFolder->name = "random";
                    $mediaFolder->path = $path;
                    $mediaFolder->url = config('app.url'). "/uploads" . $path;
                    $mediaFolder->user_id = Auth::user()->id;
                    $mediaFolder->save();
                }
            }

            $imgpath = $path . ''. (($request->imgname ? $request->imgname . "_" . getimagesize($image)[0] . "x" . getimagesize($image)[1] . "_" . $request->compression . ".". $image->extension() : $image->getClientOriginalName()));

            $file = Storage::disk('public')->putFileAs($path, $request->file('file'), ($request->imgname ? $request->imgname . "_" . getimagesize($image)[0] . "x" . getimagesize($image)[1] . "_" . $request->compression . ".". $image->extension() : $image->getClientOriginalName()), 'public');

            $mfile = $this->storeFile(($request->imgname ? $request->imgname . "_" . getimagesize($image)[0] . "x" . getimagesize($image)[1] . "_" . $request->compression . ".". $image->extension() : $image->getClientOriginalName()), $imgpath, $mediaFolder->id, $image->getSize(), null, $request->alttext);
            $mfile->pages = [];


            return response()->json(['data' => [$mfile]], 200);
        } catch (Exception $e) {
            Log::error("Failed update file . " . $e->getMessage());
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }

    public function uploadRegFile(Request $request): JsonResponse
    {
        $image = $request->file('file');

        try {
            $cname = $this->getRealName($request->imgname ? $request->imgname .".". $image->extension() :  $image->getClientOriginalName());
            if ($request->directory) {
                $mediaFolder = MediaFolder::findOrFail($request->directory);
                $path = $mediaFolder->path . "/";
            } else {
                $path = "/pages_media/random/";

                if (!Storage::disk('public')->exists($path)) {
                    Storage::disk('public')->makeDirectory($path);
                }

                $mediaFolder = MediaFolder::whereName("random")->first();
                if (!$mediaFolder) {
                    $mediaFolder = new MediaFolder();
                    $mediaFolder->name = "random";
                    $mediaFolder->path = $path;
                    $mediaFolder->url = config('app.url'). "/uploads" . $path;
                    $mediaFolder->user_id = Auth::user()->id;
                    $mediaFolder->save();
                }
            }

            // Store edited image
            $imgpath = $path . ''. (($request->imgname ? $request->imgname . "_" . getimagesize($image)[0] . "x" . getimagesize($image)[1] . "_" . $request->compression . ".". $image->extension() : $image->getClientOriginalName()));

            $file = Storage::disk('public')->putFileAs($path, $request->file('file'), ($request->imgname ? $request->imgname . "_" . getimagesize($image)[0] . "x" . getimagesize($image)[1] . ".". $image->extension() : $image->getClientOriginalName()), 'public');

            $mfile = $this->storeFile(($request->imgname ? $request->imgname . "_" . getimagesize($image)[0] . "x" . getimagesize($image)[1] . "_" . $request->compression . ".". $image->extension() : $image->getClientOriginalName()), $imgpath, $mediaFolder->id, $image->getSize(), null, null);
            $mfile->pages = [];

            $url = config('app.url'). "/uploads" . $path;
            return response()->json(['data' => [$mfile]], 200);
        } catch (Exception $e) {
            Log::error("Failed update file . " . $e->getMessage());
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }

    public function storeFile($name, $path, $folderId, $size, $parent = null, $altext = null)
    {
        $mediaFile = new MediaFile();
        $mediaFile->name = $name;
        $mediaFile->path = $path;
        $mediaFile->extension = $this->getRealExtension($name);
        $mediaFile->full_path = "/uploads" . $path;
        $mediaFile->alt_text = $altext;
        $mediaFile->folder_id = $folderId;
        $mediaFile->size = $size;
        $mediaFile->parent_id = $parent;
        $mediaFile->url = config('app.url'). "/uploads" . $path;
        $mediaFile->user_id = Auth::user()->id;
        $mediaFile->save();

        return $mediaFile;
    }

    public function getRealName($string)
    {
        $parts  = explode('.', $string);
        array_pop($parts);
        $string = implode('', $parts);

        return $string;
    }

    public function getRealExtension($string)
    {
        $parts  = explode('.', $string);
        return array_pop($parts);
    }

    public function deleteFile(Request $request, $id)
    {
        $file = MediaFile::findOrFail($id);

        if (Storage::disk('public')->exists($file->path)) {
            Storage::disk('public')->delete($file->path);
        }
        $file->delete();

        return response()->json('success', 200);
    }
}
