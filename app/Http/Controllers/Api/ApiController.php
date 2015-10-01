<?php
/**
 * User: LAHAXE Arnaud <alahaxe@boursorama.fr>
 * Date: 15/09/2015
 * Time: 13:21
 * FileName : ApiController.php
 * Project : myo2
 */

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

class ApiController extends Controller
{
    /**
     * @apiDefine ApiOAuth
     *
     * @apiHeader {String} Authorization OAuth token (Bearer: XXXXXXX).
     */

    /**
     * @apiDefine ApiNotFound
     *
     * @apiErrorExample {json} Error-Response:
     *     HTTP/1.1 404 Not Found
     *     {}
     */

    /**
     * @apiDefine NotAuthorized
     * @apiErrorExample {json} Error-Response:
     *     HTTP/1.1 401 Not Authorized
     *     {}
     */

    /**
     * @apiDefine paginationParams
     *
     * @apiSuccess {Boolean} paginate True for paginate result
     * @apiSuccess {Number} nbByPage Number of item by page.
     * @apiSuccess {Number} page The current page.
     */
}