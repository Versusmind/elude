<?php namespace App\Libraries\Acl\Repositories;

use App\Libraries\Repository;

class Permission extends Repository
{

    /**
     * Permission constructor.
     */
    public function __construct()
    {
        parent::__construct(\App\Permission::class);
    }
}