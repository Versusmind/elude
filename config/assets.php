<?php

/******************************************************************************
 *
 * @package Myo 2
 * @copyright © 2015 by Versusmind.
 * All rights reserved. No part of this document may be
 * reproduced or transmitted in any form or by any means,
 * electronic, mechanical, photocopying, recording, or
 * otherwise, without prior written permission of Versusmind.
 * @link http://www.versusmind.eu/
 *
 * @file assets.php
 * @author LAHAXE Arnaud
 * @last-edited 18/08/15
 * @description assets
 *
 ******************************************************************************/


return [
    /**
     * If true enable minification and concatenation of js and (less + sass + css)
     */
    'concat' => env('ASSETS_CONCAT', true),

    /**
     * Initial assets folder
     */
    'assetsDirectory' => base_path('resources' . DIRECTORY_SEPARATOR . 'assets'),

    /**
     * Bower folders
     */
    'bowerDirectory' => base_path('resources' . DIRECTORY_SEPARATOR . 'assets' . DIRECTORY_SEPARATOR . 'bower'),

    /**
     * Tmp folder, this folder contains only temporary files, clear on each build
     */
    'tmpDirectory' => storage_path('tmp'),

    /**
     * File directory, must be a subfolder of public.
     * If you put public/ the world may burn
     */
    'outputDirectory' => base_path('public' . DIRECTORY_SEPARATOR . 'assets' . DIRECTORY_SEPARATOR),

    /**
     * Group of assets you need separate style and javascript for the moment
     * example:
     * 'style' => [
     *       \App\Libraries\Assets\Asset::CSS  => [
     *           'resources/assets/css/folder/file.css',
     *           'resources/assets/css/file.css',
     *       ],
     *       \App\Libraries\Assets\Asset::LESS => [
     *           'resources/assets/less/file.less',
     *           'resources/assets/less/folder/file2.less',
     *       ],
     *       \App\Libraries\Assets\Asset::SASS => [
     *           'resources/assets/sass/file.scss',
     *       ]
     *   ],
     * 'javascript' => [
     *       \App\Libraries\Assets\Asset::JS  => [
     *           'resources/assets/js/*.js',
     *       ]
     *   ],
     *
     * The order of assets is the same as the array.
     * If in the same group you use CSS, LESS and SASS after compilation files will
     * be included in this order: LESS, SASS then CSS
     *
     */
    'groups' => [
        'style' => [
            \App\Libraries\Assets\Asset::CSS => [
                'resources/assets/bower/bootstrap/dist/css/bootstrap.css',
                'resources/assets/bower/angular-bootstrap/ui-bootstrap-csp.css',
            ]
        ],

        'javascript-core' => [
            \App\Libraries\Assets\Asset::JS => [
                // angular JS
                'resources/assets/bower/angularjs/angular.js',
                // bootstrap UI
                'resources/assets/bower/angular-bootstrap/ui-bootstrap.js',
                'resources/assets/bower/angular-bootstrap/ui-bootstrap-tpls.js',
                // angular UI router
                'resources/assets/bower/angular-ui-router/release/angular-ui-router.js',
            ]
        ],

        'javascript-app' => [
            \App\Libraries\Assets\Asset::JS => [
                'resources/assets/app/*.js',
                'resources/assets/app/*/*.js',
                'resources/assets/app/*/*/*.js',
                'resources/assets/app/*/*/*/*.js',
                'resources/assets/app/*/*/*/*/*.js',
            ],

            \App\Libraries\Assets\Asset::TEMPLATE => [
                'resources/assets/app/*.html',
                'resources/assets/app/*/*.html',
                'resources/assets/app/*/*/*.html',
                'resources/assets/app/*/*/*/*.html',
                'resources/assets/app/*/*/*/*/*.html',
            ]
        ],

        'statics' => [
            \App\Libraries\Assets\Asset::FONT => [
                'resources/assets/bower/bootstrap/fonts/*',
            ],
        ]

    ]
];