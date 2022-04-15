<?php

namespace App\Console\Commands;

use App\Model\Admin\MediaFile;
use App\Model\Admin\MediaFolder;
use App\Model\Admin\Page;
use Illuminate\Console\Command;
use App\Model\Topic;
use App\Model\Event;
use App\Model\Category;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

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
        MediaFolder::truncate();
        // DB::table('cms_link_pages_files')->truncate();
        // MediaFile::query()->delete();
        // return;

        $this->info("\nGetting directories in Upload disk\n");

        // $directories = \Storage::disk('public')->directories('/');
        $this->storeDirectories(["/"]);

        // delete records from db for deleted files
        $this->cleanDB();

        MediaFile::get()->searchable();

        return;
    }

    public function storeDirectories($directories)
    {
        $bar = $this->output->createProgressBar(count($directories));

        foreach ($directories ?? [] as $directory) {
            $this->info($directory);

            $bar->start();

            if (!MediaFolder::wherePath($directory)->exists()) {
                $prepath = $this->getRealName("/".$directory, false);
                if ($prepath == "") {
                    $prepath = "/";
                }
                $parent = MediaFolder::wherePath($prepath)->first();
                $folderName = $this->getRealName($directory);
                if ($folderName == "") {
                    $folderName = "/";
                }

                $mediaFolder = new MediaFolder();
                $mediaFolder->name = $folderName;
                $mediaFolder->path = $directory == "/" ? "/" : "/".$directory;
                $mediaFolder->url = config('app.url'). "/uploads" . $mediaFolder->path;
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

        $versions = Page::VERSIONS;

        foreach ($files as $key => $file) {
            $path = explode('uploads', $file->getPath())[1];
            $path = "/" . (ltrim($path, "/"));
            $filepath = $path . "/" . basename($file);
            $parentFolder = MediaFolder::wherePath($path)->first();

            MediaFile::withoutSyncingToSearch(function () use ($parentFolder, $file, $versions, $filepath) {
                $folderId = $parentFolder ? $parentFolder->id : null;
                $parentId = null;

                $name = basename($file);
                $name = Str::of($name)->basename("." . $file->getExtension());
                
                $fileVersion = "original";

                // look for version in the name
                foreach ($versions as $version) {
                    $versionName = $version[0];
                    if (str_ends_with($name, $versionName)) {
                        $this->info("file vesion found: " . $versionName);
                        $fileVersion = $versionName;

                        $originalFileName = Str::of($name)->basename($fileVersion);
                        $originalFile = MediaFile::whereName($originalFileName)->where("folder_id", $folderId)->first();
                        if ($originalFile) {
                            $this->info("original file of version found");
                            $parentId = $originalFile->id;
                        }
                    }
                }

                $mediaFile = MediaFile::wherePath($filepath)->firstOrNew();
                $mediaFile->name = basename($file);
                $mediaFile->path = "/" . (ltrim($filepath, "/"));
                $mediaFile->extension = $file->getExtension();
                $mediaFile->folder_id = $folderId;
                $mediaFile->size = $file->getSize();
                $mediaFile->url = config('app.url'). "/uploads" . $filepath;
                $mediaFile->created_at = filemtime($file);
                $mediaFile->parent_id = $parentId;
                $mediaFile->version = $fileVersion;
                $mediaFile->save();
                
                $this->info("file saved with version: " . $fileVersion);

                if ($fileVersion == "original") {
                    // try to find child files
                    $childFiles = MediaFile::where("name", "LIKE", $name."%")
                        ->where("folder_id", $folderId)->where("id", "!=", $mediaFile->id)->get();
                    foreach ($childFiles as $childFile) {
                        $this->info("child file found");
                        $childVersion = Str::of($childFile->name)->afterLast($name);
                        $childVersion = ltrim($childVersion, "-");
                        $childVersion = Str::of($childVersion)->basename(".".$childFile->extension);

                        if ($childVersion) {
                            $childFile->parent_id = $mediaFile->id;
                            $childFile->version = $childVersion;
                            $childFile->save();
                            $this->info("child file saved with version: " . $childVersion);
                        }
                    }
                }
            });

            // \Log::info("path", [MediaFolder::wherePath(ltrim($path, '/'))->exists() ? MediaFolder::wherePath(ltrim($path, '/'))->first()->id : 'no']);

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

    public function cleanDB()
    {
        $files = MediaFile::get();

        foreach ($files as $file) {
            if (!Storage::disk('public')->exists($file->path)) {
                $file->pages()->detach();
                $file->delete();
            }
        }
    }
}
