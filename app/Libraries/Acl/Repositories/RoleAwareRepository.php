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
 * @file RoleAwareRepository.php
 * @author LAHAXE Arnaud
 * @last-edited 05/09/2015
 * @description RoleAwareRepository
 *
 ******************************************************************************/


use App\Libraries\Acl\Interfaces\GrantableInterface;
use App\Libraries\Acl\Interfaces\RoleAwareInterface;

abstract class RoleAwareRepository extends GrantableRepository implements RoleAwareInterface
{

    /**
     * Permission constructor.
     */
    public function __construct($className)
    {
        parent::__construct($className);
    }

    /**
     * @param GrantableInterface $grantable
     * @param \App\Role $role
     * @return $this
     */
    public function addRole(GrantableInterface $grantable, \App\Role $role)
    {
        if($this->hasRole($grantable, $role)) {
            return $this;
        }

        $grantable->roles()->attach($role);
        $grantable->load('roles');

        return $this;
    }

    /**
     * @param GrantableInterface $grantable
     * @param \App\Role $role
     * @return $this
     */
    public function removeRole(GrantableInterface $grantable, \App\Role $role)
    {
        if(!$this->hasRole($grantable, $role)) {
            return $this;
        }

        $grantable->roles()->detach($role);
        $grantable->load('roles');

        return $this;
    }

    /**
     * @param GrantableInterface $grantable
     * @param \App\Role $role
     * @return mixed
     */
    public function hasRole(GrantableInterface $grantable, \App\Role $role)
    {
        return $grantable->roles->contains($role->id);
    }
}