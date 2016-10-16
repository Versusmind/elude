<?php namespace App\Libraries\Acl\Repositories;

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
 * @file Permission.php
 * @author LAHAXE Arnaud
 * @last-edited 05/09/2015
 * @description Permission
 *
 ******************************************************************************/

use App\Libraries\Repository;
use Illuminate\Support\Collection;

class Permission extends Repository
{

    /**
     * Permission constructor.
     */
    public function __construct()
    {
        parent::__construct(\App\Permission::class);
    }


    public function getPermissionsByStringRepresentation(array $stringPermissions)
    {
        if (empty($stringPermissions)) {
            return new Collection();
        }


        $query = $this->makeQuery()
            // must always be false
            ->where('id', '=', 0);

        foreach($stringPermissions as $stringPermission) {
            list ($area, $permission) = explode('.', $stringPermission);
            $query->orWhere(function($query) use ($area, $permission){
                $query->where('area', '=', $area)
                    ->where('permission', '=', $permission);
            });
        }

        return $query->get();
    }
}