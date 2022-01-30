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
                                ->with('user')
                                ->when($request->folder_id != null, function ($q) use ($request) {
                                    return $q->where('folder_id', $request->folder_id);
                                })
                                ->orderBy('created_at', 'desc')->paginate(20);

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
            $mediaFolder->url = config('app.url'). "/uploads" . $path;;
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
            $cname = $this->getRealName($request->imgname ? $request->imgname .".". $image->extension() :  $image->getClientOriginalName());
            if ($request->directory) {
                $mediaFolder = MediaFolder::findOrFail($request->directory);
                $path = $mediaFolder->path;
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
            $imgpath = $path . '/'. ($request->imgname ? $request->imgname .".". $image->extension() :  $image->getClientOriginalName());

            $file = Storage::disk('public')->putFileAs($path, $request->file('file'), $request->imgname ? $request->imgname .".". $image->extension() :  $image->getClientOriginalName(), 'public');

            $mfile = $this->storeFile(($request->imgname ? $request->imgname .".". $image->extension() : $image->getClientOriginalName()), $imgpath, $mediaFolder->id, $image->getSize(), true);

            /* $img = Image::make($request->file('file'));
            $img->resize(3840, 2160, function ($const) {
                $const->aspectRatio();
            })->save(public_path().'/uploads'.$path.$this->getRealName($image->getClientOriginalName()) . '_3840x2160.'. $image->extension());

            $this->storeFile($this->getRealName($image->getClientOriginalName()) . '_3840x2160.'. $image->extension(), $path.$this->getRealName($image->getClientOriginalName()) . '_3840x2160.'. $image->extension(), $mediaFolder->id, strlen((string) $img->encode('png')), false); */

            $url = config('app.url'). "/uploads/" . $path;
            return response()->json(['data' => $mfile], 200);
        } catch (Exception $e) {
            Log::error("Failed update file . " . $e->getMessage());
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }

    public function storeFile($name, $path, $folderId, $size, $parent = false)
    {
        $mediaFile = new MediaFile();
        $mediaFile->name = $name;
        $mediaFile->path = $path;
        $mediaFile->folder_id = $folderId;
        $mediaFile->size = $size;
        $mediaFile->parent_id = $parent;
        $mediaFile->url = config('app.url'). "/uploads/" . $path;
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
}
