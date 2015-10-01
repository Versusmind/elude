<?php namespace Tests\Api\Auth;

use Tests\Api\ApiCase;

/**
 * @package Tests\Api\Auth
 * @author  LAHAXE Arnaud
 * @group api
 */
class GrantPasswordTest extends ApiCase
{

    public function testLogin()
    {
        $this->login();
        $this->assertMatchPattern([
            "access_token"  => self::STRING,
            "refresh_token" => self::STRING,
            "token_type"    => "Bearer",
            "expires_in"    => 3600
        ]);
    }

    public function testLoginKo()
    {
        $this->login('jerry', 'khan', 401);
    }

    public function testLogout()
    {
        $this->login();

        // successfull logout
        $this->call('POST', '/api/v1/oauth/logout');
        $this->seeJson([]);
        $this->seeStatusCode(202);

        // can't logout because already logout
        $this->call('POST', '/api/v1/oauth/logout');
        $this->seeJson([]);
        $this->seeStatusCode(401);
    }
}