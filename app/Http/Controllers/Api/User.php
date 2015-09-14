<?php namespace App\Http\Controllers\Api;
use App\Libraries\Acl\Exceptions\ModelNotValid;
use Illuminate\Support\Facades\Input;
use Symfony\Component\HttpFoundation\Response;

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
        $this->addUserCriteria();
        $model = $this->repository->find($id);
        $group = $this->groupRepository->find($idGroup);

        if (is_null($model) || is_null($group)) {
            return response()->json([], 404);
        }

        $this->repository->setGroup($model, $group);

        return response()->json($model, 204);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
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
     * @api {get} /user/:id Request User information
     * @apiName GetUser
     * @apiGroup User
     *
     * @apiParam {Number} id Users unique ID.
     *
     * @apiSuccess {String} firstname Firstname of the User.
     * @apiSuccess {String} lastname  Lastname of the User.
     *
     * @apiSuccessExample Success-Response:
     *     HTTP/1.1 200 OK
     *     {
     *       "firstname": "John",
     *       "lastname": "Doe"
     *     }
     *
     * @apiError UserNotFound The id of the User was not found.
     *
     * @apiErrorExample Error-Response:
     *     HTTP/1.1 404 Not Found
     *     {
     *       "error": "UserNotFound"
     *     }
     */
}