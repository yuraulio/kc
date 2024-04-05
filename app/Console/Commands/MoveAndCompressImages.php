<?php

namespace App\Console\Commands;

use App\CMSFile;
use App\Jobs\CompressAndMoveImages;
use App\Model\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManagerStatic as Image;

class MoveAndCompressImages extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:move-and-compress-images';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Transform the images and move them to the correct location.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        echo 'Init';
        $total = 0;
        $users = User::whereHas('image')->get();
        foreach ($users as $user) {
            if ($user->image->path != '' && $user->image->original_name != '') {
                if (file_exists(storage_path('app/public' . $user->image->path . $user->image->original_name))) {
                    $image = Image::make(public_path() . $user->image->path . $user->image->original_name);
                    $w = $image->width();
                    $h = $image->height();
                    $name_parts = explode('.', $user->image->original_name);
                    $new_name = $name_parts[0] . '.webp';
                    $image->save(public_path('/uploads/Users/') . $new_name, 60);

                    // original image
                    $CMSFile = new CMSFile();
                    $CMSFile->name = $new_name;
                    $CMSFile->path = '/Users/' . $new_name;
                    $CMSFile->url = config('app.url') . '/uploads/Users/' . $new_name;
                    $CMSFile->extension = 'webp';
                    $CMSFile->size = filesize(storage_path('app/public/uploads/Users/' . $new_name));
                    $CMSFile->folder_id = 43;
                    $CMSFile->parent_id = null;
                    $CMSFile->user_id = Auth::id();
                    $CMSFile->full_path = '/uploads/Users/' . $new_name;
                    $CMSFile->alt_text = $new_name;
                    $CMSFile->version = 'original';
                    $CMSFile->link = $new_name;
                    $path = public_path("uploads/Users/{$new_name}");
                    if (File::exists($path)) {
                        $size = getimagesize($path);
                        $CMSFile->height = (float) $size[1];
                        $CMSFile->width = (float) $size[0];
                    }
                    $CMSFile->crop_data = null;
                    $CMSFile->save();

                    // thumb image
                    $name_parts = explode('.', $new_name);
                    $new_name_thumb = $name_parts[0] . '-users.' . $name_parts[1];
                    $image->save(public_path('/uploads/Users/') . $new_name_thumb, 60);
                    $CMSFileThumb = $CMSFile->replicate();
                    $CMSFileThumb->name = $new_name_thumb;
                    $CMSFileThumb->path = '/Users/' . $new_name_thumb;
                    $CMSFileThumb->url = config('app.url') . '/uploads/Users/' . $new_name_thumb;
                    $CMSFileThumb->full_path = '/uploads/Users/' . $new_name_thumb;
                    $CMSFileThumb->alt_text = $new_name_thumb;
                    $CMSFileThumb->version = 'users';
                    $CMSFileThumb->link = $new_name_thumb;
                    $CMSFileThumb->parent_id = $CMSFile->id;
                    $CMSFileThumb->crop_data = '{"crop_height":' . $size[1] . ',"crop_width":' . $size[0] . ',"height_offset":0,"width_offset":0}';
                    $CMSFileThumb->save();

                    $user->profile_image_id = $CMSFileThumb->id;
                    $user->save();

                    $user->image->delete();
                    $total++;
                } else {
                    echo 'n';
                }
            } else {
                echo 'e';
            }
        }
    }
}
