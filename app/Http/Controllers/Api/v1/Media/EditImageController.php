<?php

namespace App\Http\Controllers\Api\v1\Media;

use App\Http\Controllers\Api\v1\ApiBaseController;
use App\Http\Requests\Api\Media\EditImageRequest;
use App\Http\Resources\MediaFileResource;
use App\Jobs\RenameFile;
use App\Model\Admin\MediaFile;
use App\Model\Admin\MediaFolder;
use App\Services\Media\MediaFileService;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

class EditImageController extends ApiBaseController
{
    public function __invoke(MediaFileService $service, EditImageRequest $request)
    {
        $version = $request->input('version');
        $versionOptions = $service->getVersionOptions($version);
        if ($version !== 'original' && empty($versionOptions)) {
            return $this->response(['message' => 'Unknown version'], Response::HTTP_BAD_REQUEST);
        }

        $originalFile = MediaFile::findOrFail($request->input('parent_id'));
        if ($originalFile->id === $request->input('id') || $version === 'original') {
            $originalFile->update($request->only(['name', 'alt_text', 'link']));
            $service->updateAltTextLink($originalFile, $originalFile->alt_text, $originalFile->link);

            return $this->responseWithData(new MediaFileResource($originalFile));
        }

        $mediaFolder = MediaFolder::findOrFail($request->input('folder_id'));
        $imageName = $service->sanitizeFileName($request->input('name'));

        $n = strrpos($imageName, '.');
        $fileBaseName = substr($imageName, 0, $n);
        $fileExt = ($version === 'feed-image') ? 'jpg' : 'webp';
        $path = $mediaFolder->path . '/' . $fileBaseName . '.' . $fileExt;

        if (!Storage::disk('public')->exists($originalFile->path)) {
            return $this->response(['message' => 'Original File not found'], Response::HTTP_BAD_REQUEST);
        }

        $mediaFile = MediaFile::where('parent_id', $originalFile->id)
            ->where('version', $version)
            ->first();

        if ($request->filled('crop_data') && $mediaFile) {
            $service->deleteVersionFile($mediaFile);
        }

        $imageWebP = null;
        $cropData = $request->input('crop_data');

        if ($cropData) {
            $heightRatio = $request->input('height_ratio');
            $widthRatio = $request->input('width_ratio');
            if (!$heightRatio || !$widthRatio || $heightRatio <= 0 || $widthRatio <= 0) {
                return $this->response(['message' => 'Bad Request'], Response::HTTP_BAD_REQUEST);
            }

            if (empty($cropData['height']) || $cropData['height'] < 1 || empty($cropData['width']) || $cropData['width'] < 1) {
                return $this->response(['message' => 'Bad Request'], Response::HTTP_BAD_REQUEST);
            }

            $imgHeight = intval($cropData['height'] * (1 / (float) $heightRatio));
            $imgWidth = intval($cropData['width'] * (1 / (float) $widthRatio));
            $imgTop = empty($cropData['top']) ? 0 : intval($cropData['top'] * (1 / (float) $heightRatio));
            $imgLeft = empty($cropData['left']) ? 0 : intval($cropData['left'] * (1 / (float) $widthRatio));

            try {
                $contents = Storage::disk('public')->get($originalFile->path);
                try {
                    $imageWebP = $service->resize(Image::make($contents), [
                        'width' => $versionOptions['w'],
                        'height' => $versionOptions['h'],
                        'type' => 'cropR',
                        'x' => $imgLeft,
                        'y' => $imgTop,
                        'cropWidth' => $imgWidth,
                        'cropHeight' => $imgHeight,
                    ]);
                    $this->storeImage($path, $imageWebP, $fileExt);
                } catch (\Exception $e) {
                    Log::error('FileVersion generating failed . ' . $e->getMessage());

                    return $this->responseException('FileVersion generating failed', $e);
                }
            } catch (\Exception $e) {
                Log::error('FileVersion generating failed . ' . $e->getMessage());

                return $this->responseException('Original File not readable', $e);
            }
        }

        $dataToUpdate = [
            'name' => $imageName,
            'extension' => $fileExt,
            'path' => $path,
            'full_path' => '/uploads' . $path,
            'url' => Storage::disk('public')->url($path),
            'folder_id' => $mediaFolder->id,
            'parent_id' => $version == 'original' ? null : $request->input('parent_id'),
            'alt_text' => $request->input('alt_text'),
            'link' => $request->input('link'),
            'size' => Storage::disk('public')->size($path),
            'version' => $version,
            'user_id' => Auth::user()->id,
        ];

        if ($imageWebP) {
            $dataToUpdate += [
                'height' => $imageWebP->height(),
                'width' => $imageWebP->width(),
                'crop_data' => $cropData,
            ];
        }


        if (!$mediaFile) {
            $mediaFile = new MediaFile();
        }

        $mediaFile->fill($dataToUpdate)->save();
        $service->updateAltTextLink($mediaFile, $mediaFile->alt_text, $mediaFile->link);

        if ($mediaFile->url) {
            RenameFile::dispatch($mediaFile->url, $mediaFile->url, $mediaFile->alt_text, $mediaFile->link)
                ->delay(now()->addSeconds(5));
        }

        $mediaFile->load(['pages', 'siblings', 'subfiles']);

        return $this->responseWithData(new MediaFileResource($mediaFile));
    }

    protected function responseException($message, $e)
    {
        $data = [
            'message' => $message,
        ];
        if (config('app.debug')) {
            $data['error'] = $e->getMessage();
        }

        return $this->response($data, Response::HTTP_BAD_REQUEST);
    }

    protected function storeImage($path, $image, $fileExt)
    {
        if ($fileExt === 'jpg') {
            $image->encode('jpg', 60);
        }
        $image->stream();
        Storage::disk('public')->put($path, $image, 'public');
    }
}
