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
 * @file        Password.php
 * @author      LAHAXE Arnaud
 * @last-edited 05/09/2015
 * @description Password
 *
 ******************************************************************************/

use App\Jobs\Mails\LostPassword;
use App\Libraries\Acl\Repositories\User;
use App\Libraries\TokenGenerator;
use Carbon\Carbon;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Contracts\Validation\ValidationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;

class Password extends Controller
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
     * @param \App\Libraries\Acl\Repositories\User $userRepository
     * @param \App\Libraries\TokenGenerator        $tokenGenerator
     */
    public function __construct(User $userRepository, TokenGenerator $tokenGenerator)
    {
        $this->userRepository = $userRepository;
        $this->tokenGenerator = $tokenGenerator;
    }

    public function lostPasswordForm()
    {
        return view('auth.lostPassword')
            ->with('error', Input::get('error', false));
    }

    /**
     * @param Request $request
     *
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
            $request->session()->flash('error', 'auth.user_not_found');

            return redirect(route('auth.lostPasswordForm'));
        }

        $user = $users->first();

        $token                                = $this->tokenGenerator->generate(50);
        $user->lost_password_token            = $token;
        $user->lost_password_token_created_at = Carbon::now();

        try {
            $this->userRepository->update($user);
        } catch (ValidationException $e) {
            $request->session()->flash('error', 'auth.user_error_update');

            return redirect(route('auth.lostPasswordForm'));
        }

        $this->dispatch(new LostPassword($user, Crypt::encrypt($token)));

        $request->session()->flash('success', 'auth.password_lost_email_sended');

        return redirect(route('auth.loginForm'));
    }

    /**
     * @return $this|\Illuminate\Http\RedirectResponse|\Laravel\Lumen\Http\Redirector
     */
    public function changeLostPasswordForm(Request $request)
    {
        $token = Input::get('token', false);

        try {
            $decryptToken = Crypt::decrypt($token);
        } catch (DecryptException $e) {
            $request->session()->flash('error', 'auth.token_not_valid');

            return redirect(route('auth.lostPasswordForm'));
        }

        // try to find the user with the username and the decrypt token, this will check the token existence
        $users = $this->userRepository->where([
            'username'            => base64_decode(Input::get('username')),
            'lost_password_token' => $decryptToken
        ]);

        if ($users->count() !== 1) {
            $request->session()->flash('error', 'auth.token_not_valid');

            return redirect(route('auth.lostPasswordForm'));
        }

        $user = $users->first();

        // use a constant for the time validity of the token
        if (Carbon::now()->diffInHours($user->lost_password_token_created_at) > 2) {
            $request->session()->flash('error', 'auth.token_expired');

            return redirect(route('auth.lostPasswordForm'));
        }

        return view('auth.changeLostPassword')
            ->with('user', $user)
            ->with('token', $token)
            ->with('error', Input::get('error', false));
    }

    /**
     * @param Request $request
     *
     * @return $this|\Illuminate\Http\RedirectResponse|\Laravel\Lumen\Http\Redirector
     */
    public function changeLostPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id'  => 'required',
            'token'    => 'required',
            'password' => $this->userRepository->getModel()->getRules()['password']
        ]);

        if ($validator->fails()) {
            return redirect(route('auth.lostPasswordForm'))
                ->withErrors($validator)
                ->withInput();
        }

        $token = Input::get('token', false);

        try {
            $decryptToken = Crypt::decrypt($token);

            // try to find the user with the user id and the decrypt token, this will check the token existence
            $users = $this->userRepository->where([
                'id'                  => Input::get('user_id', false),
                'lost_password_token' => $decryptToken
            ]);

            if ($users->count() !== 1) {
                $request->session()->flash('error', 'auth.user_not_found');

                return redirect(route('auth.lostPasswordForm', ['error' => true]));
            }

            $user = $users->first();

            // use a constant for the time validity of the token
            if (Carbon::now()->diffInHours($user->lost_password_token_created_at) > 2) {
                $request->session()->flash('error', 'auth.token_expired');

                return redirect(route('auth.lostPasswordForm'));
            }

            // remove token
            $user->lost_password_token            = null;
            $user->lost_password_token_created_at = null;
            // hash new password
            $user->password = \Hash::make(Input::get('password'));

            $this->userRepository->update($user);

        } catch (ValidationException $e) {
            $request->session()->flash('error', 'auth.user_error_update');

            return redirect(route('auth.changeLostPasswordForm'));

        } catch (DecryptException $e) {
            $request->session()->flash('error', 'auth.token_not_valid');

            return redirect(route('auth.lostPasswordForm'));
        }

        $request->session()->flash('success', 'auth.password_changed');

        return redirect(route('auth.login'));
    }
}
