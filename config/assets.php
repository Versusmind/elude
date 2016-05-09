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
        'style-core' => [
            \App\Libraries\Assets\Asset::CSS => [
                'resources/assets/bower/bootstrap/dist/css/bootstrap.css',
                //Angular loading bar
                'resources/assets/bower/angular-loading-bar/build/loading-bar.min.css',
                //Font Awesome
                'resources/assets/bower/font-awesome/css/font-awesome.min.css',
                //ng notify
                'resources/assets/bower/ng-notify/dist/ng-notify.min.css',
                //sweet alert
                'resources/assets/bower/sweetalert/dist/sweetalert.css',
            ]
        ],

        'style' => [
             \App\Libraries\Assets\Asset::SASS => [
                'resources/assets/sass/bootmind.scss',
                 
                'resources/assets/app/*.scss',
                'resources/assets/app/*/*.scss',
                'resources/assets/app/*/*/*.scss',
                'resources/assets/app/*/*/*/*.scss',
                'resources/assets/app/*/*/*/*/*.scss'
            ],
        ],

        'javascript-core' => [
            \App\Libraries\Assets\Asset::JS => [
                //JQuery
                'resources/assets/bower/jquery/dist/jquery.min.js',
                // angular JS
                'resources/assets/bower/angularjs/angular.min.js',
                // bootstrap UI
                'resources/assets/bower/angular-bootstrap/ui-bootstrap-tpls.min.js',
                // angular UI router
                'resources/assets/bower/angular-ui-router/release/angular-ui-router.min.js',
                // angular Translate
                'resources/assets/bower/angular-translate/angular-translate.min.js',
                //Underscore
                'resources/assets/bower/lodash/dist/lodash.min.js',
                //Restangular
                'resources/assets/bower/restangular/dist/restangular.min.js',
                //Angular Smart Table
                'resources/assets/bower/angular-smart-table/dist/smart-table.min.js',
                //Angular loading bar
                'resources/assets/bower/angular-loading-bar/build/loading-bar.min.js',
                //ng notify
                'resources/assets/bower/ng-notify/dist/ng-notify.min.js',
                //angular-moment & moment
                'resources/assets/bower/moment/moment.js',
                'resources/assets/bower/moment/locale/fr.js',
                'resources/assets/bower/angular-moment/angular-moment.min.js',
                //ng-focus-if
                'resources/assets/bower/ng-focus-if/focusIf.min.js',
                //sweet alert
                'resources/assets/bower/sweetalert/dist/sweetalert.min.js',
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
                'resources/assets/bower/font-awesome/fonts/*',
            ],
        ]

    ]
];