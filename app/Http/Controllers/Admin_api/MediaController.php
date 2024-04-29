<?php

namespace App\Http\Controllers\Admin_api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateMediaFolderRequest;
use App\Http\Requests\EditMediaFolderRequest;
use App\Http\Requests\MoveMediaFileRequest;
use App\Http\Resources\MediaFileResource;
use App\Http\Resources\MediaFolderResource;
use App\Http\Resources\PageResource;
use App\Jobs\DeleteMediaFiles;
use App\Jobs\MoveFile;
use App\Jobs\RenameFolder;
use App\Model\Admin\MediaFile;
use App\Model\Admin\MediaFolder;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class MediaController extends Controller
{
    /**
     * Get folders.
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
            Log::error('Failed to get folders. ' . $e->getMessage());

            return response()->json(['message' => $e->getMessage()], 400);
        }
    }

    public function files(Request $request)
    {
        try {
            $query = MediaFile::lookForOriginal($request->filter)
                ->when($request->parent != false && $request->parent != 'false', function ($q) {
                    return $q->whereNull('parent_id');
                })
                ->with('user')
                ->withCount('pages')
                ->when($request->folder_id != null, function ($q) use ($request) {
                    return $q->where('folder_id', $request->folder_id);
                });

            // Filter files by the date of creation.
            if ($request->query->has('created_at')) {
                $createdAtFilter = $request->query->all('created_at');

                if (isset($createdAtFilter['from'])) {
                    $query->whereDate('created_at', '>=', $createdAtFilter['from']);
                }

                if (isset($createdAtFilter['to'])) {
                    $query->whereDate('created_at', '<=', $createdAtFilter['to']);
                }
            }

            $files = $query
                ->orderBy('created_at', 'desc')
                ->paginate((int) $request->query->get('per_page', 50))
                ->appends($request->query->all());

            if ($request->parent != false && $request->parent != 'false') {
                $files->load('subfiles');
            }

            return MediaFileResource::collection($files);
        } catch (Exception $e) {
            Log::error('Failed to get files. ' . $e->getMessage());

            return response()->json(['message' => $e->getMessage()], 400);
        }
    }

    public function getFile(Request $request, $id)
    {
        try {
            $file = MediaFile::whereId($id)->with(['user', 'subfiles', 'parrent'])->first();

            return new MediaFileResource($file);
        } catch (Exception $e) {
            Log::error('Failed to get file. ' . $e->getMessage());

            return response()->json(['message' => $e->getMessage()], 400);
        }
    }

    /**
     * Add mediaFolder.
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

            $path = str_replace('//', '/', $path);

            Storage::disk('public')->makeDirectory($path);

            $max = MediaFolder::max('order');

            $mediaFolder = new MediaFolder();
            $mediaFolder->name = $request->name;
            $mediaFolder->path = $path;
            $mediaFolder->url = config('app.url') . '/uploads' . $path;
            $mediaFolder->user_id = Auth::user()->id;
            $mediaFolder->parent_id = $parent->id;
            $mediaFolder->order = $max + 1;
            $mediaFolder->save();

            return new MediaFolderResource($mediaFolder);
        } catch (Exception $e) {
            Log::error('Failed to add new mediaFolder. ' . $e->getMessage());

            return response()->json(['message' => $e->getMessage()], 400);
        }
    }

    public function deleteFile($id)
    {
        $file = MediaFile::findOrFail($id);

        if (Storage::disk('public')->exists($file->path)) {
            Storage::disk('public')->delete($file->path);
        }

        // Delete webp version image
        $webp_version = str_replace($file['extension'], 'webp', $file->path);

        if (Storage::disk('public')->exists($webp_version)) {
            Storage::disk('public')->delete($webp_version);
        }

        if ($file->parent_id == null) {
            $subfiles = MediaFile::where('parent_id', $file->id)->get();
            foreach ($subfiles as $subfile) {
                if (Storage::disk('public')->exists($subfile->path)) {
                    Storage::disk('public')->delete($subfile->path);
                }

                // Delete webp version subfile image
                $webp_version = str_replace($subfile['extension'], 'webp', $subfile->path);

                if (Storage::disk('public')->exists($webp_version)) {
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
        $subfolders = MediaFolder::where('parent_id', $id)->get();
        foreach ($subfolders as $subfolder) {
            $this->deleteFolder($subfolder->id);
        }

        $path = $folder->path;
        $result = Storage::disk('public')->deleteDirectory($path);
        if (!$result) {
            Log::error('Failed to delete folder form disk.');
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
            Log::error('Failed to update pages when renaming folder (controller). ' . $e->getMessage());

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

    /**
     * @throws \Throwable
     */
    public function download(MediaFile $mediaFile): BinaryFileResponse
    {
        $file = public_path(implode('/', ['uploads', $mediaFile->path]));

        throw_if(!file_exists($file), new NotFoundHttpException(sprintf('File "%s" does not exist.', $file)));

        return response()->download($file);
    }
}
