<?php

namespace App\Services\Media;

use App\Jobs\RenameFile;
use App\Model\Admin\MediaFile;
use App\Model\Admin\Page;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

class MediaFileService
{
    public function sanitizeFileName($name)
    {
        $txt = preg_replace('/[^a-zA-Z0-9\-\._]/', '_', $name);
        $txt = preg_replace('/(_)\\1+/', '$1', $txt);

        return $txt;
    }

    public function getVersionOptions($version)
    {
        $versions = get_image_versions('versions');

        return empty($versions[$version]) ? null : $versions[$version];
    }

    public function generateVersions(MediaFile $mediaFile)
    {
        $contents = Storage::disk('public')->get($mediaFile->path);

        $fileName = pathinfo($mediaFile->name, PATHINFO_FILENAME);
        $fileExt = 'webp';

        $basePath = $mediaFile->mediaFolder->path . '/';

//        $versions = Page::VERSIONS;
        $versions = config('image_versions.versions');

        $items = [];
        foreach ($versions as $version => $versionOptions) {
            if ($version === 'feed-image') {
                $fileExt = 'jpg';
            }
            $imageName = $fileName . '-' . $version . '.' . $fileExt;
            $path = $basePath . $imageName;
            try {
                $imageWebP = $this->resize(Image::make($contents), [
                    'width' => $versionOptions['w'],
                    'height' => $versionOptions['h'],
                    'type' => 'fill',
                ]);
                $this->storeImage($path, $imageWebP, $fileExt, $versionOptions['q']);

                $items[] = MediaFile::create([
                    'name' => $imageName,
                    'extension' => $fileExt,
                    'path' => $path,
                    'full_path' => '/uploads' . $path,
                    'url' => Storage::disk('public')->url($path),
                    'folder_id' => $mediaFile->folder_id,
                    'parent_id' => $mediaFile->id,
                    'alt_text' => $mediaFile->alt_text,
                    'link' => $mediaFile->link,
                    'size' => Storage::disk('public')->size($path),
                    'height' => $imageWebP->height(),
                    'width' => $imageWebP->width(),
                    'version' => $version,
                    'user_id' => Auth::user()->id,
                ]);
            } catch (\Exception $e) {
                Log::error('FileVersion generating failed . ' . $e->getMessage());
            }
        }

        return $items;
    }

    public function resize($image, $options)
    {
        if ($options['type'] == 'fill') {
            $image->resize($options['width'], $options['height'], function ($c) {
                $c->aspectRatio();
                $c->upsize();
            });

            return Image::canvas($options['width'], $options['height'])
                ->insert($image, 'center');
        }
        if ($options['type'] == 'cropR') {
            $w = $options['cropWidth'];
            $h = intval($options['cropWidth'] * $options['height'] / $options['width']);
            if ($options['cropHeight'] > $options['cropWidth']) {
                $h = $options['cropHeight'];
                $w = intval($options['cropHeight'] * $options['width'] / $options['height']);
            }

            $image->crop($w, $h, $options['x'], $options['y']);

            return $image->resize($options['width'], $options['height']);
        }

        return $image->fit($options['width'], $options['height'], function ($c) {
            $c->upsize();
        });
    }

    protected function storeImage($path, $image, $fileExt, $quality)
    {
        if ($fileExt === 'jpg') {
            $image->encode('jpg', $quality);
        }
        $image->stream();
        Storage::disk('public')->put($path, $image, 'public');
    }

    /**
     * @param MediaFile $mediaFile
     * @param $alttext
     * @param $link
     * @return void
     */
    public function updateAltTextLink($mediaFile, $alttext, $link)
    {
        // set alttext on all subfiles/siblings
        if ($alttext && $alttext != 'null') {
            $subfiles = $mediaFile->subfiles()->get();
            if ($subfiles) {
                foreach ($subfiles as $subfile) {
                    if ($subfile->alt_text == null || $subfile->alt_text == 'null') {
                        $subfile->alt_text = $alttext;
                        $subfile->save();
                    }
                }
            }
            $siblings = $mediaFile->siblings()->get();
            if ($siblings) {
                foreach ($siblings as $sibling) {
                    if ($sibling->alt_text == null || $sibling->alt_text == 'null') {
                        $sibling->alt_text = $alttext;
                        $sibling->save();
                    }
                }
            }
        }

        // set link on all subfiles/siblings
        if ($link && $link != 'null') {
            $subfiles = $mediaFile->subfiles()->get();
            if ($subfiles) {
                foreach ($subfiles as $subfile) {
                    if ($subfile->link == null || $subfile->link == 'null') {
                        $subfile->link = $link;
                        $subfile->save();
                    }
                }
            }
            $siblings = $mediaFile->siblings()->get();
            if ($siblings) {
                foreach ($siblings as $sibling) {
                    if ($sibling->link == null || $sibling->link == 'null') {
                        $sibling->link = $link;
                        $sibling->save();
                    }
                }
            }
        }
    }

    public function deleteVersionFile(MediaFile $mediaFile)
    {
        if (Storage::disk('public')->exists($mediaFile->path)) {
            Storage::disk('public')->delete($mediaFile->path);
        }
        $n = strrpos($mediaFile->path, '.');
        $ext = substr($mediaFile->path, $n);
        if ($ext !== 'webp') {
            $webpPath = str_replace($ext, 'webp', $mediaFile->path);
            if (Storage::disk('public')->exists($webpPath)) {
                Storage::disk('public')->delete($webpPath);
            }
        }
    }

    public function deleteVersion(MediaFile $mediaFile, $newVersion = null)
    {
        $this->deleteVersionFile($mediaFile);

        if ($newVersion && $mediaFile->url && $mediaFile->parent_id) {
            /** @type MediaFile $newFile */
            $newFile = MediaFile::where('version', $newVersion)
                ->where('parent_id', $mediaFile->parent_id)
                ->first();
            if (!$newFile) {
                $newFile = MediaFile::query()
                    ->where('id', $mediaFile->parent_id)
                    ->first();
            }
            if ($newFile) {
                if ($mediaFile->pages) {
                    foreach ($mediaFile->pages as $page) {
                        $content = $page->content;
                        $content = str_replace($mediaFile->url, $newFile->url, $content);
                        $content = str_replace($mediaFile->path, $newFile->path, $content);
                        $page->content = $content;
                        $page->save();
                        if (!$newFile->pages()->where('cms_pages.id', $page->id)->exists()) {
                            $newFile->pages()->attach($page->id);
                        }
                    }
                }
                RenameFile::dispatch($mediaFile->url, $newFile->url, $newFile->alt_text, $newFile->link)
                    ->delay(now()->addSeconds(5));
            }
        }
        $mediaFile->pages()->detach();
        $mediaFile->delete();
    }
}
