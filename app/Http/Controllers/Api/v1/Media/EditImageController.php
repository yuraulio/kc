<?php

namespace App\Http\Controllers\Api\v1\Media;

use App\Http\Controllers\Api\v1\ApiBaseController;
use App\Http\Requests\Api\Media\EditImageRequest;
use App\Http\Resources\MediaFileResource;
use App\Jobs\RenameFile;
use App\Model\Admin\MediaFile;
use App\Model\Admin\MediaFolder;
use App\Services\Media\MediaFileService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

class EditImageController extends ApiBaseController
{
    public function __invoke(MediaFile $file, MediaFileService $service, EditImageRequest $request): JsonResponse|MediaFileResource
    {
        $data = $request->validated();
        $version = $data['version'];

        $versionOptions = $service->getVersionOptions($version);
        if ($version !== 'original' && empty($versionOptions)) {
            return $this->response(['message' => 'Unknown version'], Response::HTTP_BAD_REQUEST);
        }

        if (($file->id === $data['parent_id']) || $version === 'original') {
            $file->fill($data);
            $file->save();
            $service->updateAltTextLink($file, $file->alt_text, $file->link);

            return new MediaFileResource($file);
        }

        $mediaFolder = MediaFolder::find($data['folder_id']);
        $imageName = $service->sanitizeFileName($data['name']);

        $n = strrpos($imageName, '.');
        $fileBaseName = substr($imageName, 0, $n);
        $fileExt = ($version === 'feed-image') ? 'jpg' : 'webp';
        $path = $mediaFolder->path . '/' . $fileBaseName . '.' . $fileExt;

        if (!Storage::disk('public')->exists($file->path)) {
            return $this->response(['message' => 'Original File not found'], Response::HTTP_BAD_REQUEST);
        }

        $mediaFile = MediaFile::where('parent_id', $data['parent_id'])
            ->where('version', $version)
            ->first();

        $imageWebP = null;
        $cropData = is_array($data['crop_data']) ? $data['crop_data'] : null;

        if ($cropData) {
            if ($mediaFile) {
                $service->deleteVersionFile($mediaFile);
            }

            $imgHeight = intval($cropData['height'] * (1 / (float)$data['height_ratio']));
            $imgWidth = intval($cropData['width'] * (1 / (float)$data['width_ratio']));
            $imgTop = empty($cropData['top']) ? 0 : intval($cropData['top'] * (1 / (float)$data['height_ratio']));
            $imgLeft = empty($cropData['left']) ? 0 : intval($cropData['left'] * (1 / (float)$data['width_ratio']));

            try {
                $contents = Storage::disk('public')->get($file->path);

                $imageWebP = $service->resize(Image::make($contents), [
                    'width'      => $versionOptions['w'],
                    'height'     => $versionOptions['h'],
                    'type'       => 'cropR',
                    'x'          => $imgLeft,
                    'y'          => $imgTop,
                    'cropWidth'  => $imgWidth,
                    'cropHeight' => $imgHeight,
                ]);
                $this->storeImage($path, $imageWebP, $fileExt, $versionOptions['q']);
            } catch (\Exception $e) {
                Log::error('FileVersion generating failed . ' . $e->getMessage());

                return $this->error('Original File not readable', $e->getCode());
            }
        }

        $dataToUpdate = [
            'name'      => $imageName,
            'extension' => $fileExt,
            'path'      => $path,
            'full_path' => '/uploads' . $path,
            'url'       => Storage::disk('public')->url($path),
            'folder_id' => $mediaFolder->id,
            'parent_id' => $version == 'original' ? null : $data['parent_id'],
            'alt_text'  => $data['alt_text'],
            'link'      => $data['link'],
            'size'      => Storage::disk('public')->size($path),
            'version'   => $version,
            'user_id'   => Auth::user()->id,
        ];

        if ($imageWebP) {
            $dataToUpdate += [
                'height'    => $imageWebP->height(),
                'width'     => $imageWebP->width(),
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

        return new MediaFileResource($mediaFile);
    }

    protected function storeImage($path, $image, $fileExt, $quality)
    {
        if ($fileExt === 'jpg') {
            $image->encode('jpg', $quality);
        }
        $image->stream();
        Storage::disk('public')->put($path, $image, 'public');
    }
}
