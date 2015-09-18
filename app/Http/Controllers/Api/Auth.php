<?php namespace App\Http\Controllers\Api;

use Illuminate\Support\Facades\Input;
use League\OAuth2\Server\Exception\InvalidCredentialsException;
use LucaDegasperi\OAuth2Server\Storage\FluentAccessToken;
use LucaDegasperi\OAuth2Server\Storage\FluentRefreshToken;

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
        dump(\Auth::user());
        dump(\Session::all());
        return;
        // get access token
        /** @var FluentAccessToken $accessTokenRepository */
        $accessTokenRepository = \App::make(FluentAccessToken::class);
        $entity = $accessTokenRepository->get(Input::get('access_token'));

        // delete the token
        $accessTokenRepository->delete($entity);

        // get the refresh token
        /** @var FluentRefreshToken $refreshTokenRepository */
        $refreshTokenRepository = \App::make(FluentRefreshToken::class);



        // reset session
        \Session::flush();

        return response()->json([], 202);
    }
}
