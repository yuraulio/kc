<?php

namespace App\Console\Commands;

use App\Model\Media;
use App\Model\Metas;
use App\Model\Pages;
use App\Model\Slug;
use Illuminate\Console\Command;

class InitPages extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'init:pages';

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
     * @return mixed
     */
    public function handle()
    {
        /*$page = new Pages;

        $page->name = 'Home';
        $page->title = 'Professional educational courses & training';
        $page->content = 'Learn, transform, thrive';
        $page->template = 'home';
        $page->published = true;

        $page->save();

        $metas = new Metas;
        $metas->save();

        $media = new Media;
        $media->save();

        $slug = new Slug;
        $slug->slug = 'home';
        $slug->save();

        $page->metable()->save($metas);
        $page->mediable()->save($media);
        $page->slugable()->save($slug);*/

        ///cart
        $page = new Pages;

        $page->name = 'Cart';
        $page->title = '';
        $page->content = '';
        $page->template = 'cart';
        $page->published = true;

        $page->save();

        $metas = new Metas;
        $metas->save();

        $media = new Media;
        $media->save();

        $slug = new Slug;
        $slug->slug = 'cart';
        $slug->save();

        $page->metable()->save($metas);
        $page->mediable()->save($media);
        $page->slugable()->save($slug);
    }
}
