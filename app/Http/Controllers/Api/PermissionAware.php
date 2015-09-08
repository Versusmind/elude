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
 * @file PermissionAware.php
 * @author LAHAXE Arnaud
 * @last-edited 05/09/2015
 * @description PermissionAware
 *
 ******************************************************************************/


use App\Libraries\Acl\Repositories\Permission;

abstract class PermissionAware extends ResourcesController
{

    /**
     * @var Permission
     */
    protected $permissionRepository;

    /**
     * @param \App\Libraries\Repository $repository
     * @param Permission $permissionRepository
     */
    public function __construct(\App\Libraries\Repository $repository, Permission $permissionRepository)
    {
        $this->permissionRepository = $permissionRepository;

        parent::__construct($repository);
    }

    public function permissionStore($id, $idPermission)
    {
        $this->addUserCriteria();
        $model = $this->repository->find($id);
        $permission = $this->permissionRepository->find($idPermission);

        if(is_null($model) || is_null($permission)) {
            return response()->json([], 404);
        }

        $this->repository->addPermission($model, $permission);

        return response()->json($model, 204);
    }

    public function permissionDestroy($id, $idPermission)
    {
        $this->addUserCriteria();
        $model = $this->repository->find($id);
        $permission = $this->permissionRepository->find($idPermission);

        if(is_null($model) || is_null($permission)) {
            return response()->json([], 404);
        }

        $this->repository->removePermission($model, $permission);

        return response()->json($model, 204);
    }
}