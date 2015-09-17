<?php  namespace App\Http\Controllers\Api;

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
 * @file Group.php
 * @author LAHAXE Arnaud
 * @last-edited 05/09/2015
 * @description Group
 *
 ******************************************************************************/

use App\Libraries\Acl\Repositories\Permission;
use App\Libraries\Acl\Repositories\Role;

class Group extends RoleAware
{
    /**
     * Group constructor.
     */
    public function __construct(\App\Libraries\Acl\Repositories\Group $repository, Role $roleRepository, Permission $permissionRepository)
    {
        parent::__construct($repository, $permissionRepository, $roleRepository);
    }


    /**
     * @apiDefine groupParams
     *
     * @apiParam {String} area Area.
     * @apiParam {String} group Group.
     * @apiParam {String} description Description.
     */

    /**
     * @api {get} /groups Request group list
     * @apiGroup Groups
     * @apiUse getIndex
     * @apiUse paginationParams
     * @apiUse ApiOAuth
     */

    /**
     * @api {get} /groups/:id Request group information
     * @apiGroup Groups
     * @apiUse get
     * @apiSuccess (200) {Number} id Id.
     * @apiSuccess (200) {datetime} created_at Creation date.
     * @apiSuccess (200) {datetime} updated_at Last Update date.
     * @apiSuccess (200) {String} name Name.
     * @apiUse ApiOAuth
     */

    /**
     * @api {post} /groups Create a group
     * @apiGroup Groups
     * @apiUse postStore
     * @apiSuccess (201) {Number} id Id.
     * @apiSuccess (201) {datetime} created_at Creation date.
     * @apiSuccess (201) {datetime} updated_at Last Update date.
     * @apiSuccess (201) {String} name Name.
     * @apiUse groupParams
     * @apiUse ApiOAuth
     */

    /**
     * @api {put} /groups/:id Update a group
     * @apiGroup Groups
     * @apiUse putUpdate
     * @apiSuccess (202) {Number} id Id.
     * @apiSuccess (202) {datetime} created_at Creation date.
     * @apiSuccess (202) {datetime} updated_at Last Update date.
     * @apiSuccess (202) {String} name Name.
     * @apiParam {Number} id Id.
     * @apiUse groupParams
     * @apiUse ApiOAuth
     */

    /**
     * @api {delete} /groups/:id Delete a group
     * @apiGroup Groups
     * @apiUse deleteDestroy
     * @apiUse ApiOAuth
     * @apiParam {Number} id Id.
     */

    /**
     * @api      {delete} groups/:id/permissions/:idPermission Remove a permission from a group
     * @apiGroup Groups
     * @apiUse   deletePermission
     * @apiUse   ApiOAuth
     * @apiParam {Number} id Id.
     * @apiSuccess (202) {Number} id Id.
     * @apiSuccess (202) {datetime} created_at Creation date.
     * @apiSuccess (202) {datetime} updated_at Last Update date.
     * @apiSuccess (202) {String} name Name.
     */

    /**
     * @api      {post} groups/:id/permissions/:idPermission Add a permission to a group
     * @apiGroup Groups
     * @apiUse   postPermission
     * @apiUse   ApiOAuth
     * @apiParam {Number} id Id.
     * @apiSuccess (202) {Number} id Id.
     * @apiSuccess (202) {datetime} created_at Creation date.
     * @apiSuccess (202) {datetime} updated_at Last Update date.
     * @apiSuccess (202) {String} name Name.
     */

    /**
     * @api      {delete} groups/:id/roles/:idRole Remove a role from a group
     * @apiGroup Groups
     * @apiUse   deleteRole
     * @apiUse   ApiOAuth
     * @apiParam {Number} id Id.
     * @apiSuccess (202) {Number} id Id.
     * @apiSuccess (202) {datetime} created_at Creation date.
     * @apiSuccess (202) {datetime} updated_at Last Update date.
     * @apiSuccess (202) {String} name Name.
     */

    /**
     * @api      {post} groups/:id/roles/:idRole Add a role to a group
     * @apiGroup Groups
     * @apiUse   postRole
     * @apiUse   ApiOAuth
     * @apiParam {Number} id Id.
     * @apiSuccess (202) {Number} id Id.
     * @apiSuccess (202) {datetime} created_at Creation date.
     * @apiSuccess (202) {datetime} updated_at Last Update date.
     * @apiSuccess (202) {String} name Name.
     */
}