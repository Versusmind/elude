<?php namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

class Auth extends Controller
{
    public function login()
    {
        return response()->json(\Authorizer::issueAccessToken());
    }
}
