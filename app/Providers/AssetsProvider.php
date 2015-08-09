<?php namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AssetsProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('assets', function ($app) {
            return new \App\Libraries\Assets\Orchestrator;
        });


        $this->app->configure('assets');
    }
}
