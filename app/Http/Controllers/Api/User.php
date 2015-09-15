<?php namespace App\Http\Controllers\Api;

use App\Libraries\Acl\Exceptions\ModelNotValid;
use Illuminate\Support\Facades\Input;
use Symfony\Component\HttpFoundation\Response;

/******************************************************************************
 *
 * @package     Myo 2
 * @copyright   © 2015 by Versusmind.
 * All rights reserved. No part of this document may be
 * reproduced or transmitted in any form or by any means,
 * electronic, mechanical, photocopying, recording, or
 * otherwise, without prior written permission of Versusmind.
 * @link        http://www.versusmind.eu/
 *
 * @file        User.php
 * @author      LAHAXE Arnaud
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

    /**
     * @author LAHAXE Arnaud
     *
     * @param $id
     * @param $idGroup
     *
     * @return mixed
     *
     * @apiGroup Users
     * @apiName updateGroup
     * @api      {post} /users/:id/group/:iGroup Update group for an user
     *
     * @apiUse ApiNotFound
     * @apiUse NotAuthorized
     *
     * @apiParam {Number} id Model unique ID.
     * @apiParam {Number} iGroup Group unique ID.
     * @apiSuccess (201) {Number} id Id.
     * @apiSuccess (201) {datetime} created_at Creation date.
     * @apiSuccess (201) {datetime} updated_at Last Update date.
     * @apiSuccess (201) {String} email Email
     * @apiSuccess (201) {String} username Username
     * @apiSuccess (201) {Number} group_id User group id
     */
    public function groupUpdate($id, $idGroup)
    {
        $this->addUserCriteria();
        $model = $this->repository->find($id);
        $group = $this->groupRepository->find($idGroup);

        if (is_null($model) || is_null($group)) {
            return response()->json([], 404);
        }

        $this->repository->setGroup($model, $group);

        return response()->json($model, 202);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     *
     * @apiVersion 1.0.0
     * @api      {post} /users Create a user
     * @apiGroup Users
     * @apiUse   postStore
     * @apiSuccess (201) {Number} id Id.
     * @apiSuccess (201) {datetime} created_at Creation date.
     * @apiSuccess (201) {datetime} updated_at Last Update date.
     * @apiSuccess (201) {String} email Email
     * @apiSuccess (201) {String} username Username
     * @apiSuccess (201) {Number} group_id User group id
     * @apiUse   userParams
     * @apiUse   ApiOAuth
     */
    public function store()
    {
        try {
            $class = $this->repository->getModelClass();
            $model = new $class(Input::all());

            $model = $this->repository->create($model);
        } catch (ModelNotValid $e) {
            return response()->json($e->getErrors(), 400);
        }

        return response()->json($model, 201);
    }

    /**
     * @apiDefine userParams
     *
     * @apiParam {String} email Email
     * @apiParam {String} username Username
     * @apiParam {String} password Password
     */

    /**
     * @api      {get} /users Request user list
     * @apiGroup Users
     * @apiUse   getIndex
     * @apiUse   paginationParams
     * @apiUse   ApiOAuth
     */

    /**
     * @api      {get} /users/:id Request user information
     * @apiGroup Users
     * @apiUse   get
     * @apiSuccess (200) {Number} id Id.
     * @apiSuccess (200) {datetime} created_at Creation date.
     * @apiSuccess (200) {datetime} updated_at Last Update date.
     * @apiSuccess (200) {String} email Email
     * @apiSuccess (200) {String} username Username
     * @apiSuccess (200) {Number} group_id User group id
     * @apiUse   ApiOAuth
     */

    /**
     * @api      {put} /users/:id Update a user
     * @apiGroup Users
     * @apiUse   putUpdate
     * @apiSuccess (202) {Number} id Id.
     * @apiSuccess (202) {datetime} created_at Creation date.
     * @apiSuccess (202) {datetime} updated_at Last Update date.
     * @apiSuccess (202) {String} email Email
     * @apiSuccess (202) {String} username Username
     * @apiSuccess (202) {Number} group_id User group id
     * @apiParam {Number} id Id.
     * @apiUse   userParams
     * @apiUse   ApiOAuth
     */

    /**
     * @api      {delete} /users/:id Delete a user
     * @apiGroup Users
     * @apiUse   deleteDestroy
     * @apiUse   ApiOAuth
     * @apiParam {Number} id Id.
     */

    /**
     * @api      {delete} users/:id/permissions/:idPermission Remove a permission from a user
     * @apiGroup Users
     * @apiUse   deletePermission
     * @apiUse   ApiOAuth
     * @apiParam {Number} id Id.
     * @apiSuccess (202) {Number} id Id.
     * @apiSuccess (202) {datetime} created_at Creation date.
     * @apiSuccess (202) {datetime} updated_at Last Update date.
     * @apiSuccess (202) {String} email Email
     * @apiSuccess (202) {String} username Username
     * @apiSuccess (202) {Number} group_id User group id
     */

    /**
     * @api      {post} users/:id/permissions/:idPermission Add a permission to a user
     * @apiGroup Users
     * @apiUse   postPermission
     * @apiUse   ApiOAuth
     * @apiParam {Number} id Id.
     * @apiSuccess (202) {Number} id Id.
     * @apiSuccess (202) {datetime} created_at Creation date.
     * @apiSuccess (202) {datetime} updated_at Last Update date.
     * @apiSuccess (202) {String} email Email
     * @apiSuccess (202) {String} username Username
     * @apiSuccess (202) {Number} group_id User group id
     */

    /**
     * @api      {delete} users/:id/roles/:idRole Remove a role from a user
     * @apiGroup Users
     * @apiUse   deleteRole
     * @apiUse   ApiOAuth
     * @apiParam {Number} id Id.
     * @apiSuccess (202) {Number} id Id.
     * @apiSuccess (202) {datetime} created_at Creation date.
     * @apiSuccess (202) {datetime} updated_at Last Update date.
     * @apiSuccess (202) {String} email Email
     * @apiSuccess (202) {String} username Username
     * @apiSuccess (202) {Number} group_id User group id
     */

    /**
     * @api      {post} users/:id/roles/:idRole Add a role to a user
     * @apiGroup Users
     * @apiUse   postRole
     * @apiUse   ApiOAuth
     * @apiParam {Number} id Id.
     * @apiSuccess (202) {Number} id Id.
     * @apiSuccess (202) {datetime} created_at Creation date.
     * @apiSuccess (202) {datetime} updated_at Last Update date.
     * @apiSuccess (202) {String} email Email
     * @apiSuccess (202) {String} username Username
     * @apiSuccess (202) {Number} group_id User group id
     */
}