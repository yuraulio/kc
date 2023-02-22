<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Storage;
use App\Model\Media;
use Intervention\Image\ImageManagerStatic as Image;
use App\Model\Admin\Page;
use Intervention\Image\ImageManager;

class ResizeImages extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'resize:image {folder_name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    public $versions = [
        'instructors-testimonials'=>[
            'w' => 470,
            'h' => 470,
            'q' => 60,
            'fit' => 'crop',
            'version' => 'instructors-testimonials',
            'description' => 'Applies to : Our Instructor Page (Footer) & Event -> Instructors',

        ],
        'event-card'=>[
            'w' => 542,
            'h' => 291,
            'q' => 60,
            'fit' => 'crop',
            'version' => 'event-card',
            'description' => 'Applies to : Homepage Events list',
            // 'description' => 'Applies to : Event version in grid layout',
        ],
        'users'=>[
            'w' => 470,
            'h' => 470,
            'q' => 60,
            'fit' => 'crop',
            'version' => 'users',
            'description' => 'Applies to : Testimonial square image',
        ],
        'header-image'=>[
            'w' => 2880,
            'h' => 1248,
            'q' => 60,
            'fit' => 'crop',
            'version' => 'header-image',
            'description' => 'Applies to: Event header carousel (Main event page)',
        ],
        'instructors-small'=>[
            'w' => 90,
            'h' => 90,
            'q' => 60,
            'fit' => 'crop',
            'version' => 'instructors-small',
            'description' => 'Applies to : Event -> Topics (syllabus-block)',
        ],
        'feed-image'=>[
            'w' => 300,
            'h' => 300,
            'q' => 60,
            'fit' => 'crop',
            'description' => 'feed-image',
            'version' => 'feed-image',
        ],
        'social-media-sharing'=>[
            'w' => 1920,
            'h' => 832,
            'q' => 60,
            'fit' => 'crop',
            'version' => 'social-media-sharing',
            'description' => 'Applies to: Social media sharing default image'
        ]

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
        
        foreach($folders as $folder){
            if($folder == $this->argument('folder_name')){
                $files = Storage::disk('public')->files($this->argument('folder_name'));
                foreach($files as $file){
                    $this->convert($file);
                }
                

            }
        }


        return 0;
    }


    public function convert($file){

        $versions = Page::VERSIONS;

        foreach($versions as $version){
            $ver = $version[0];

            $crop_height = $version[2];
            $crop_width = $version[1];
            if(str_contains($file, $ver)){

                $pos = strrpos($file, '/');
                
                $image_name = $pos === false ? $file : substr($file, $pos + 1);
                $path = explode($image_name, $file)[0];
                


                
                $tmp = explode('.', $image_name);
              
                $extension = end($tmp);
                if ($tmp[1] == "jpg") {
                    $extension = "jpg";
                }else if($tmp[1] == "png"){
                    $extension = "png";
                }

                $image_name = $tmp[0];

                $version_name = pathinfo($image_name, PATHINFO_FILENAME);
                $version_name = $version_name . "." . $extension;

                // set image path
                $imgpath = $path . '' . $version_name;

                // save image
                // $file = Storage::disk('public')->putFileAs($path, $request->file('file'), $version_name, 'public');
              
                

                // crop image
                $manager = new ImageManager();
                $image = $manager->make(Storage::disk('public')->get('\\'.$imgpath));

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
                $image->save(public_path("/uploads/" . $path . $version_name), 80, $extension);

            }
        }


    }
}
