<?php
/**
 * Date: 28/08/2015
 * Time: 12:31
 * FileName : Group.php
 * Project : myo2
 */

namespace App\Http\Controllers\Api;

class Group extends ResourcesController
{

    /**
     * Group constructor.
     */
    public function __construct(\App\Libraries\Acl\Repositories\Group $repository)
    {
        parent::__construct($repository);
    }
}