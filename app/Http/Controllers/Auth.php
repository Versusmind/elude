<?php namespace App\Http\Controllers;

/******************************************************************************
 *
 * @package     Myo 2
 * @copyright   © 2015 by Versusmind.
 * All rights reserved. No part of this document may be
 * reproduced or transmitted in any form or by any means,
 * electronic, mechanical, photocopying, recording, or
 * otherwise, without prior written permission of Versusmind.
 * @link        http://www.versusmind.eu/
 *
 * @file        Auth.php
 * @author      LAHAXE Arnaud
 * @last-edited 05/09/2015
 * @description Auth
 *
 ******************************************************************************/

use App\Libraries\OAuth\Password as OAuthPassword;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Session;

class Auth extends Controller
{

    /**
     * @author LAHAXE Arnaud
     *
     *
     * @return $this|\Illuminate\Http\RedirectResponse|\Laravel\Lumen\Http\Redirector
     */
    public function loginForm()
    {
        if (!\Auth::guest()) {
            return redirect('/');
        }

        return view('auth.login');
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Laravel\Lumen\Http\Redirector
     */
    public function login(Request $request)
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
            $request->session()->flash('error', 'auth.login_error');

            return redirect(route('auth.loginForm'));
        }
    }

    /**
     * @author LAHAXE Arnaud
     *
     * @param \App\Libraries\OAuth\Password $passwordService
     *
     * @return \Illuminate\Http\RedirectResponse|\Laravel\Lumen\Http\Redirector
     */
    public function logout(OAuthPassword $passwordService)
    {
        // remove OAuth token from database and session
        $passwordService->logout();

        // logout user
        \Auth::logout();

        return redirect(route('auth.loginForm'));
    }
}
