<?php

namespace App\Http\Controllers\Api\v1\Media;

use App\Http\Controllers\Api\v1\ApiBaseController;
use App\Http\Requests\Api\Media\UploadImageRequest;
use App\Http\Resources\MediaFileResource;
use App\Model\Admin\MediaFile;
use App\Model\Admin\MediaFolder;
use App\Services\Media\MediaFileService;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

class UploadImageController extends ApiBaseController
{
    public function __invoke(MediaFileService $service, UploadImageRequest $request)
    {
        $image = $request->file('file');

        $mediaFolder = MediaFolder::findOrFail($request->input('folder_id'));
        $imageOriginalName = $image->getClientOriginalName();
        $imageName = $service->sanitizeFileName($imageOriginalName);

        $path = $mediaFolder->path . '/' . $imageName;
        $n = strrpos($imageName, '.');
        $fileBaseName = substr($imageName, 0, $n);

        if ($image->getMimeType() === 'image/svg+xml') {
            $fileExt = 'svg';
        } else {
            $fileExt = 'webp';
        }

        $imageName = $fileBaseName . '.' . $fileExt;
        $i = 1;
        while (Storage::disk('public')->exists($path) && $i < 10) {
            $imageName = $fileBaseName . '_' . $i++ . '.' . $fileExt;
            $path = $mediaFolder->path . '/' . $imageName;
        }
        if (Storage::disk('public')->exists($path)) {
            return response()->json(['message' => 'File already exists at this location.'], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        try {
            $width = 0;
            $height = 0;
            if ($image->getMimeType() === 'image/svg+xml') {
                preg_match("#viewbox=[\"']\d* \d* (\d*) (\d*)#i", $image->getContent(), $d);
                $width = $d[1];
                $height = $d[2];
                Storage::disk('public')->put($path, $image->getContent(), 'public');
            } else {
                $imageWebP = Image::make($image)->encode('webp', config('app.WEBP_IMAGE_QUALITY'));
                $imageWebP->stream();
                Storage::disk('public')->put($path, $imageWebP, 'public');

                $width = $imageWebP->width();
                $height = $imageWebP->height();
            }
        } catch (\Exception $e) {
            Log::error('File uploading failed . ' . $e->getMessage());

            return $this->response(['message' => 'File uploading failed'], Response::HTTP_BAD_REQUEST);
        }

        $mediaFile = MediaFile::create([
            'name' => $imageName,
            'admin_label' => $request->input('admin_label'),
            'extension' => $fileExt,
            'path' => $path,
            'full_path' => '/uploads' . $path,
            'url' => Storage::disk('public')->url($path),
            'folder_id' => $mediaFolder->id,
            'parent_id' => $request->input('parent_id'),
            'alt_text' => $request->input('alt_text'),
            'link' => $request->input('link'),
            'size' => Storage::disk('public')->size($path),
            'height' => $height,
            'width' => $width,
            'version' => 'original',
            'user_id' => Auth::user()->id,
        ]);

        $data = $service->generateVersions($mediaFile);

        $mediaFile->load(['pages', 'siblings', 'subfiles']);

        array_unshift($data, $mediaFile);

        return $this->responseWithData(MediaFileResource::collection($data));
    }
}
