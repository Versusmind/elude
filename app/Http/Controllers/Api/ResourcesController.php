<?php namespace App\Http\Controllers\Api;

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
 * @file        ResourcesController.php
 * @author      LAHAXE Arnaud
 * @last-edited 05/09/2015
 * @description ResourcesController
 *
 ******************************************************************************/

use App\Libraries\Acl\Exceptions\ModelNotValid;
use App\Libraries\Acl\Interfaces\UserRestrictionInterface;
use App\Libraries\Criterias\User;
use App\Libraries\Repository;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Input;

/**
 * Class ResourcesController
 *
 * @package App\Http\Controllers\Api
 *
 */
abstract class ResourcesController extends ApiController
{

    /**
     * @var Repository
     */
    protected $repository;

    /**
     *
     * ResourcesController constructor.
     *
     * @param \App\Libraries\Repository $repository
     */
    public function __construct(\App\Libraries\Repository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     *
     * @apiDefine getIndex
     * @apiName index
     * @apiErrorExample {json} Error-Response:
     *     HTTP/1.1 404 Not Found
     *     {}
     *
     * @apiErrorExample {json} Error-Response:
     *     HTTP/1.1 401 Not Authorized
     *     {}
     * @apiName index
     */
    public function index()
    {
        $this->addUserCriteria();

        return response()->json($this->repository->all(Input::get('paginate', false), Input::get('nbByPage', 15), Input::get('page', 1)));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     *
     * @apiDefine postStore
     * @apiName store
     * @apiErrorExample {json} Error-Response:
     *     HTTP/1.1 404 Not Found
     *     {}
     *
     * @apiErrorExample {json} Error-Response:
     *     HTTP/1.1 401 Not Authorized
     *     {}
     *
     * @apiErrorExample {json} Error-Response:
     *     HTTP/1.1 400 Bad Request
     *     {
     *            'errors' : []
     *     }
     * @apiName show
     */
    public function store()
    {
        try {
            $class = $this->repository->getModelClass();
            $model = new $class(Input::all());

            if ($model instanceof UserRestrictionInterface) {
                $model->user()->associate(\Auth::user());
            }

            $model = $this->repository->create($model);
        } catch (ModelNotValid $e) {
            return response()->json($e->getErrors(), 400);
        }

        return response()->json($model, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     *
     * @return Response
     *
     * @apiDefine get
     * @apiName show
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
     */
    public function show($id)
    {
        $this->addUserCriteria();
        $model = $this->repository->find($id);

        if (is_null($model)) {
            return response()->json([], 404);
        }

        return response()->json($model, 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int $id
     *
     * @return Response
     *
     * @apiDefine putUpdate
     * @apiName update
     * @apiErrorExample {json} Error-Response:
     *     HTTP/1.1 404 Not Found
     *     {}
     *
     * @apiErrorExample {json} Error-Response:
     *     HTTP/1.1 401 Not Authorized
     *     {}
     * @apiName put
     * @apiParam {Number} id Model unique ID.
     **/
    public function update($id)
    {
        $this->addUserCriteria();
        $model = $this->repository->find($id);

        if (is_null($model)) {
            return response()->json([], 404);
        }

        try {
            $model = $this->repository->update($model, Input::all());
        } catch (ModelNotValid $e) {
            return response()->json($e->getErrors(), 400);
        }

        return response()->json($model, 202);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     *
     * @return Response
     *
     * @apiDefine deleteDestroy
     * @apiName update
     * @apiErrorExample {json} Error-Response:
     *     HTTP/1.1 404 Not Found
     *     {}
     *
     * @apiErrorExample {json} Error-Response:
     *     HTTP/1.1 401 Not Authorized
     *     {}
     *
     * @apiName delete
     * @apiParam {Number} id Model unique ID.
     */
    public function destroy($id)
    {
        $this->addUserCriteria();
        $model = $this->repository->find($id);

        if (is_null($model)) {
            return response()->json([], 404);
        }

        $this->repository->delete($model);

        return response()->json([], 202);
    }

    /**
     * @author LAHAXE Arnaud
     *
     *  Add a criteria on repository if the user is not admin and
     *         the model is implementing UserRestrictionInterface
     *
     * @return bool
     */
    protected function addUserCriteria()
    {
        $model = $this->repository->getModel();
        if (!$model instanceof UserRestrictionInterface) {
            return true;
        }

        /** @var \App\User $user */
        $user = \Auth::user();

        // no current user and no user given, no access
        if (is_null($user)) {

            return false;
        }

        if ($user->isSuperAdmin()) {

            return true;
        }

        $this->repository->addCriteria(new User($user));
    }
}