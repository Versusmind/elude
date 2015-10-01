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
 * @file RoleAware.php
 * @author LAHAXE Arnaud
 * @last-edited 05/09/2015
 * @description RoleAware
 *
 ******************************************************************************/

use App\Libraries\Acl\Repositories\Permission;
use App\Libraries\Acl\Repositories\Role;

abstract class RoleAware extends PermissionAware
{
    /**
     * @var Role
     */
    protected $roleRepository;

    /**
     * @param \App\Libraries\Repository $repository
     * @param Permission $permissionRepository
     */
    public function __construct(\App\Libraries\Repository $repository, Permission $permissionRepository, Role $roleRepository)
    {
        $this->roleRepository = $roleRepository;

        parent::__construct($repository, $permissionRepository);
    }

    /**
     * @author LAHAXE Arnaud
     *
     * @param $id
     * @param $idRole
     *
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @apiDefine postRole
     * @apiName createRole
     * @apiErrorExample {json} Error-Response:
     *     HTTP/1.1 404 Not Found
     *     {}
     *
     * @apiErrorExample {json} Error-Response:
     *     HTTP/1.1 401 Not Authorized
     *     {}
     *
     * @apiName show
     * @apiParam {Number} id Model unique ID.
     * @apiParam {Number} idRole Role unique ID.
     */
    public function roleStore($id, $idRole)
    {
        $this->addUserCriteria();
        $model = $this->repository->find($id);
        $role = $this->roleRepository->find($idRole);

        if(is_null($model) || is_null($role)) {
            return response()->json([], 404);
        }

        $this->repository->addRole($model, $role);

        return response()->json($model, 202);
    }

    /**
     * @author LAHAXE Arnaud
     *
     * @param $id
     * @param $idRole
     *
     * @return \Symfony\Component\HttpFoundation\Response
     *
     *
     * @apiDefine deleteRole
     * @apiName destroyRole
     * @apiErrorExample {json} Error-Response:
     *     HTTP/1.1 404 Not Found
     *     {}
     *
     * @apiErrorExample {json} Error-Response:
     *     HTTP/1.1 401 Not Authorized
     *     {}
     *
     * @apiName show
     * @apiParam {Number} id Model unique ID.
     * @apiParam {Number} idRole Role unique ID.
     */
    public function roleDestroy($id, $idRole)
    {
        $this->addUserCriteria();
        $model = $this->repository->find($id);
        $role = $this->roleRepository->find($idRole);

        if(is_null($model) || is_null($role)) {
            return response()->json([], 404);
        }
        $this->repository->removeRole($model, $role);

        return response()->json($model, 202);
    }
}