<?php namespace App\Http\Controllers\Api;

use League\OAuth2\Server\Exception\InvalidCredentialsException;

class Auth extends ApiController
{

    public function login()
    {
        try {
            return response()->json(\Authorizer::issueAccessToken());
        } catch (InvalidCredentialsException $e) {
            return response()->json([], 401);
        }
    }
}
