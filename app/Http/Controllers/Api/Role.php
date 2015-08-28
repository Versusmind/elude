<?php namespace App\Http\Controllers\Api;

class Role extends ResourcesController
{

    /**
     * Group constructor.
     */
    public function __construct(\App\Libraries\Acl\Repositories\Role $repository)
    {
        parent::__construct($repository);
    }
}