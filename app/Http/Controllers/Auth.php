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

use App\Jobs\Mails\LostPassword;
use App\Libraries\Acl\Repositories\User;
use App\Libraries\OAuth\Password;
use App\Libraries\TokenGenerator;
use Carbon\Carbon;
use Illuminate\Contracts\Validation\ValidationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class Auth extends Controller
{

    /**
     * @var User
     */
    protected $userRepository;

    /**
     * @var TokenGenerator
     */
    protected $tokenGenerator;

    /**
     * Auth constructor.
     * @param $userRepository
     */
    public function __construct(User $userRepository, TokenGenerator $tokenGenerator)
    {
        $this->userRepository = $userRepository;
        $this->tokenGenerator = $tokenGenerator;
    }

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

        return view('auth.login')
            ->with('error', Input::get('error', false));
    }

    /**
     * @author LAHAXE Arnaud
     *
     *
     * @return \Illuminate\Http\RedirectResponse|\Laravel\Lumen\Http\Redirector
     */
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

    /**
     * @author LAHAXE Arnaud
     *
     * @param \App\Libraries\OAuth\Password $passwordService
     *
     * @return \Illuminate\Http\RedirectResponse|\Laravel\Lumen\Http\Redirector
     */
    public function logout(Password $passwordService)
    {
        // remove OAuth token from database and session
        $passwordService->logout();

        // logout user
        \Auth::logout();

        return redirect(route('auth.loginForm'));
    }


    public function lostPasswordForm()
    {
        return view('auth.lostPassword')
            ->with('error', Input::get('error', false));
    }

    /**
     * @param Request $request
     * @return $this|\Illuminate\Http\RedirectResponse|\Laravel\Lumen\Http\Redirector
     */
    public function lostPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'username' => 'required'
        ]);

        if ($validator->fails()) {
            return redirect(route('auth.lostPasswordForm'))
                ->withErrors($validator)
                ->withInput();
        }

        $users = $this->userRepository->where([
            'username' => Input::get('username', false)
        ]);

        if ($users->count() !== 1) {
            return redirect(route('auth.lostPasswordForm', ['error' => true]));
        }

        $user = $users->first();

        $token = $this->tokenGenerator->generate(50);
        $user->lost_password_token = $token;
        $user->lost_password_token_created_at = Carbon::now();

        try {
            $this->userRepository->update($user);
        } catch (ValidationException $e) {
            return redirect(route('auth.lostPasswordForm', ['error' => true]));
        }

        $this->dispatch(new LostPassword($user, Crypt::encrypt($token)));

        return redirect(route('auth.loginForm'));
    }

    /**
     * @return $this|\Illuminate\Http\RedirectResponse|\Laravel\Lumen\Http\Redirector
     */
    public function changeLostPasswordForm()
    {
        $token = Input::get('token', false);
        $users = $this->userRepository->where([
            'username' => base64_decode(Input::get('username')),
            'lost_password_token' => Crypt::decrypt($token)
        ]);

        if ($users->count() !== 1) {
            return redirect(route('auth.lostPasswordForm', ['error' => true]));
        }

        $user = $users->first();

        // use a constant for the time validity of the token
        if(Carbon::now()->diffInHours($user->lost_password_token_created_at) > 2) {
            return redirect(route('auth.lostPasswordForm', ['error' => true]));
        }


        return view('auth.changeLostPassword')
            ->with('user', $user)
            ->with('token', $token)
            ->with('error', Input::get('error', false));
    }

    /**
     * @param Request $request
     * @return $this|\Illuminate\Http\RedirectResponse|\Laravel\Lumen\Http\Redirector
     */
    public function changeLostPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required',
            'token' => 'required',
            'password' => 'required'
        ]);

        if ($validator->fails()) {
            return redirect(route('auth.lostPasswordForm'))
                ->withErrors($validator)
                ->withInput();
        }

        $token = Input::get('token', false);
        $users = $this->userRepository->where([
            'id' => Input::get('user_id', false),
            'lost_password_token' => Crypt::decrypt($token)
        ]);

        if ($users->count() !== 1) {
            return redirect(route('auth.lostPasswordForm', ['error' => true]));
        }

        $user = $users->first();
        $user->lost_password_token = null;
        $user->password = \Hash::make(Input::get('password'));

        try {
            $this->userRepository->update($user);
        } catch (ValidationException $e) {
            return redirect(route('auth.changeLostPasswordForm', ['error' => true]));
        }

        return redirect(route('auth.login'));
    }
}
