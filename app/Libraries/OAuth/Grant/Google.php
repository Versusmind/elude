<?php namespace App\Libraries\OAuth\Grant;

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use League\OAuth2\Client\Provider\GoogleUser;
use League\OAuth2\Server\Grant\PasswordGrant;


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
 * @file Google.php
 * @author LAHAXE Arnaud
 * @last-edited 18/08/15
 * @description Email/Google user token authentication for OAuth server
 *
 ******************************************************************************/

class Google extends PasswordGrant
{

    protected $identifier = 'google';


    /**
     * Return the callback function
     *
     * @return callable
     *
     * @throws
     */
    protected function getVerifyCredentialsCallback()
    {

        return [$this, 'verify'];
    }

    /**
     * This verify try to check that an email match with a valid token
     * @param $email
     * @param $token
     * @return bool
     */
    public function verify($email, $token)
    {
        $provider = new \League\OAuth2\Client\Provider\Google([
            'clientId'     => env('GOOGLE_OAUTH_CLIENT_ID'),
            'clientSecret' => env('GOOGLE_OAUTH_CLIENT_SECRET'),
            'redirectUri'  => env('GOOGLE_OAUTH_REDIRECT_URI'),
            'hostedDomain' => env('GOOGLE_OAUTH_DOMAIN'),
        ]);


        $token = new \League\OAuth2\Client\Token\AccessToken([
            'access_token' => $token
        ]);

        /** @var GoogleUser $ownerDetails */
        $ownerDetails = $provider->getResourceOwner($token);

        if (!$ownerDetails->getEmail() === $email) {
            return false;
        }

        $user = App::make(\App\Libraries\Acl\Repositories\User::class)->getByEmail($email);

        Auth::loginUsingId($user->id);

        return $user->id;
    }

}
