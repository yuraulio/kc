<?php

namespace App\Services\Event;

use App\Model\Dropbox;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class EventFileService
{
    /**
     * Builds the files tree for use on the front end.
     *
     * @return \Illuminate\Support\Collection
     *   The files tree.
     */
    public function buildFileTree(): Collection
    {
        $filesTree = new Collection();

        foreach (Dropbox::cursor() as $dropbox) {
            // Combine folders with the bonus folders.
            $folders = Arr::collapse($dropbox->folders ?? []);
            // Combine files with the bonus files.
            $files = Arr::collapse($dropbox->files ?? []);

            // Put files into folders.
            foreach ($folders as &$folder) {
                $folder['children'] = array_values(
                    Arr::where($files, fn (array $file): bool => pathinfo($file['dirname'], PATHINFO_DIRNAME) === $folder['dirname'])
                );
            }

            unset($folder);

            // As the bonus folders have the parent ID it means that we can use
            // it to identify bonus folders and get all of them.
            $bonusFolders = array_values(
                Arr::where($folders, fn (array $folder): bool => isset($folder['parent']))
            );
            // Remove all bonus folders from the main array with the folders.
            $folders = array_values(
                Arr::where($folders, fn (array $folder): bool => !isset($folder['parent']))
            );

            // Put the bonus folders in their place in the main array of folders.
            foreach ($bonusFolders as $bonusFolder) {
                $key = array_search($bonusFolder['parent'], array_column($folders, 'id'));

                if (isset($folders[$key])) {
                    $folders[$key]['children'][] = $bonusFolder;
                }
            }

            // Put the root folder with its children.
            $filesTree->push([
                'foldername' => $dropbox->folder_name,
                'dirname' => $dropbox->folder_name,
                'children' => $folders,
            ]);
        }

        return $filesTree;
    }

    /**
     * Adds the selected property to files.
     *
     * Also, it sets the selected property to true when the file has been found
     * in the $selectedFiles list.
     *
     * @param  \Illuminate\Support\Collection|array  $files
     *   The list of the files.
     * @param  \Illuminate\Support\Collection  $selectedFiles
     *   The list of the selected files.
     *
     * @return \Illuminate\Support\Collection|array
     *   Modified array of the files.
     */
    public function markSelectedFiles(Collection|array $files, Collection $selectedFiles): Collection|array
    {
        foreach ($files as $index => $file) {
            if (isset($file['children'])) {
                $file['children'] = $this->markSelectedFiles($file['children'], $selectedFiles);
            } else {
                $file['selected'] = $selectedFiles->contains($file['dirname']);
            }

            $files[$index] = $file;
        }

        return $files;
    }

    /**
     * Adds the UUID to each element of the array.
     *
     * @param  \Illuminate\Support\Collection|array  $files
     *   The array of the folders with the files.
     *
     * @return \Illuminate\Support\Collection|array
     *   Modified array.
     */
    public function addUuidToEachElement(Collection|array $files): Collection|array
    {
        foreach ($files as $index => $file) {
            $file = ['uuid' => Str::uuid()] + $file;

            if (isset($file['children'])) {
                $file['children'] = $this->addUuidToEachElement($file['children']);
            }

            $files[$index] = $file;
        }

        return $files;
    }
}
