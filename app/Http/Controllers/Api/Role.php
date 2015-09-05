<?php namespace App\Http\Controllers\Api;

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
 * @file Role.php
 * @author LAHAXE Arnaud
 * @last-edited 05/09/2015
 * @description Role
 *
 ******************************************************************************/


class Role extends PermissionAware
{

    /**
     * Role constructor.
     */
    public function __construct(\App\Libraries\Acl\Repositories\Role $repository, \App\Libraries\Acl\Repositories\Permission $permissionRepository)
    {
        parent::__construct($repository, $permissionRepository);
    }
}