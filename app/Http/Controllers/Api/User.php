<?php namespace App\Http\Controllers\Api;

class User extends ResourcesController
{

    /**
     * Group constructor.
     */
    public function __construct(\App\Libraries\Acl\Repositories\User $repository)
    {
        parent::__construct($repository);
    }
}