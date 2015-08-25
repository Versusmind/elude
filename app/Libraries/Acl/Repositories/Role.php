<?php namespace App\Libraries\Acl\Repositories;


class Role extends RevokableRepository
{

    /**
     * Permission constructor.
     */
    public function __construct()
    {
        parent::__construct(\App\Role::class);
    }
}