<?php

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
            ->see('Welcome user');
        $this->assertSessionHas('oauth');
    }

    public function testInvalidLogin()
    {
        $this->visit('/')
            ->seePageIs('/auth/login')
            ->type('dummy', 'username')
            ->type('dummy', 'password')
            ->press('Sign In')
            ->seePageIs('/auth/login?' . http_build_query(['error' => 1]))
            ->see("Nom d'utilisateur ou mot de passe incorrect");
    }
}