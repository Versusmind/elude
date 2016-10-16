<?php namespace Tests\Web\Auth;

use Tests\TestCase;

/**
 * Class LoginTest
 *
 *
 *
 * @author LAHAXE Arnaud
 * @group critical
 */
class LoginTest extends TestCase
{

    public function testValidLogin()
    {
        $this->visit('/')
            ->seePageIs('/auth/login')
            ->type('user', 'username')
            ->type('user', 'password')
            ->press('Sign In')
            ->seePageIs('/')
            ->see('<div ui-view></div>');
        $this->assertSessionHas('oauth');

        return $this;
    }


    public function testLogout()
    {
        $this->testValidLogin()
            ->visit('/auth/logout')
            ->seePageIs('/auth/login')
            ->see('Sign In');
    }


    public function testInvalidLogin()
    {
        $this->visit('/')
            ->seePageIs('/auth/login')
            ->type('dummy', 'username')
            ->type('dummy', 'password')
            ->press('Sign In')
            ->seePageIs('/auth/login')
            ->see("Nom d'utilisateur ou mot de passe incorrect");
    }
}