<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use League\Flysystem\Filesystem;
use Spatie\Dropbox\Client as DropboxClient;
//use Dropbox\Client as DropboxClient;
use Spatie\FlysystemDropbox\DropboxAdapter;
//use League\Flysystem\Dropbox\DropboxAdapter;

//use Spatie\Dropbox\Client;
use Storage;

class DropboxFilesystemServiceProvider extends ServiceProvider
{
    public function boot()
    {
        /* Storage::extend('dropbox', function ($app, $config) {
             $client = new DropboxClient($config['accessToken'], $config['appSecret']);

             return new Filesystem(new DropboxAdapter($client));
         });*/
        Storage::extend('dropbox', function ($app, $config) {
            $client = new DropboxClient(
                $config['accessToken']
            );

            return new Filesystem(new DropboxAdapter($client));
        });
    }

    public function register()
    {
        //
    }
}
