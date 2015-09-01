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


use App\Facades\Acl;
use Closure;
use Illuminate\Support\Facades\Auth;

class AclMiddleware
{

    /**
     * @param          $request
     * @param \Closure $next
     * @param array    $permissions
     *
     * @see http://laravel.com/docs/5.1/middleware#middleware-parameters
     *
     * @return \Illuminate\Http\RedirectResponse|\Laravel\Lumen\Http\Redirector
     */
    public function handle($request, Closure $next, $permissions = [])
    {

        if(!is_array($permissions)) {
            $permissions = [$permissions];
        }

        // no permission required
        if(empty($permissions)) {
            return $next($request);
        }

        foreach($permissions as $permission) {
            if(!Acl::isUserAllow(Auth::user(), $permission)) {
                // @todo redirect to a specific page
                return redirect('/bite');
            }
        }

        return $next($request);
    }
}
