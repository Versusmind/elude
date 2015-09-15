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
 * @file Permission.php
 * @author LAHAXE Arnaud
 * @last-edited 05/09/2015
 * @description Permission
 *
 ******************************************************************************/


class Permission extends ResourcesController
{

    /**
     * Permission constructor.
     */
    public function __construct(\App\Libraries\Acl\Repositories\Permission $repository)
    {
        parent::__construct($repository);
    }

    /**
     * @apiDefine permissionParams
     *
     * @apiParam {String} area Area.
     * @apiParam {String} permission  Permission.
     * @apiParam {String} description  Description.
     */

    /**
     * @api {get} /permissions Request permission list
     * @apiGroup Permissions
     * @apiUse getIndex
     * @apiUse paginationParams
     * @apiUse ApiOAuth
     */

    /**
     * @api {get} /permissions/:id Request permission information
     * @apiGroup Permissions
     * @apiUse get
     * @apiSuccess (200) {Number} id Id.
     * @apiSuccess (200) {datetime} created_at Creation date.
     * @apiSuccess (200) {datetime} updated_at Last Update date.
     * @apiSuccess (200) {String} area Area.
     * @apiSuccess (200) {String} permission  Permission.
     * @apiSuccess (200) {String} description  Description.
     * @apiUse ApiOAuth
     */

    /**
     * @api {post} /permissions Create a permission
     * @apiGroup Permissions
     * @apiUse postStore
     * @apiSuccess (201) {Number} id Id.
     * @apiSuccess (201) {datetime} created_at Creation date.
     * @apiSuccess (201) {datetime} updated_at Last Update date.
     * @apiSuccess (201) {String} area Area.
     * @apiSuccess (201) {String} permission  Permission.
     * @apiSuccess (201) {String} description  Description.
     * @apiUse permissionParams
     * @apiUse ApiOAuth
     */

    /**
     * @api {put} /permissions/:id Update a permission
     * @apiGroup Permissions
     * @apiUse putUpdate
     * @apiSuccess (202) {Number} id Id.
     * @apiSuccess (202) {datetime} created_at Creation date.
     * @apiSuccess (202) {datetime} updated_at Last Update date.
     * @apiSuccess (202) {String} area Area.
     * @apiSuccess (202) {String} permission  Permission.
     * @apiSuccess (202) {String} description  Description.
     * @apiParam {Number} id Id.
     * @apiUse permissionParams
     * @apiUse ApiOAuth
     */

    /**
     * @api {delete} /permissions/:id Delete a permission
     * @apiGroup Permissions
     * @apiUse deleteDestroy
     * @apiUse ApiOAuth
     * @apiParam {Number} id Id.
     */
}