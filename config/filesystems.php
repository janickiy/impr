<?php

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
    | Filesystem Disks
    |--------------------------------------------------------------------------
    |
    | Here you may configure as many filesystem "disks" as you wish, and you
    | may even configure multiple disks of the same driver. Defaults have
    | been setup for each driver as an example of the required options.
    |
    | Supported Drivers: "local", "ftp", "sftp", "s3"
    |
    */

    'disks' => [

        'local' => [
            'driver' => 'local',
            'root' => storage_path('app'),
        ],

        'public' => [
            'driver' => 'local',
            'root' => storage_path('app/public'),
            'url' => env('APP_URL').'/storage',
            'visibility' => 'public',
        ],

        's3_cold' => [
            'driver' => 's3',
            'key' => env('AWS_COLD_ACCESS_KEY_ID'),
            'secret' => env('AWS_COLD_SECRET_ACCESS_KEY'),
            'region' => env('AWS_COLD_DEFAULT_REGION'),
            'bucket' => env('AWS_COLD_BUCKET'),
            'url' => env('AWS_COLD_URL'),
            'endpoint' => env('AWS_COLD_ENDPOINT'),
            'lifetime' => env('AWS_COLD_URL_LIFETIME'),
        ],

        's3_hot' => [
            'driver' => 's3',
            'key' => env('AWS_HOT_ACCESS_KEY_ID'),
            'secret' => env('AWS_HOT_SECRET_ACCESS_KEY'),
            'region' => env('AWS_HOT_DEFAULT_REGION'),
            'bucket' => env('AWS_HOT_BUCKET'),
            'url' => env('AWS_HOT_URL'),
            'endpoint' => env('AWS_HOT_ENDPOINT'),
            'lifetime' => env('AWS_HOT_URL_LIFETIME'),
        ],

        'local_video' => [
            'driver' => 'local',
            'root' => storage_path('videos'),
        ],

        'root' => [
            'driver' => 'local',
            'root' => '/',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Symbolic Links
    |--------------------------------------------------------------------------
    |
    | Here you may configure the symbolic links that will be created when the
    | `storage:link` Artisan command is executed. The array keys should be
    | the locations of the links and the values should be their targets.
    |
    */

    'links' => [
        public_path('storage') => storage_path('app/public'),
    ],

];
