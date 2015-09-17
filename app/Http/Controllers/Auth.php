<?php namespace App\Http\Controllers;

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
 * @file Auth.php
 * @author LAHAXE Arnaud
 * @last-edited 05/09/2015
 * @description Auth
 *
 ******************************************************************************/

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Session;

class Auth extends Controller
{
    public function loginForm()
    {
        if (!\Auth::guest()) {
            return redirect('/');
        }

        return view('auth.login')
            ->with('error', Input::get('error', false));
    }

    public function login()
    {

        // set default web oauth client
        Input::merge(['client_id' => Config::get('oauth2.web_client.client_id')]);
        Input::merge(['client_secret' => Config::get('oauth2.web_client.client_secret')]);
        Input::merge(['grant_type' => 'password']);

        try {

            $oauth = \Authorizer::issueAccessToken();

            // save oauth token and access token in session
            Session::put('oauth', $oauth);

            return redirect('/');
        } catch (\Exception $e) {

            return redirect(route('auth.loginForm', ['error' => true]));
        }
    }
}
