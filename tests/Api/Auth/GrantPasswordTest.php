<?php namespace Tests\Api\Auth;

use Tests\TestCase;

/**
 * Class GrantPasswordTest
 *
 *
 *
 * @package Tests\Api\Auth
 * @author  LAHAXE Arnaud
 * @group api
 */
class GrantPasswordTest extends TestCase
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
}