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
    | Supported Drivers: "local", "ftp", "s3", "rackspace"
    |
    */

    'disks' => [

        'local' => [
            'driver' => 'local',
            'root' => storage_path('s3'),
        ],

        'logs' => [
            'driver' => 'local',
            'root' => storage_path('logs'),
        ],

        'public' => [
            'driver' => 'local',
            'root' => public_path('img/users'),
            'visibility' => 'public',
        ],

        'data' => [
            'driver' => 'local',
            'root' => storage_path('data'),
            'url' => env('APP_URL').'/storage/data',
            'visibility' => 'public',
        ],

        's3' => [
            'driver' => 's3',
            'arn'    => env('AWS_ARN_ROLE'),
            'key'    => env('AWS_KEY'),
            'secret' => env('AWS_SECRET'),
            'region' => env('AWS_REGION') ?? 'us-west-2',
            'bucket' => env('AWS_BUCKET') ?? 'dbp-prod',
        ],

        'cloudfront' => [
            'driver' => 'cloudfront',
            'key'    => env('AWS_CLOUDFRONT_KEY_ID'),
            'secret' => env('AWS_CLOUDFRONT_KEY_SECRET')
        ],

        's3_dbs' => [
            'driver' => 's3',
            'key'    => env('DBS_AWS_KEY'),
            'secret' => env('DBS_AWS_SECRET'),
            'region' => env('DBS_AWS_REGION') ?? 'us-west-2',
            'bucket' => env('DBS_AWS_BUCKET'),
        ],

        'dbp-web' => [
            'driver' => 's3',
            'key'    => env('FCBH_AWS_KEY'),
            'secret' => env('FCBH_AWS_SECRET'),
            'region' => env('FCBH_AWS_REGION') ?? 'us-west-2',
            'bucket' => env('FCBH_AWS_BUCKET'),
        ],

        's3_dbs_log' => [
            'driver' => 's3',
            'key'    => env('AWS_KEY'),
            'secret' => env('AWS_SECRET'),
            'region' => env('AWS_REGION') ?? 'us-west-2',
            'bucket' => env('AWS_BUCKET_LOG'),
        ],

        's3_fcbh' => [
            'driver' => 's3',
            'key'    => env('FCBH_AWS_KEY'),
            'secret' => env('FCBH_AWS_SECRET'),
            'region' => env('FCBH_AWS_REGION') ?? 'us-west-2',
            'bucket' => env('FCBH_AWS_BUCKET'),
        ],

        'dbp-dev' => [
            'driver' => 's3',
            'key'    => env('FCBH_AWS_KEY'),
            'secret' => env('FCBH_AWS_SECRET'),
            'region' => env('FCBH_AWS_REGION') ?? 'us-west-2',
            'bucket' => env('FCBH_AWS_BUCKET'),
        ],

        'dbp-dev-cdn' => [
            'driver' => 's3',
            'key'    => env('DBS_AWS_KEY'),
            'secret' => env('DBS_AWS_SECRET'),
            'region' => env('DBS_AWS_REGION') ?? 'us-west-2',
            'bucket' => 'dbp-dev-cdn',
        ],

        's3_fcbh_ccdn' => [
            'driver' => 's3',
            'key'    => env('FCBH_AWS_KEY'),
            'secret' => env('FCBH_AWS_SECRET'),
            'region' => env('FCBH_AWS_REGION') ?? 'us-west-2',
            'bucket' => env('FCBH_AWS_BUCKET'),
            'url'    => 'https://ccdn.bible.build',
        ],

        's3_fcbh_stream' => [
            'driver' => 's3',
            'key'    => env('FCBH_AWS_KEY'),
            'secret' => env('FCBH_AWS_SECRET'),
            'region' => env('FCBH_AWS_REGION') ?? 'us-west-2',
            'bucket' => env('FCBH_AWS_BUCKET'),
            'url'    => 'rtmp://stream.bible.build/cfx/st',
        ],

        's3_fcbh_video' => [
            'driver' => 's3',
            'key'    => env('FCBH_AWS_VIDEO_KEY') ?? env('FCBH_AWS_KEY'),
            'secret' => env('FCBH_AWS_VIDEO_SECRET') ?? env('FCBH_AWS_SECRET'),
            'region' => env('FCBH_AWS_VIDEO_REGION') ?? env('FCBH_AWS_REGION'),
            'bucket' => env('FCBH_AWS_VIDEO_BUCKET')
        ]

    ],

];
