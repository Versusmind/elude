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
];