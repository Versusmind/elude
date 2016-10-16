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
 * @file Role.php
 * @author LAHAXE Arnaud
 * @last-edited 05/09/2015
 * @description Role
 *
 ******************************************************************************/


class Role extends PermissionAware
{

    /**
     * Role constructor.
     */
    public function __construct(\App\Libraries\Acl\Repositories\Role $repository, \App\Libraries\Acl\Repositories\Permission $permissionRepository)
    {
        parent::__construct($repository, $permissionRepository);
    }

    /**
     * @apiDefine roleParams
     *
     * @apiParam {String} name Name.
     * @apiParam {String} filter Filer A,D,R.
     */

    /**
     * @api {get} /roles Request role list
     * @apiGroup Roles
     * @apiUse getIndex
     * @apiUse paginationParams
     * @apiUse ApiOAuth
     */

    /**
     * @api {get} /roles/:id Request role information
     * @apiGroup Roles
     * @apiUse get
     * @apiSuccess (200) {Number} id Id.
     * @apiSuccess (200) {datetime} created_at Creation date.
     * @apiSuccess (200) {datetime} updated_at Last Update date.
     * @apiSuccess (200) {String} name Name.
     * @apiSuccess (200) {String} filter Filer A,D,R.
     * @apiUse ApiOAuth
     */

    /**
     * @api {post} /roles Create a role
     * @apiGroup Roles
     * @apiUse postStore
     * @apiSuccess (201) {Number} id Id.
     * @apiSuccess (201) {datetime} created_at Creation date.
     * @apiSuccess (201) {datetime} updated_at Last Update date.
     * @apiSuccess (201) {String} name Name.
     * @apiSuccess (201) {String} filter Filer A,D,R.
     * @apiUse roleParams
     * @apiUse ApiOAuth
     */

    /**
     * @api {put} /roles/:id Update a role
     * @apiGroup Roles
     * @apiUse putUpdate
     * @apiSuccess (202) {Number} id Id.
     * @apiSuccess (202) {datetime} created_at Creation date.
     * @apiSuccess (202) {datetime} updated_at Last Update date.
     * @apiSuccess (202) {String} name Name.
     * @apiSuccess (202) {String} filter Filer A,D,R.
     * @apiParam {Number} id Id.
     * @apiUse roleParams
     * @apiUse ApiOAuth
     */

    /**
     * @api {delete} /roles/:id Delete a role
     * @apiGroup Roles
     * @apiUse deleteDestroy
     * @apiUse ApiOAuth
     * @apiParam {Number} id Id.
     */

    /**
     * @api      {delete} roles/:id/permissions/:idPermission Remove a permission from a role
     * @apiGroup Roles
     * @apiUse   deletePermission
     * @apiUse   ApiOAuth
     * @apiParam {Number} id Id.
     * @apiSuccess (202) {Number} id Id.
     * @apiSuccess (202) {datetime} created_at Creation date.
     * @apiSuccess (202) {datetime} updated_at Last Update date.
     * @apiSuccess (202) {String} name Name.
     * @apiSuccess (202) {String} filter Filer A,D,R.
     */

    /**
     * @api      {post} roles/:id/permissions/:idPermission Add a permission to a role
     * @apiGroup Roles
     * @apiUse   postPermission
     * @apiUse   ApiOAuth
     * @apiParam {Number} id Id.
     * @apiSuccess (202) {Number} id Id.
     * @apiSuccess (202) {datetime} created_at Creation date.
     * @apiSuccess (202) {datetime} updated_at Last Update date.
     * @apiSuccess (202) {String} name Name.
     * @apiSuccess (202) {String} filter Filer A,D,R.
     */
}