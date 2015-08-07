<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

$app->get('/', function() use ($app) {
    return $app->welcome();
});

$app->get('/assets', function() use ($app) {
    $collection = new \App\Libraries\Assets\Collection([
        \App\Libraries\Assets\Asset::CSS => [
            'file1.css',
            'file2.css'
        ],
        \App\Libraries\Assets\Asset::JS => [
            'file1.js',
            'file2.js'
        ],
        \App\Libraries\Assets\Asset::LESS => [
            'file1.less',
            'file2.less'
        ],
        \App\Libraries\Assets\Asset::SASS => [
            'file1.scss',
            'file2.scss'
        ]
    ]);

    (new \App\Libraries\Assets\Orchestrator())->style($collection);
    echo "<hr/>";
    (new \App\Libraries\Assets\Orchestrator())->javascript($collection);
});