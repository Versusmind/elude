<?php namespace App\Libraries\Acl\Manager;

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

use App\Libraries\Acl\PermissionResolver;
use App\Libraries\Acl\Repositories\Role as RoleRepository;

class Role extends Manager
{

    /**
     * @param PermissionResolver $resolver
     * @param RoleRepository     $repository
     */
    public function __construct(PermissionResolver $resolver, RoleRepository $repository)
    {
        $this->repository = $repository;
        parent::__construct($resolver);
    }
}