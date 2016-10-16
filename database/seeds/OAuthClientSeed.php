<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Config;

class OAuthClientSeed extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $repository = \App::make(\LucaDegasperi\OAuth2Server\Storage\FluentClient::class);

        if (\App::environment() !== 'production') {
            $repository->create('versusmind dev', 'versusmind', 'versusmind');
        }


        $repository->create('web client', Config::get('oauth2.web_client.client_id'), Config::get('oauth2.web_client.client_secret'));
        $repository->create('app client', Config::get('oauth2.app_client.client_id'), Config::get('oauth2.app_client.client_secret'));
    }
}
