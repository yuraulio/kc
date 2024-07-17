<?php

namespace App\Services\Event;

use App\Model\Dropbox;
use Illuminate\Support\Collection;

class EventFileService
{
    /**
     * @param  \Illuminate\Support\Collection  $dropboxFiles
     * @param  \Illuminate\Support\Collection  $selectedFiles
     *
     * @return \Illuminate\Support\Collection
     */
    public function associateSelectedFilesWithDropboxFiles(Collection $dropboxFiles, Collection $selectedFiles): Collection
    {
        return $dropboxFiles->map(function (Dropbox $item) use ($selectedFiles) {
            $item = $item->toArray();

            foreach ($item['files'] as &$files) {
                $files = array_map(function ($item) use ($selectedFiles) {
                    $item['selected'] = $selectedFiles->contains($item['dirname']);

                    return $item;
                }, $files);
            }

            return $item;
        });
    }
}
