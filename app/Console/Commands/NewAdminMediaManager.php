<?php

namespace App\Console\Commands;

use App\Model\Admin\MediaFile;
use App\Model\Admin\MediaFolder;
use Illuminate\Console\Command;
use App\Model\Topic;
use App\Model\Event;
use App\Model\Category;

class NewAdminMediaManager extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'populate:manager';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Populate database file manager';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info("\nGetting directories in Upload disk\n");

        $directories = \Storage::disk('public')->directories('/');
        $this->storeDirectories($directories);

        return;
        dd($directories);
    $files = \File::files(public_path('/uploads'). '/pages_media');
foreach ($files as $key => $file) {
    dd(basename($file), $file->getPath(), $file->getRealPath(), $file);
}
        dd($files);

    }

    public function storeDirectories($directories)
    {
        $bar = $this->output->createProgressBar(count($directories));

        foreach ($directories ?? [] as $directory) {
            $bar->start();

            if (!MediaFolder::wherePath($directory)->exists()) {
                $prepath = $this->getRealName($directory, false);
                $parent = MediaFolder::wherePath($prepath)->first();

                $mediaFolder = new MediaFolder();
                $mediaFolder->name = $this->getRealName($directory);
                $mediaFolder->path = $directory;
                $mediaFolder->url = config('app.url'). "/uploads" . $directory;
                $mediaFolder->parent_id = $parent ? $parent->id : null;
                $mediaFolder->save();

                $bar->advance();
            } else {
                $mediaFolder = MediaFolder::wherePath($directory)->first();
            }

            $bar->finish();

            $this->syncFilesInDirectory($mediaFolder);

            $this->info("\nGetting directories in $directory\n");
            $this->storeDirectories(\Storage::disk('public')->directories("/$directory"));
        }
    }

    public function syncFilesInDirectory($mediaFolder)
    {
        $this->info("\nSyncing files in $mediaFolder->path\n");
        $files = \File::files(public_path('/uploads'). '/' . $mediaFolder->path);
        $bar = $this->output->createProgressBar(count($files));

        foreach ($files as $key => $file) {
            $path = explode('uploads', $file->getPath())[1];
            $filepath = $path . "/" . basename($file);
            $parentFolder = MediaFolder::wherePath(ltrim($path, '/'))->first();

            if (!MediaFile::wherePath($filepath)->exists()) {
                $mediaFile = new MediaFile();
                $mediaFile->name = basename($file);
                $mediaFile->path = $filepath;
                $mediaFile->extension = $file->getExtension();
                $mediaFile->folder_id = $parentFolder ? $parentFolder->id : null;
                $mediaFile->size = $file->getSize();
                $mediaFile->url = config('app.url'). "/uploads" . $filepath;
                $mediaFile->created_at = filemtime($file);
                $mediaFile->save();
            }

            \Log::info("path", [MediaFolder::wherePath(ltrim($path, '/'))->exists() ? MediaFolder::wherePath(ltrim($path, '/'))->first()->id : 'no']);


            /* dd(basename($file),$file->getExtension(), $path, $file->getRealPath(), $file->getSize(), filemtime($file), $file);
            dd(basename($file), $file->getPath(), $file->getRealPath(), $file); */

            $bar->advance();
        }
        $bar->finish();
    }

    public function getRealName($string, $last = true)
    {
        $parts  = explode('/', $string);
        $name = array_pop($parts);
        $string = implode('/', $parts);

        return $last ? $name : $string;
    }
}
