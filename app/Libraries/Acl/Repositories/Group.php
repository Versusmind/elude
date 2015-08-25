<?php namespace App\Libraries\Acl\Repositories;

class Group extends RoleAwareRepository
{

    /**
     * Permission constructor.
     */
    public function __construct()
    {
        parent::__construct(\App\Group::class);
    }
}