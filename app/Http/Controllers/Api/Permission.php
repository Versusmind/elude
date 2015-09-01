<?php namespace App\Http\Controllers\Api;

class Permission extends ResourcesController
{

    /**
     * Permission constructor.
     */
    public function __construct(\App\Libraries\Acl\Repositories\Permission $repository)
    {
        parent::__construct($repository);
    }
}