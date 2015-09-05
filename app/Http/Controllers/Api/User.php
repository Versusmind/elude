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
 * @file User.php
 * @author LAHAXE Arnaud
 * @last-edited 05/09/2015
 * @description User
 *
 ******************************************************************************/

class User extends RoleAware
{

    protected $groupRepository;

    /**
     * User constructor.
     */
    public function __construct(\App\Libraries\Acl\Repositories\User $repository, \App\Libraries\Acl\Repositories\Role $roleRepository, \App\Libraries\Acl\Repositories\Permission $permissionRepository, \App\Libraries\Acl\Repositories\Group $groupRepository)
    {
        $this->groupRepository = $groupRepository;

        parent::__construct($repository, $permissionRepository, $roleRepository);
    }

    public function groupUpdate($id, $idGroup)
    {
        $model = $this->repository->find($id);
        $group = $this->groupRepository->find($idGroup);

        if (is_null($model) || is_null($group)) {
            return response()->json([], 404);
        }

        if(!$this->isAllowModel($model)) {
            return response()->json([], 403);
        }

        $this->repository->setGroup($model, $group);

        return response()->json($model, 204);
    }
}