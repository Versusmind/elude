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

    return view('index');
});

$app->get('protected', ['middleware' => 'oauth', function() {
    dump('OK');
}]);

$app->get('scope', ['middleware' => 'oauth:scope1',  function() {
    dump('OK scope1');
}]);

$app->post('oauth/access_token', ['as' => 'oauth.login', function() {

    return response()->json(Authorizer::issueAccessToken());
}]);