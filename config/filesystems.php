<?php

$publicStorageUrl = env('PUBLIC_STORAGE_URL', env('APP_URL'));

return [

    /*
    |--------------------------------------------------------------------------
    | Default Filesystem Disk
    |--------------------------------------------------------------------------
    |
    | Here you may specify the default filesystem disk that should be used
    | by the framework. The "local" disk, as well as a variety of cloud
    | based disks are available to your application. Just store away!
    |
    */

    'default' => env('FILESYSTEM_DRIVER', 'local'),

    /*
    |--------------------------------------------------------------------------
    | Default Cloud Filesystem Disk
    |--------------------------------------------------------------------------
    |
    | Many applications store files both locally and in the cloud. For this
    | reason, you may specify a default "cloud" driver here. This driver
    | will be bound as the Cloud disk implementation in the container.
    |
    */

    'cloud' => env('FILESYSTEM_CLOUD', 's3'),

    /*
    |--------------------------------------------------------------------------
    | Filesystem Disks
    |--------------------------------------------------------------------------
    |
    | Here you may configure as many filesystem "disks" as you wish, and you
    | may even configure multiple disks of the same driver. Defaults have
    | been setup for each driver as an example of the required options.
    |
    | Supported Drivers: "local", "ftp", "sftp", "s3", "rackspace"
    |
    */

    'disks' => [

        'cert' => [
            'driver' => 'local',
            'root' => public_path('/cert'),
        ],
        'import' => [
            'driver' => 'local',
            'root' => public_path('/import'),
        ],
        'local' => [
            'driver' => 'local',
            'root' => public_path('storage/app/public'),
        ],
        'storage' => [
            'driver' => 'local',
            'root' => storage_path('app'),
        ],
        'royalties' => [
            'driver' => 'local',
            'root' => storage_path('app/export/royalties'),
        ],
        'uploads' => [
            'driver' => 'local',
            'root' => public_path('/uploads'),
            'url' => $publicStorageUrl . '/uploads',
            'visibility' => 'public',
        ],

        'awards' => [
            'driver' => 'local',
            'root' => public_path('/awards'),
            'url' => $publicStorageUrl . '/awards',
            'visibility' => 'public',
        ],

        'public' => [
            'driver' => 'local',
            'root' => public_path('/uploads'),
            'url' => $publicStorageUrl . '/uploads',
            'visibility' => 'public',
        ],
        'export' => [
            'driver' => 'local',
            'root' => public_path('/tmp/exports'),
            'url' => $publicStorageUrl . '/tmp/exports',
            'visibility' => 'public',
        ],

        's3' => [
            'driver' => 's3',
            'key' => env('AWS_ACCESS_KEY_ID'),
            'secret' => env('AWS_SECRET_ACCESS_KEY'),
            'region' => env('AWS_DEFAULT_REGION'),
            'bucket' => env('AWS_BUCKET'),
            'url' => env('AWS_URL'),
        ],
        'dropbox' => [
            'driver' => 'dropbox',
            'accessToken' => env('DROPBOX_TOKEN'),
            'appSecret' => env('DROPBOX_APPKEY'),
            'secret' => env('DROPBOX_SECRET'),
            'refresh_token' => env('DROPBOX_REFRESH_TOKEN', 'ye2tGeh1iIQAAAAAAAAAAYW21MH3QmUH-PE2ZCfarQpTK2TZ5MKOS5WJccsMPKUq'),
        ],

    ],
    'links' => [
        public_path('uploads') => storage_path('app/public/uploads'),
    ],

];
