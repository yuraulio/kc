<?php

namespace App\Http\Controllers\Admin_api;

use Exception;
use App\Jobs\MoveFile;
use App\Jobs\RenameFolder;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Model\Admin\MediaFile;
use App\Model\Admin\MediaFolder;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Http\Resources\PageResource;
use Illuminate\Support\Facades\Auth;
use Intervention\Image\ImageManager;
use Illuminate\Support\Facades\Storage;
use App\Http\Resources\MediaFileResource;
use App\Http\Requests\MoveMediaFileRequest;
use App\Http\Resources\MediaFolderResource;
use App\Http\Requests\EditMediaFolderRequest;
use App\Http\Requests\CreateMediaFolderRequest;
use App\Jobs\RenameFile;
use App\Jobs\TinifyImage;
use App\Model\Admin\Page;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\DB;

class MediaController extends Controller
{
    /**
     * Get folders
     *
     * @return AnonymousResourceCollection
     */
    public function index(Request $request)
    {
        // $this->authorize('viewAny', Page::class, Auth::user());

        try {
            $folders = MediaFolder::lookForOriginal($request->filter)
                ->with('children.children.children.children.children.children.children.children')->whereParentId($request->folder_id ?? null)
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
            $parent = MediaFolder::whereId($request->directory)->first();

            $cname = Str::slug($request->name, '_');
            $path = $parent->path . "/$cname";

            $path = str_replace("//", "/", $path);

            Storage::disk('public')->makeDirectory($path);

            $mediaFolder = new MediaFolder();
            $mediaFolder->name = $request->name;
            $mediaFolder->path = $path;
            $mediaFolder->url = config('app.url') . "/uploads" . $path;
            $mediaFolder->user_id = Auth::user()->id;
            $mediaFolder->parent_id = $parent->id;
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
            $folder = $this->getFolder($request);
            $path = '/' . $folder["path"];
            $mediaFolder = $folder["mediaFolder"];

            $image_name = $image->getClientOriginalName();
            $imgpath_original = $path . '' . $image_name;
            $file = Storage::disk('public')->putFileAs($path, $image, $image_name, 'public');
            $mfile_original = $this->storeFile($image_name, "original", $imgpath_original, $mediaFolder->id, $image->getSize(), $request->parrent_id, $request->alt_text, $request->link);

            $versions = Page::VERSIONS;

            foreach ($versions as $version) {
                // set image name
                $tmp = explode('.', $image_name);
                $extension = end($tmp);
                $version_name = pathinfo($image_name, PATHINFO_FILENAME);
                $version_name = $version_name . "-" . $version[0] . "." . $extension;

                // set image path
                $imgpath = $path . '' . $version_name;

                // save image
                $file = Storage::disk('public')->putFileAs($path, $request->file('file'), $version_name, 'public');

                // crop image
                $manager = new ImageManager();
                $image = $manager->make(Storage::disk('public')->get($file));
                $image_height = $image->height();
                $image_width = $image->width();

                $crop_height = $version[2];
                $crop_width = $version[1];

                $height_offset = ($image_height / 2) - ($crop_height / 2);
                $height_offset = $height_offset > 0 ? (int) $height_offset : null;

                $width_offset = ($image_width / 2) - ($crop_width / 2);
                $width_offset = $width_offset > 0 ? (int) $width_offset : null;

                $image->crop($crop_width, $crop_height, $width_offset, $height_offset);
                $image->save(public_path("/uploads" . $path . $version_name));

                // save to db
                $mfile = $this->storeFile($version_name, $version[0], $imgpath, $mediaFolder->id, $image->filesize(), $mfile_original->id, $request->alt_text, $request->link);
                $files[] = new MediaFileResource($mfile);
                
                TinifyImage::dispatch(public_path() . $mfile->full_path, $mfile->id);
            }

            $original = MediaFile::find($mfile_original->id);
            array_unshift($files, new MediaFileResource($original));

            return response()->json(['data' => $files], 200);
        } catch (Exception $e) {
            Log::error("Failed to uoload file . " . $e->getMessage());
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }

    public function editImage(Request $request): JsonResponse
    {
        try {
            $folder = $this->getFolder($request);
            $path = $folder["path"];
            $mediaFolder = $folder["mediaFolder"];

            $image_name = $request->imgname;
            $imgpath = $path . '' . $image_name;
            $size = null;

            $original_file = MediaFile::findOrFail($request->parent_id);
            $file = MediaFile::whereId($request->id)->first();

            if ($file) {
                $file_path = $file->path;
            } else {
                // create new file path
                $tmp = explode('.', $original_file->path);
                $extension = end($tmp);
                $file_path = $tmp[0];
                $file_path = $file_path . "-" . $request->version . "." . $extension;
            }

            if ($request->version != 'original') {
                // delete old file
                if ($file && Storage::disk('public')->exists($file->path)) {
                    Storage::disk('public')->delete($file->path);
                }

                // duplicate original file
                Storage::disk('public')->copy($original_file->path, $file_path);
                // crop image
                $manager = new ImageManager();
                $image = $manager->make(Storage::disk('public')->get($file_path));

                $crop_data = json_decode($request->crop_data);

                $crop_height = $crop_data->height * (1 / $request->height_ratio);
                $crop_width = $crop_data->width * (1 / $request->width_ratio);

                $height_offset = $crop_data->top * (1 / $request->height_ratio);
                $width_offset = $crop_data->left * (1 / $request->width_ratio);

                $image->crop((int) $crop_width, (int) $crop_height, (int) $width_offset, (int) $height_offset);

                $folderPath = ltrim($mediaFolder->path, "/");
                $folderPath = "/" . $folderPath;

                $image->save(public_path("/uploads" . $folderPath . "/" . $image_name));

                $size = $image ? $image->filesize() : null;
            }

            $parent_id = $request->parent_id;
            if ($request->version == 'original') {
                $parent_id = null;
            }

            $mfile = $this->editFile($parent_id, $request->version, $image_name, $imgpath, $mediaFolder->id, $size, null, $request->alttext, $request->link, $request->id);

            if ($request->version != 'original') {
                TinifyImage::dispatch($save_path, $mfile->id);
            }

            return response()->json(['data' => new MediaFileResource($mfile)], 200);
        } catch (Exception $e) {
            throw $e;
            Log::error("Failed update file . " . $e->getMessage());
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }

    public function uploadRegFile(Request $request): JsonResponse
    {
        $image = $request->file('file');

        try {
            $cname = $this->getRealName($request->imgname ? $request->imgname . "." . $image->extension() :  $image->getClientOriginalName());

            $folder = $this->getFolder($request);
            $path = $folder["path"];
            $mediaFolder = $folder["mediaFolder"];

            // Store edited image
            $imgpath = $path . '' . (($request->imgname ? $request->imgname . "_" . getimagesize($image)[0] . "x" . getimagesize($image)[1] . "_" . $request->compression . "." . $image->extension() : $image->getClientOriginalName()));

            $file = Storage::disk('public')->putFileAs($path, $request->file('file'), ($request->imgname ? $request->imgname . "_" . getimagesize($image)[0] . "x" . getimagesize($image)[1] . "." . $image->extension() : $image->getClientOriginalName()), 'public');

            $mfile = $this->storeFile(($request->imgname ? $request->imgname . "_" . getimagesize($image)[0] . "x" . getimagesize($image)[1] . "_" . $request->compression . "." . $image->extension() : $image->getClientOriginalName()), $imgpath, $mediaFolder->id, $image->getSize(), null, null);
            $mfile->pages = [];

            $url = config('app.url') . "/uploads" . $path;
            return response()->json(['data' => [$mfile]], 200);
        } catch (Exception $e) {
            Log::error("Failed update file . " . $e->getMessage());
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }

    public function storeFile($name, $version, $path, $folderId, $size, $parent = null, $alt_text = null, $link = null)
    {
        $path = str_replace("//", "/", $path);

        $mediaFile = new MediaFile();
        $mediaFile->name = $name;
        $mediaFile->path = $path;
        $mediaFile->extension = $this->getRealExtension($name);
        $mediaFile->full_path = "/uploads" . $path;
        $mediaFile->alt_text = $alt_text;
        $mediaFile->link = $link;
        $mediaFile->folder_id = $folderId;
        $mediaFile->size = $size;
        $mediaFile->parent_id = $parent;
        $mediaFile->url = config('app.url') . "/uploads" . $path;
        $mediaFile->user_id = Auth::user()->id;
        $mediaFile->version = $version;
        $mediaFile->save();

        $mediaFile->load(["pages", "siblings", "subfiles"]);

        return $mediaFile;
    }

    public function editFile($parent_id, $version, $name, $path, $folderId, $size, $parent = null, $alttext = "", $link = "", $id)
    {
        DB::beginTransaction();

        try {
            $url = config('app.url') . "/uploads" . $path;

            $mediaFile = MediaFile::whereId($id)->firstOrNew();

            $oldPath = $mediaFile->path;
            $oldUrl = $mediaFile->url;

            $mediaFile->name = $name;
            $mediaFile->path = $path;
            $mediaFile->extension = $this->getRealExtension($name);
            $mediaFile->full_path = "/uploads" . $path;
            $mediaFile->alt_text = $alttext;
            $mediaFile->link = $link;
            $mediaFile->folder_id = $folderId;
            $mediaFile->size = $size ?? $mediaFile->size;
            $mediaFile->url = $url;
            $mediaFile->user_id = Auth::user()->id;
            $mediaFile->version = $version;
            $mediaFile->parent_id = $parent_id;
            $mediaFile->save();

            if (!Storage::disk('public')->exists($mediaFile->path)) {
                Storage::disk('public')->move($oldPath, $mediaFile->path);
            }

            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            Log::error("Failed edit file. " . $e->getMessage());

            return $e->getMessage();
        }

        RenameFile::dispatch($oldUrl, $url);

        $mediaFile->load(["pages", "siblings", "subfiles"]);

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

        if ($file->parent_id == null) {
            $subfiles = MediaFile::where("parent_id", $file->id)->get();
            foreach ($subfiles as $subfile) {
                if (Storage::disk('public')->exists($subfile->path)) {
                    Storage::disk('public')->delete($subfile->path);
                }
                $subfile->pages()->detach();
                $subfile->delete();
            }
        }

        $file->pages()->detach();
        $file->delete();

        return response()->json('success', 200);
    }

    public function deleteFolder($id)
    {
        $folder = MediaFolder::find($id);

        // delete subfolders
        $subfolders = MediaFolder::where("parent_id", $id)->get();
        foreach ($subfolders as $subfolder) {
            $this->deleteFolder($subfolder->id);
        }

        $path = $folder->path;
        $result = Storage::disk('public')->deleteDirectory($path);
        if ($result) {
            $folder->delete();
            MediaFile::where('folder_id', $id)->delete();
            return response()->json('success', 200);
        } else {
            return response()->json('Failed to delete folder.', 400);
        }
    }

    public function editFolder(EditMediaFolderRequest $request)
    {
        DB::beginTransaction();
        try {
            $folder = MediaFolder::find($request->id);

            $newFolderNameSlugify = Str::slug($request->name, '_');
            $oldFolderNameSlugify = Str::slug($folder->name, '_');

            $oldPath = $folder->path;
            $newPath = Str::replaceLast($oldFolderNameSlugify, $newFolderNameSlugify, $oldPath);

            $oldFullPath = public_path("/uploads/" . $oldPath);
            $newFullPath = public_path("/uploads/" . $newPath);

            $result = rename($oldFullPath, $newFullPath);
            if ($result) {
                $folder->name = $request->name;
                $folder->save();

                RenameFolder::dispatch($oldPath, $newPath, $folder->id);

                DB::commit();
                return response()->json('success', 200);
            }

            DB::rollback();
        } catch (Exception $e) {
            throw $e;
            DB::rollback();
            Log::error("Failed to update pages when renaming file. " . $e->getMessage());
            return response()->json('Failed to rename folder.', 400);
        }
    }

    public function moveFile(MoveMediaFileRequest $request)
    {
        $folder = json_decode($request->folder);
        $file = json_decode($request->file);

        MoveFile::dispatch($file->id, $folder->id);

        return response()->json('success', 200);
    }

    private function getFolder(Request $request)
    {
        if ($request->directory) {
            $mediaFolder = MediaFolder::findOrFail($request->directory);
            $path = $mediaFolder->path . "/";
        } else {
            $path = "/pages_media/random";

            if (!Storage::disk('public')->exists($path)) {
                Storage::disk('public')->makeDirectory($path);
            }

            $mediaFolder = MediaFolder::whereName("random")->firstOrCreate();

            $mediaFolder->name = "random";
            $mediaFolder->path = $path;
            $mediaFolder->url = config('app.url') . "/uploads" . $path;
            $mediaFolder->user_id = Auth::user()->id;
            $mediaFolder->save();
            
            $path = $mediaFolder->path . "/";
        }

        return [
            "mediaFolder" => $mediaFolder,
            "path" => $path
        ];
    }
}
