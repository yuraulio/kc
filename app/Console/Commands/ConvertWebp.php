<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Model\Media;
use WebPConvert\WebPConvert;
use Intervention\Image\ImageManagerStatic as Image;
use Storage;

class ConvertWebp extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'convert:webp';

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
        $image = Media::where('id','=', '5694')->first();

        $source = '\courses\Knowcrunch-Digital-Marketing-Course-Athens.JPG';
        //dd($source);

        $destination = '\courses\Knowcrunch-Digital-Marketing-Course-Athens.webp';
        $options = [
            'quality'=> 80,
            'auto-limit'=> true,
        ];

        //dd(public_path('/uploads/').$source);
        $a = Image::make(public_path('/uploads/').$source)->stream("webp", 70);
        //dd($a);
        Storage::disk('local')->put(public_path('/uploads/').$destination, $a, 'public');
       //WebPConvert::convert($source, $destination, $options);



    }
}
