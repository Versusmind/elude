<?php use Illuminate\Database\Seeder;

class OAuthClientSeed extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App::make(\LucaDegasperi\OAuth2Server\Storage\FluentClient::class)
            ->create('versusmind dev', 'versusmind', 'versusmind');
    }
}
