<?php namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use League\OAuth2\Server\Exception\InvalidCredentialsException;

class Auth extends Controller
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
