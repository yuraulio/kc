<?php

namespace App\Console\Commands;

use App\Model\Admin\Page;
use App\Model\Media;
use Illuminate\Console\Command;
use Intervention\Image\ImageManager;
use Intervention\Image\ImageManagerStatic as Image;
use Storage;

class ResizeImages extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'resize:image';

    /*
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    public $paths = [
        'Benefit',
        'blog',
        'blog_image',
        'courses',
        'headers',
        'instructors',
        'pages',
    ];

    public $paths_originals = [
        'corporate_brands',
        'events',
        'instructors',
        'logos',
        'pages',
        'testimonials',
    ];

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
        $folders = Storage::disk('public')->directories();

        $files = Storage::disk('public')->files();
        foreach ($files as $file) {
            $this->convert($file);
        }

        foreach ($folders as $folder) {
            foreach ($this->paths as $key => $fol) {
                if ($folder == $fol) {
                    print_r($fol);
                    //dd($fol);

                    $files = Storage::disk('public')->files($fol);

                    foreach ($files as $file) {
                        $this->convert($file);
                    }
                }
            }
        }

        $folders = Storage::disk('public')->directories('originals');
        //dd($folders);

        foreach ($folders as $fol) {
            foreach ($this->paths_originals as $path) {
                if (str_contains($fol, $path)) {
                    print_r($fol);
                    $files = Storage::disk('public')->files($fol);
                    foreach ($files as $file) {
                        $this->convert($file);
                    }
                    //dd($files);
                }
            }
        }

        return 0;
    }

    public function convert($file, $from = false)
    {
        $versions = Page::VERSIONS;

        foreach ($versions as $version) {
            $ver = $version[0];

            $crop_height = $version[2];
            $crop_width = $version[1];

            if (str_contains($file, $ver)) {
                // dd($file.'  '.$ver);

                $pos = strrpos($file, '/');

                $image_name = $pos === false ? $file : substr($file, $pos + 1);
                $path = explode($image_name, $file)[0];

                $tmp = explode('.', $image_name);

                $extension = end($tmp);
                if ($tmp[1] == 'jpg') {
                    $extension = 'jpg';
                } elseif ($tmp[1] == 'png') {
                    $extension = 'png';
                } elseif ($tmp[1] == 'JPG') {
                    $extension = 'JPG';
                } else {
                    continue;
                }

                $image_name = $tmp[0];

                $version_name = pathinfo($image_name, PATHINFO_FILENAME);
                $version_name = $version_name . '.' . $extension;

                // set image path
                $imgpath = $path . '' . $version_name;

                // save image
                // $file = Storage::disk('public')->putFileAs($path, $request->file('file'), $version_name, 'public');

                // crop image
                $manager = new ImageManager();
                $image = $manager->make(Storage::disk('public')->get('\\' . $imgpath));

                $image_height = $image->height();
                $image_width = $image->width();

                $crop_height = $version[2];
                $crop_width = $version[1];

                $height_offset = ($image_height / 2) - ($crop_height / 2);
                $height_offset = $height_offset > 0 ? (int) $height_offset : null;

                $width_offset = ($image_width / 2) - ($crop_width / 2);
                $width_offset = $width_offset > 0 ? (int) $width_offset : null;

                $image->resize($crop_width, $crop_height);
                $image->fit($crop_width, $crop_height);

                //$image->crop($crop_width, $crop_height, $width_offset, $height_offset);
                $image->save(public_path('/uploads/' . $path . $version_name), 100, $extension);

                echo nl2br('/uploads/' . $path . $version_name);
            }
        }
    }
}
