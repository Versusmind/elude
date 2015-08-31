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
 * @file AclMiddleware.php
 * @author LAHAXE Arnaud
 * @last-edited 18/08/15
 * @description AclMiddleware
 *
 ******************************************************************************/


use Closure;

class DebugMiddleware
{

    protected $nbSqlQueries;

    /**
     * @param          $request
     * @param \Closure $next
     * @param array    $permissions
     *
     * @see http://laravel.com/docs/5.1/middleware#middleware-parameters
     *
     * @return \Illuminate\Http\RedirectResponse|\Laravel\Lumen\Http\Redirector
     */
    public function handle($request, Closure $next)
    {

        \DB::listen(function($sql, $bindings, $time)  {

            if($time >50) {
                \Log::warning('Log query Query=' . $sql . ' data= ' . json_encode($bindings) . ' time=' . $time . 'ms');
            }
            $this->nbSqlQueries ++;
        });

        $response = $next($request);

        if($this->nbSqlQueries > 10) {
            \Log::error('Nb sql queries: ' . $this->nbSqlQueries);
        }

        return $response;
    }
}
