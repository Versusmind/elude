<?php

/**
 * Created by PhpStorm.
 * Author LAHAXE Arnaud <lahaxe.arnaud@gmail.com>
 * Date: 08/08/15
 * Time: 16:49
 */
return [
    /**
     * If true enable minification and concatenation of js and (less + sass + css)
     */
    'concat'          => env('ASSETS_CONCAT', true),

    /**
     * Initial assets folder
     */
    'assetsDirectory' => base_path('resources/assets'),

    /**
     * Bower folders
     */
    'bowerDirectory'  => base_path('resources/assets/bower'),

    /**
     * Tmp folder, this folder contains only temporary files, clear on each build
     */
    'tmpDirectory'    => storage_path('tmp'),

    /**
     * File directory, must be in public folder
     */
    'outputDirectory' => base_path('public/assets/'),

    /**
     * Group of assets
     */
    'groups' => [
        'style' => [
            \App\Libraries\Assets\Asset::CSS  => [
                'resources/assets/css/folder/file.css',
                'resources/assets/css/file.css',
            ],
            \App\Libraries\Assets\Asset::LESS => [
                'resources/assets/less/file.less',
                'resources/assets/less/folder/file2.less',
            ]
        ],

        'javascript-core' => [
            \App\Libraries\Assets\Asset::JS => [
                'resources/assets/bower/angularjs/angular.js',
            ]
        ],

        'javascript-app' => [
            \App\Libraries\Assets\Asset::JS => [
                'resources/assets/js/file.js',
            ]
        ]
    ]
];