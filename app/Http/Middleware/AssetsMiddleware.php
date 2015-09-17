<?php namespace App\Http\Middleware;

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
 * @file AssetsMiddleware.php
 * @author LAHAXE Arnaud
 * @last-edited 18/08/15
 * @description AssetsMiddleware
 *
 ******************************************************************************/


use App\Libraries\Assets\Collection;
use App\Libraries\Assets\Orchestrator;
use Closure;

class AssetsMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     *
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        /** @var Orchestrator $ocherstator */
        $ocherstator = \App::make('App\Libraries\Assets\Orchestrator');

        foreach (array_keys(config('assets.groups')) as $groupname) {
            $collection = \App\Libraries\Assets\Collection::createByGroup($groupname);

            try {
                $ocherstator->build($collection, array_diff(Collection::$types, Collection::$staticType));
            } catch (\Exception $e) {
                \Log::error($e->getMessage());
            }
        }

        return $next($request);
    }
}
