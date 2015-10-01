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
 * @file AclProvider.php
 * @author LAHAXE Arnaud
 * @last-edited 18/08/15
 * @description AclProvider
 *
 ******************************************************************************/

use Illuminate\Support\ServiceProvider;

class AclProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('acl', function ($app) {
            return $app->make(\App\Libraries\Acl\Acl::class);
        });

        if (!class_exists('Acl')) {
            class_alias('App\Facades\Acl', 'Acl');
        }
    }
}
