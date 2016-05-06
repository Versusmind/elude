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
 * @file routes.php
 * @author LAHAXE Arnaud
 * @last-edited 05/09/2015
 * @description routes
 *
 ******************************************************************************/

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

/*
|--------------------------------------------------------------------------
| WEB
|--------------------------------------------------------------------------
*/
$app->get('auth/login', ['as' => 'auth.loginForm', 'uses' => 'Auth@loginForm']);
$app->get('auth/lost-password', ['as' => 'auth.lostPasswordForm', 'uses' => 'Password@lostPasswordForm']);
$app->get('auth/change-lost-password', ['as' => 'auth.changeLostPasswordForm', 'uses' => 'Password@changeLostPasswordForm']);
$app->get('auth/register', ['as' => 'auth.registerForm', 'uses' => 'Register@registerForm']);

$app->group(['middleware' => 'csrf'], function () use ($app) {
    $app->post('auth/register', ['as' => 'auth.register', 'uses' => \App\Http\Controllers\Register::class . '@register']);
    $app->post('auth/change-lost-password', ['as' => 'auth.changeLostPassword', 'uses' => \App\Http\Controllers\Password::class . '@changeLostPassword']);
    $app->post('auth/lost-password', ['as' => 'auth.lostPassword', 'uses' => \App\Http\Controllers\Password::class . '@lostPassword']);
    $app->post('auth/login', ['as' => 'auth.login', 'uses' => \App\Http\Controllers\Auth::class . '@login']);


    $app->get('oauth/google/connect', ['as' => 'oauth.google.connect', 'uses' => \App\Http\Controllers\OAuth\Google::class . '@connect']);
    $app->get('oauth/google/callback', ['as' => 'oauth.google.callback', 'uses' => \App\Http\Controllers\OAuth\Google::class . '@callback']);

});

$app->group(['middleware' => 'auth|csrf'], function () use ($app) {
    $app->get('auth/logout', ['as' => 'auth.logout', 'uses' => \App\Http\Controllers\Auth::class . '@logout']);

    $app->get('/', function () use ($app) {

        Clockwork::info('Message text.'); // 'Message text.' appears in Clockwork log tab
        Log::info('Lumen logger interface.'); // 'Message text.' appears in Clockwork log tab as well as application log file

        Clockwork::info(array('hello' => 'world')); // logs json representation of the array


        return view('index');
    });

    $app->get('/acl', ['middleware' => 'acl:test.test', 'as' => 'acl.test', function () {
        dd('OK');
    }]);

});

/*
|--------------------------------------------------------------------------
| API
|--------------------------------------------------------------------------
*/
$app->group(['prefix' => 'api/v1', 'middleware' => 'cors'], function () use ($app) {
    $app->post('oauth/access_token', ['as' => 'oauth.login', 'uses' => App\Http\Controllers\Api\Auth::class . '@login']);

    $app->group(['middleware' => 'cors|oauth', 'prefix' => 'api/v1'], function () use ($app) {
        $app->resource('groups', \App\Http\Controllers\Api\Group::class);
        $app->post('groups/{id}/permissions/{idPermission}', ['as' => 'groups.permissions.store', 'uses' => \App\Http\Controllers\Api\Group::class . '@permissionStore']);
        $app->delete('groups/{id}/permissions/{idPermission}', ['as' => 'groups.permissions.destroy', 'uses' => \App\Http\Controllers\Api\Group::class . '@permissionDestroy']);
        $app->post('groups/{id}/roles/{idRole}', ['as' => 'groups.roles.store', 'uses' => \App\Http\Controllers\Api\Group::class . '@roleStore']);
        $app->delete('groups/{id}/roles/{idRole}', ['as' => 'groups.roles.destroy', 'uses' => \App\Http\Controllers\Api\Group::class . '@roleDestroy']);

        $app->resource('roles', \App\Http\Controllers\Api\Role::class);
        $app->post('roles/{id}/permissions/{idPermission}', ['as' => 'roles.permissions.store', 'uses' => \App\Http\Controllers\Api\Role::class . '@permissionStore']);
        $app->delete('roles/{id}/permissions/{idPermission}', ['as' => 'roles.permissions.destroy', 'uses' => \App\Http\Controllers\Api\Role::class . '@permissionDestroy']);

        $app->resource('users', \App\Http\Controllers\Api\User::class);
        $app->post('users/{id}/permissions/{idPermission}', ['as' => 'users.permissions.store', 'uses' => \App\Http\Controllers\Api\User::class . '@permissionStore']);
        $app->delete('users/{id}/permissions/{idPermission}', ['as' => 'users.permissions.destroy', 'uses' => \App\Http\Controllers\Api\User::class . '@permissionDestroy']);
        $app->post('users/{id}/roles/{idRole}', ['as' => 'users.roles.store', 'uses' => \App\Http\Controllers\Api\User::class . '@roleStore']);
        $app->delete('users/{id}/roles/{idRole}', ['as' => 'users.roles.destroy', 'uses' => \App\Http\Controllers\Api\User::class . '@roleDestroy']);
        $app->put('users/{id}/group/{idGroup}', ['as' => 'users.group.update', 'uses' => \App\Http\Controllers\Api\User::class . '@groupUpdate']);
        $app->patch('users/{id}/group/{idGroup}', ['as' => 'users.group.update', 'uses' => \App\Http\Controllers\Api\User::class . '@groupUpdate']);

        $app->resource('permissions', \App\Http\Controllers\Api\Permission::class);

        $app->post('oauth/logout', ['as' => 'oauth.logout', 'uses' => App\Http\Controllers\Api\Auth::class . '@logout']);
    });
});