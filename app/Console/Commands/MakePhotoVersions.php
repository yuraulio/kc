<?php

namespace App\Console\Commands;

use App\Model\Media;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManagerStatic as Image;

class MakePhotoVersions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'media:versions';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
        $medias = Media::all();
        foreach ($medias as $media) {
            if (!$media['original_name']) {
                continue;
            }
            if ($media['mediable_type'] == 'App\\Model\\Logos') {
                continue;
            }
            $details = json_decode($media['details'], true);

            if ($media['ext'] == '.svg') {
                continue;
            }

            if (!$details) {
                continue;
            }
            foreach (get_image_versions() as $value) {
                if (!file_exists(public_path($media['path'] . $media['original_name']))) {
                    continue;
                }

                //if(isset($details['img_align'][$value['version']]) && $details['img_align'][$value['version']]['x'] > 0 && $details['img_align'][$value['version']]['y'] > 0){
                if (isset($details['img_align'][$value['version']])) {
                    $edit = $details['img_align'][$value['version']];
                    $image_resize = Image::make(public_path($media['path'] . $media['original_name']));
                    //$image_resize->crop(intval($edit['width']),intval($edit['height']), intval($edit['x']), intval($edit['y']));
                    //$image_resize->resize($value['w'],$value['h']);

                    if ($image_resize->width() > $image_resize->height()) {
                        $image_resize->crop(intval($edit['width']), intval($edit['height']), intval($edit['x']), intval($edit['y']))->heighten($value['h']);
                    } elseif ($image_resize->width() < $image_resize->height()) {
                        $image_resize->crop(intval($edit['width']), intval($edit['height']), intval($edit['x']), intval($edit['y']))->widen($value['w']);
                    } else {
                        $image_resize->crop(intval($edit['width']), intval($edit['height']), intval($edit['x']), intval($edit['y']))->resize($value['w'], $value['h']);
                    }

                    $image_resize->save(public_path($media['path'] . $media['name'] . '-' . $value['version'] . $media['ext']), $value['q']);
                }
            }
        }
    }
}
