<?php

return [
    'gallery-originals' => [
        'driver' => 'local',
        'root' => storage_path('gallery'),
    ],

    'gallery' => [
        'driver' => 'local',
        'root' => storage_path('app/public/gallery'),
        'url' => env('APP_URL') . '/storage/gallery',
        'visibility' => 'public',
    ],
];
