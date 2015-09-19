<?php namespace App\Http\Controllers\Api;

use App\Libraries\OAuth\RefreshTokenHelper;
use App\Libraries\OAuth\TokenHelper;
use Illuminate\Support\Facades\Input;
use League\OAuth2\Server\Exception\InvalidCredentialsException;
use LucaDegasperi\OAuth2Server\Storage\FluentAccessToken;

class Auth extends ApiController
{

    /**
     * @author LAHAXE Arnaud
     *
     * @apiGroup Auth
     * @apiName login
     * @api      {post} /oauth/access_token Authenticate user
     *
     * @apiParam {String} username Username.
     * @apiParam {String} password Password.
     * @apiParam {String} grant_type Grant type (password).
     * @apiParam {String} client_id Client id.
     * @apiParam {String} client_secret Client secret.
     *
     * @apiUse NotAuthorized
     *
     * @apiSuccess (200) {String} access_token
     * @apiSuccess (200) {String} token_type
     * @apiSuccess (200) {Datetime} expires_in
     * @apiSuccess (200) {String} refresh_token
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function login()
    {
        try {
            return response()->json(\Authorizer::issueAccessToken());
        } catch (InvalidCredentialsException $e) {
            return response()->json([], 401);
        }
    }

    /**
     * @author LAHAXE Arnaud
     *
     * @apiGroup Auth
     * @apiName logout
     * @api      {post} /oauth/logout Logout
     *
     * @return \Illuminate\Http\RedirectResponse|\Laravel\Lumen\Http\Redirector
     */
    public function logout()
    {
        /** @var TokenHelper $tokenHelper */
        $tokenHelper = \App::make(TokenHelper::class);
        $tokenHelper->deleteTokens(\Authorizer::getChecker()->getAccessToken()->getId());

        // reset session if some dev use session in a rest api :D
        \Session::flush();

        return response()->json([], 202);
    }
}
