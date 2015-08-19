<?php namespace App\Providers;

/******************************************************************************
 *
 * @package Myo 2
 * @copyright © 2015 by Versusmind.
 * All rights reserved. No part of this document may be
 * reproduced or transmitted in any form or by any means,
 * electronic, mechanical, photocopying, recording, or
 * otherwise, without prior written permission of Versusmind.
 * @link http://www.versusmind.eu/
 *
 * @file AssetsProvider.php
 * @author LAHAXE Arnaud
 * @last-edited 18/08/15
 * @description AssetsProvider
 *
 ******************************************************************************/

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

        if ($this->app->environment() !== 'production') {
            $this->app->middleware([
                \App\Http\Middleware\AssetsMiddleware::class
            ]);
        }

        if(!class_exists('Assets')) {
            class_alias('App\Facades\Assets', 'Assets');
        }
    }
}
