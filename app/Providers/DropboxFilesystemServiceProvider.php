<?php

namespace App\Providers;

use Storage;
use League\Flysystem\Filesystem;
use Illuminate\Support\ServiceProvider;
//use Dropbox\Client as DropboxClient;
use Spatie\Dropbox\Client as DropboxClient;
//use League\Flysystem\Dropbox\DropboxAdapter;


//use Spatie\Dropbox\Client;
use Spatie\FlysystemDropbox\DropboxAdapter;

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

