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
 * @file GrantableRepository.php
 * @author LAHAXE Arnaud
 * @last-edited 05/09/2015
 * @description GrantableRepository
 *
 ******************************************************************************/


use App\Libraries\Acl\Interfaces\PermissionInterface;
use App\Libraries\Repository;
use App\Libraries\Acl\Interfaces\GrantableInterface;

abstract class GrantableRepository extends Repository
{

    /**
     * Permission constructor.
     */
    public function __construct($className)
    {
        parent::__construct($className);
    }


    /**
     * @param GrantableInterface $model
     * @param PermissionInterface $permission
     * @return $this
     */
    public function addPermission(GrantableInterface $model, PermissionInterface $permission)
    {
        if($this->hasPermission($model, $permission)) {
            return $this;
        }

        $model->permissions()->attach($permission);
        $model->load('permissions');

        return $this;
    }

    /**
     * @param GrantableInterface $model
     * @param PermissionInterface $permission
     * @return $this
     */
    public function removePermission(GrantableInterface $model, PermissionInterface $permission)
    {
        if(!$this->hasPermission($model, $permission)) {
            return $this;
        }

        $model->permissions()->detach($permission);
        $model->load('permissions');

        return $this;
    }

    /**
     * @param GrantableInterface $model
     * @param PermissionInterface $permission
     * @return mixed
     */
    public function hasPermission(GrantableInterface $model, PermissionInterface $permission)
    {
        return $model->permissions->contains($permission->id);
    }
}