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
use App\Jobs\DeleteMediaFiles;
use App\Jobs\RenameFile;
use App\Jobs\TinifyImage;
use App\Model\Admin\Page;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\DB;
use App\Jobs\UploadImageConvertWebp;

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
                ->whereParentId($request->folder_id ?? null)
                ->with('childrenAll')
                ->get();

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

    public function getFile(Request $request, $id)
    {
        try {
            $file = MediaFile::whereId($id)->with(['user', 'subfiles', 'parrent'])->first();
            return new MediaFileResource($file);
        } catch (Exception $e) {
            Log::error("Failed to get file. " . $e->getMessage());
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

            $max = MediaFolder::max("order");

            $mediaFolder = new MediaFolder();
            $mediaFolder->name = $request->name;
            $mediaFolder->path = $path;
            $mediaFolder->url = config('app.url') . "/uploads" . $path;
            $mediaFolder->user_id = Auth::user()->id;
            $mediaFolder->parent_id = $parent->id;
            $mediaFolder->order = $max + 1;
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
            $image_name1 = $image->getClientOriginalName();
            $imgpath_original = $path . '' . $image_name;

            if (Storage::disk('public')->exists($imgpath_original)) {
                return response()->json(['message' => "File already exists at this location."], 422);
            }

            // if is non image file (or non supported image file)
            $tmp = explode('.', $image_name);
            $extension = end($tmp);
            if (!in_array($extension, ["jpg", "jpeg", "png", 'JPG'])) {
                return $this->uploadRegFile($request);
            }

            $file = Storage::disk('public')->putFileAs($path, $image, $image_name, 'public');

            // convert uploaded image to webp format
            dispatch((new UploadImageConvertWebp($path, $image_name1))->delay(now()->addSeconds(5)));

            $data = getimagesize($request->file);
            $original_image_width = $data[0];
            $original_image_height = $data[1];

            $mfile_original = $this->storeFile(
                $image_name,
                "original",
                $imgpath_original,
                $mediaFolder->id,
                $image->getSize(),
                $request->parrent_id,
                $request->alt_text,
                $request->link,
                $original_image_height,
                $original_image_width
            );

            $versions = Page::VERSIONS;

            foreach ($versions as $version) {
                $crop_height = $version[2];
                $crop_width = $version[1];

                // if ($original_image_height < $crop_height || $original_image_width < $crop_width) {
                //     continue;
                // }

                // set image name
                $tmp = explode('.', $image_name);
                $extension = end($tmp);
                if ($request->jpg == "true") {
                    $extension = "jpg";
                }
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

                $image->resize($crop_width, $crop_height);
                $image->fit($crop_width, $crop_height);

                //$image->crop($crop_width, $crop_height, $width_offset, $height_offset);
                $image->save(public_path("/uploads" . $path . $version_name), 80, $extension);

                // Convert version image to webp format
                dispatch((new UploadImageConvertWebp($path, $version_name))->delay(now()->addSeconds(5)));

                // save to db
                $mfile = $this->storeFile(
                    $version_name,
                    $version[0],
                    $imgpath,
                    $mediaFolder->id,
                    $image->filesize(),
                    $mfile_original->id,
                    $request->alt_text,
                    $request->link,
                    $image_height,
                    $image_width
                );
                $files[] = new MediaFileResource($mfile);

                //TinifyImage::dispatch(public_path() . $mfile->full_path, $mfile->id)->delay(now()->addSeconds(5));
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
            if ($request->parent_id == $request->id) {
                $image = $this->editOriginalImage($request->id, $request->imgname, $request->alttext, $request->link);
                return response()->json(['data' => new MediaFileResource($image)], 200);
            }
            $height = null;
            $width = null;
            $crop_data = null;

            $folder = $this->getFolder($request);
            $path = $folder["path"];
            $mediaFolder = $folder["mediaFolder"];

            $image_name = $request->imgname;

            $original_file = MediaFile::findOrFail($request->parent_id);
            $file = MediaFile::where("parent_id", $request->parent_id)->where("version", $request->version)->first();

            $tmp = explode('.', $original_file->name);
            $extension = end($tmp);
            if ($request->jpg == "true") {
                $image_name = str_replace_last($extension, "jpg", $image_name);
                $extension = "jpg";
            } else {
                $tmp = explode('.', $image_name);
                $curentExtension = end($tmp);
                $image_name = str_replace_last($curentExtension, $extension, $image_name);
            }

            $imgpath = $path . '' . $image_name;
            $size = null;

            if ($file) {
                $file_path = $file->path;
                $file_path = str_replace($file->name, $image_name, $file_path);
            } else {
                // create new file path
                $file_path = $original_file->path;
                $file_path = str_replace($original_file->name, $image_name, $file_path);
            }

            $cropData = null;

            // if crop data undefined only alttext and link set
            if ($request->version != 'original' && $request->crop_data != 'undefined') {
                // delete old file

                if ($file && Storage::disk('public')->exists($file->path)) {
                    Storage::disk('public')->delete($file->path);

                    // Delete webp version image
                    $webp_version = str_replace($extension,'webp',$file->path);
                    if(Storage::disk('public')->exists($webp_version)){
                        Storage::disk('public')->delete($webp_version);
                    }
                }

                // duplicate original file
                Storage::disk('public')->copy($original_file->path, $file_path);
                // crop image
                $manager = new ImageManager();
                $image = $manager->make(Storage::disk('public')->get($file_path));

                $crop_data = json_decode($request->crop_data);

                $crop_height = $crop_data->height * (1 / (float)$request->height_ratio);
                $crop_width = $crop_data->width * (1 / (float)$request->width_ratio);

                $height_offset = $crop_data->top * (1 / (float)$request->height_ratio);
                $width_offset = $crop_data->left * (1 / (float)$request->width_ratio);

                $cropData = [
                    "crop_height" => $crop_height,
                    "crop_width" => $crop_width,
                    "height_offset" => $height_offset,
                    "width_offset" => $width_offset,

                ];
                $cropData = json_encode($cropData);

                $image->crop((int) $crop_width, (int) $crop_height, (int) $width_offset, (int) $height_offset);

                $folderPath = ltrim($mediaFolder->path, "/");
                $folderPath = "/" . $folderPath;

                $image->save(public_path("/uploads" . $folderPath . "/" . $image_name), 50, $extension);

                // Convert webp image format
                dispatch((new UploadImageConvertWebp($folderPath , '/'.$image_name))->delay(now()->addSeconds(5)));

                $size = $image ? $image->filesize() : null;
                $height = $image ? $image->height() : null;
                $width = $image ? $image->width() : null;
            }

            $parent_id = $request->parent_id;
            if ($request->version == 'original') {
                $parent_id = null;
            }

            $mfile = $this->editFile(
                $parent_id,
                $request->version,
                $image_name,
                $imgpath,
                $mediaFolder->id,
                $size,
                null,
                $request->alttext,
                $request->link,
                $request->id,
                $height,
                $width,
                $cropData
            );


            // if ($request->version != 'original') {
            //     TinifyImage::dispatch(public_path() . $mfile->full_path, $mfile->id)->delay(now()->addSeconds(5));
            // }

            return response()->json(['data' => new MediaFileResource($mfile)], 200);
        } catch (Exception $e) {
            Log::error("Failed update file . " . $e->getMessage());
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }

    public function uploadRegFile(Request $request): JsonResponse
    {
        $image = $request->file('file');

        try {
            $cname = $this->getRealName($request->imgname ? $request->imgname . "." . $image->extension() : $image->getClientOriginalName());

            $folder = $this->getFolder($request);
            $path = $folder["path"];
            $mediaFolder = $folder["mediaFolder"];

            // Store edited image
            $imgpath = $path . '' . (($request->imgname ? $request->imgname . "_" . getimagesize($image)[0] . "x" . getimagesize($image)[1] . "_" . $request->compression . "." . $image->extension() : $image->getClientOriginalName()));

            $file = Storage::disk('public')->putFileAs($path, $request->file('file'), ($request->imgname ? $request->imgname . "_" . getimagesize($image)[0] . "x" . getimagesize($image)[1] . "." . $image->extension() : $image->getClientOriginalName()), 'public');

            $mfile = $this->storeFile(
                $image->getClientOriginalName(),
                "Original",
                $imgpath,
                $mediaFolder->id,
                $image->getSize(),
                null,
                null,
                null,
                null,
                null
            );
            $mfile->pages = [];

            $url = config('app.url') . "/uploads" . $path;
            return response()->json(['data' => [$mfile]], 200);
        } catch (Exception $e) {
            Log::error("Failed update file . " . $e->getMessage());
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }

    public function storeFile(
        $name,
        $version,
        $path,
        $folderId,
        $size,
        $parent = null,
        $alt_text = null,
        $link = null,
        $height,
        $width
    ) {
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
        $mediaFile->url = env('MIX_APP_URL') . "/uploads" . $path;
        $mediaFile->user_id = Auth::user()->id;
        $mediaFile->version = $version;
        $mediaFile->height = $height;
        $mediaFile->width = $width;
        $mediaFile->save();

        $mediaFile->load(["pages", "siblings", "subfiles"]);

        return $mediaFile;
    }

    public function editFile(
        $parent_id,
        $version,
        $name,
        $path,
        $folderId,
        $size,
        $parent = null,
        $alttext = "",
        $link = "",
        $id,
        $height,
        $width,
        $cropData
    ) {
        DB::beginTransaction();

        try {
            $url = env('MIX_APP_URL') . "/uploads" . $path;

            $mediaFile = MediaFile::whereParentId($parent_id)->whereVersion($version)->firstOrNew();

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


            if($height != null){
                $mediaFile->height = $height;
            }

            if($width != null){
                $mediaFile->width = $width;
            }

            if($cropData != null){
                $mediaFile->crop_data = $cropData;
            }

            $mediaFile->save();

            if (!Storage::disk('public')->exists($mediaFile->path)) {
                Storage::disk('public')->move($oldPath, $mediaFile->path);
            }

            $this->updateAltTextLink($mediaFile, $alttext, $link);

            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            Log::error("Failed edit file. " . $e->getMessage());

            return $e->getMessage();
        }

        RenameFile::dispatch($oldUrl, $url, $alttext, $link)->delay(now()->addSeconds(5));

        $mediaFile->load(["pages", "siblings", "subfiles"]);

        return $mediaFile;
    }

    /**
     * updates link and alt text on subfiles or siblings if empty
     *
     * @param   MediaFile  $mediaFile  [$mediaFile description]
     * @param   string  $alttext    [$alttext description]
     * @param   string  $link       [$link description]
     *
     * @return  void
     */
    private function updateAltTextLink($mediaFile, $alttext, $link)
    {
        // set alttext on all subfiles/siblings
        if ($alttext && $alttext != "null") {
            $subfiles = $mediaFile->subfiles()->get();
            if ($subfiles) {
                foreach ($subfiles as $subfile) {
                    if ($subfile->alt_text == null || $subfile->alt_text == "null") {
                        $subfile->alt_text = $alttext;
                        $subfile->save();
                    }
                }
            }
            $siblings = $mediaFile->siblings()->get();
            if ($siblings) {
                foreach ($siblings as $sibling) {
                    if ($sibling->alt_text == null || $sibling->alt_text == "null") {
                        $sibling->alt_text = $alttext;
                        $sibling->save();
                    }
                }
            }
        }

        // set link on all subfiles/siblings
        if ($link && $link != "null") {
            $subfiles = $mediaFile->subfiles()->get();
            if ($subfiles) {
                foreach ($subfiles as $subfile) {
                    if ($subfile->link == null || $subfile->link == "null") {
                        $subfile->link = $link;
                        $subfile->save();
                    }
                }
            }
            $siblings = $mediaFile->siblings()->get();
            if ($siblings) {
                foreach ($siblings as $sibling) {
                    if ($sibling->link == null || $sibling->link == "null") {
                        $sibling->link = $link;
                        $sibling->save();
                    }
                }
            }
        }
    }

    private function editOriginalImage($id, $name, $alttext, $link)
    {
        $mediaFile = MediaFile::find($id);

        $mediaFile->name = $name;
        $mediaFile->alt_text = $alttext;
        $mediaFile->link = $link;
        $mediaFile->save();

        $this->updateAltTextLink($mediaFile, $alttext, $link);

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
        return strtolower(array_pop($parts));
    }

    public function deleteFile($id)
    {
        $file = MediaFile::findOrFail($id);

        if (Storage::disk('public')->exists($file->path)) {
            Storage::disk('public')->delete($file->path);
        }

        // Delete webp version image
        $webp_version = str_replace($file['extension'],'webp',$file->path);

        if(Storage::disk('public')->exists($webp_version)){
            Storage::disk('public')->delete($webp_version);
        }

        if ($file->parent_id == null) {
            $subfiles = MediaFile::where("parent_id", $file->id)->get();
            foreach ($subfiles as $subfile) {
                if (Storage::disk('public')->exists($subfile->path)) {
                    Storage::disk('public')->delete($subfile->path);
                }

                // Delete webp version subfile image
                $webp_version = str_replace($subfile['extension'],'webp',$subfile->path);

                if(Storage::disk('public')->exists($webp_version)){
                    Storage::disk('public')->delete($webp_version);
                }

                $subfile->pages()->detach();
                $subfile->delete();
            }
        }

        $file->pages()->detach();
        $file->delete();

        return response()->json('success', 200);
    }

    public function deleteFiles(Request $request)
    {
        $selected = json_decode($request->selected);
        DeleteMediaFiles::dispatch($selected);
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
        if (!$result) {
            Log::error("Failed to delete folder form disk.");
        }

        $folder->delete();
        $files = MediaFile::where('folder_id', $id)->get();
        foreach ($files as $file) {
            $file->pages()->detach();
            $file->delete();
        }

        return response()->json('success', 200);
    }

    public function editFolder(EditMediaFolderRequest $request)
    {
        try {
            $folder = MediaFolder::find($request->id);

            $newFolderNameSlugify = Str::slug($request->name, '_');
            $oldFolderNameSlugify = Str::slug($folder->name, '_');

            $oldPath = $folder->path;
            $newPath = Str::replaceLast($oldFolderNameSlugify, $newFolderNameSlugify, $oldPath);

            RenameFolder::dispatch($oldPath, $newPath, $folder->id, true, $request->name);

            return response()->json('success', 200);
        } catch (Exception $e) {
            Log::error("Failed to update pages when renaming folder (controller). " . $e->getMessage());
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

    public function moveFiles(MoveMediaFileRequest $request)
    {
        $folder = json_decode($request->folder);
        $files = json_decode($request->file);

        foreach ($files as $file) {
            MoveFile::dispatch($file, $folder->id);
        }

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

    public function changeFolderOrder(Request $request)
    {
        $folderMain = MediaFolder::find($request->id);

        $i = 0;
        $children = $folderMain->parent()->first()->children()->get();
        if ($children) {
            foreach ($children as $child) {
                if ($child->id != $request->id) {
                    $child->order = $i + 0.1;
                    $child->save();
                    $i++;
                }
            }
        }

        // set order for moved folder
        $position = $request->position;

        $folderMain->order = $position;
        $folderMain->save();

        return response()->json('success', 200);
    }
}
