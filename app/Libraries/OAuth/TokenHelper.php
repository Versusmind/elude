<?php namespace App\Libraries\OAuth;

use Illuminate\Support\Facades\DB;

class TokenHelper
{

    /**
     * @param $accessToken
     * @return mixed
     */
    public function deleteTokens($accessToken)
    {
        DB::table('oauth_refresh_tokens')
            ->where('oauth_refresh_tokens.access_token_id', $accessToken)
            ->delete();

        DB::table('oauth_access_tokens')
            ->where('oauth_access_tokens.id', $accessToken)
            ->delete();
    }


}