<?php

/**
 * Class GrantPasswordTest
 *
 *
 *
 * @author LAHAXE Arnaud
 * @group critical
 */
class GrantPasswordTest extends TestCase
{
    public function testLogin()
    {

        $this->call('POST', '/api/v1/oauth/access_token', [
            'username' => 'user',
            'password' => 'user',
            'grant_type' => 'password',
            'client_id' => 'versusmind',
            'client_secret' => 'versusmind'
        ]);
        $this->seeJson([]);
        $this->seeStatusCode(200);
        $this->assertMatchPattern([
            "access_token" => self::STRING,
            "refresh_token" => self::STRING,
            "token_type" => "Bearer",
            "expires_in" => 3600
        ]);
    }


    public function testLoginKo()
    {
        $this->call('POST', '/api/v1/oauth/access_token', [
            'username' => 'jerry',
            'password' => 'khan',
            'grant_type' => 'password',
            'client_id' => 'versusmind',
            'client_secret' => 'versusmind'
        ]);
        $this->seeJson([]);
        $this->seeStatusCode(401);
    }
}