<?php  namespace App\Http\Controllers\Api;

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
 * @file Group.php
 * @author LAHAXE Arnaud
 * @last-edited 05/09/2015
 * @description Group
 *
 ******************************************************************************/

use App\Libraries\Acl\Repositories\Permission;
use App\Libraries\Acl\Repositories\Role;

class Group extends RoleAware
{
    /**
     * Group constructor.
     */
    public function __construct(\App\Libraries\Acl\Repositories\Group $repository, Role $roleRepository, Permission $permissionRepository)
    {
        parent::__construct($repository, $permissionRepository, $roleRepository);
    }

}