<?php namespace Tests\Web\Auth;

use App\User;
use Illuminate\Support\Facades\Crypt;
use Tests\TestCase;

/**
 * Class LostPasswordTest
 *
 *
 *
 * @author LAHAXE Arnaud
 * @group critical
 */
class LostPasswordTest extends TestCase
{

    public function testValidLostPassword()
    {
        $this->visit('/auth/lost-password')
            ->type('user', 'username')
            ->press('Lost password')
            ->seePageIs('/auth/login')
            ->see('Un email contenant');

        $user = User::where('username', 'user')->first();

        $this->assertNotNull($user->lost_password_token);
        $this->assertNotNull($user->lost_password_token_created_at);

        $cryptToken = Crypt::encrypt($user->lost_password_token);

        $this->visit('/auth/change-lost-password?' . http_build_query([
                'username' => base64_encode($user->username),
                'token' => $cryptToken
            ]))
            ->type($user->username, 'username')
            ->type('user123456', 'password')
            ->type('user123456', 'password_confirmation')
            ->press('Change password')
            ->seePageIs('/auth/login')
            ->see('Votre nouveau mot');

        $this->visit('/auth/login')
            ->type($user->username, 'username')
            ->type('user123456', 'password')
            ->press('Sign In')
            ->seePageIs('/')
            ->see('Welcome ' . $user->username);
        $this->assertSessionHas('oauth');
    }
}