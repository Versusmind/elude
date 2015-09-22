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
 * @file        Register.php
 * @author      LAHAXE Arnaud
 * @last-edited 05/09/2015
 * @description Register
 *
 ******************************************************************************/

use App\Jobs\Mails\AccountCreated;
use App\Libraries\Acl\Repositories\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class Register extends Controller
{

    /**
     * @var User
     */
    protected $userRepository;

    /**
     * Auth constructor.
     *
     * @param $userRepository
     */
    public function __construct(User $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function registerForm()
    {
        if (!\Auth::guest()) {
            return redirect('/');
        }

        return view('auth.register');
    }

    public function register(Request $request)
    {
        $rules = $this->userRepository->getModel()->getRules();
        $rules['password'] .= '|confirmed';

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {

            return redirect(route('auth.registerForm'))
                ->withErrors($validator)
                ->withInput();
        }

        $inputs             = $request->all();
        $inputs['password'] = \Hash::make($inputs['password']);

        $user = $this->userRepository->create(new \App\User($inputs), false);
        $this->dispatch(new AccountCreated($user));

        $request->session()->flash('success', 'auth.account_created');

        return redirect(route('auth.login', ['username' => $user->username]));
    }
}
