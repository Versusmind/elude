<?php namespace App\Http\Controllers\OAuth;


use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Session;
use League\OAuth2\Client\Provider\GoogleUser;
use LucaDegasperi\OAuth2Server\Facades\Authorizer;

class Google extends Controller
{

    /**
     * @var \League\OAuth2\Client\Provider\Google
     */
    protected $provider;

    /**
     * @var \App\Libraries\Acl\Repositories\User
     */
    protected $repository;

    /**
     * Google constructor.
     * @param \App\Libraries\Acl\Repositories\User $repository
     */
    public function __construct(\App\Libraries\Acl\Repositories\User $repository)
    {
        $this->provider = new \League\OAuth2\Client\Provider\Google([
            'clientId'     => env('GOOGLE_OAUTH_CLIENT_ID'),
            'clientSecret' => env('GOOGLE_OAUTH_CLIENT_SECRET'),
            'redirectUri'  => env('GOOGLE_OAUTH_REDIRECT_URI'),
            'hostedDomain' => env('GOOGLE_OAUTH_DOMAIN'),
        ]);

        $this->repository = $repository;
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Laravel\Lumen\Http\Redirector
     */
    public function connect(Request $request)
    {
        $authUrl = $this->provider->getAuthorizationUrl();
        Session::put('google.oauth2state', $this->provider->getState());

        return redirect($authUrl);
    }


    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Laravel\Lumen\Http\Redirector
     */
    public function callback(Request $request)
    {
        $state = $request->get('state');
        $sessionState = Session::get('google.oauth2state');
        $code = $request->get('code');

        if ($request->get('error')) {
            $request->session()->flash('error', 'Une erreur est survenue');

            return redirect(route('auth.loginForm'));
        }

        if (empty($state) || $state !== $sessionState) {
            Session::forget('google.oauth2state');
            $request->session()->flash('error', 'Une erreur est survenue');

            return redirect(route('auth.loginForm'));
        }

        $token = $this->provider->getAccessToken('authorization_code', [
            'code' => $code
        ]);

        try {

            /** @var GoogleUser $ownerDetails */
            $ownerDetails = $this->provider->getResourceOwner($token);

            $email  = $ownerDetails->getEmail();

            // if we already have the email in DB we log the user
            if (!$this->repository->exists(['email' => $email])) {
                $lastName = $ownerDetails->getLastName();
                $firstName = $ownerDetails->getFirstName();
                $this->createUser($firstName, $lastName, $email);
            }

            // set default web oauth client
            Input::merge(['client_id' => Config::get('oauth2.web_client.client_id')]);
            Input::merge(['client_secret' => Config::get('oauth2.web_client.client_secret')]);
            Input::merge(['grant_type' => 'google']);
            Input::merge(['username' => $email]);
            Input::merge(['password' => $token->getToken()]);

            try {
                $oauth = Authorizer::issueAccessToken();

                // save oauth token and access token in session
                Session::put('oauth', $oauth);

                return redirect('/');
            } catch (\Exception $e) {

                $request->session()->flash('error', 'auth.login_error');

                return redirect(route('auth.loginForm'));
            }

        } catch (\Exception $e) {

            $request->session()->flash('error', 'Une erreur est survenue');

            return redirect(route('auth.loginForm'));
        }
    }

    /**
     * @param $firstName
     * @param $lastName
     * @param $email
     * @return \Illuminate\Database\Eloquent\Model
     */
    protected function createUser($firstName, $lastName, $email)
    {
        $user = new User();

        // generate an ldap like name
        $initialUsername = $username = strtolower(substr($firstName, 0, 1) . str_replace([' ', '-', '', ], '', $lastName));

        // handle name conflicts
        $i = 1;
        while($this->repository->exists(['username' => $username])) {
            $username = $initialUsername . $i;
            $i++;
        }

        $user->username = $username;
        $user->email = $email;

        // fake random password
        $user->password = Hash::make(str_random(30));

        return $this->repository->create($user);
    }
}