<?php namespace Tests\Web\Auth;

use Tests\TestCase;

/**
 * Class RegisterTest
 *
 *
 *
 * @author LAHAXE Arnaud
 * @group critical
 */
class RegisterTest extends TestCase
{

    public function testValidLogin()
    {
        $username = \Faker\Factory::create()->userName;
        $this->visit('/auth/register')
            ->type($username, 'username')
            ->type(\Faker\Factory::create()->safeEmail, 'email')
            ->type('user123456', 'password')
            ->type('user123456', 'password_confirm')
            ->press('Register')
            ->seePageIs('/auth/login')
            ->see('Votre compte est');

        $this->visit('/auth/login')
            ->type($username, 'username')
            ->type('user123456', 'password')
            ->press('Sign In')
            ->seePageIs('/')
            ->see('Welcome user');
        $this->assertSessionHas('oauth');
    }
}