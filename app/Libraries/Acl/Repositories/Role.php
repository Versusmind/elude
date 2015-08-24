<?php namespace App\Libraries\Acl\Repositories;


class Role extends GrantableRepository
{

    /**
     * Permission constructor.
     */
    public function __construct()
    {
        parent::__construct(\App\Role::class);
    }
}