<?php namespace App\Libraries\Acl\Manager;

use App\Libraries\Acl\PermissionResolver;
use App\Libraries\Acl\Repositories\Role as RoleRepository;

class Role extends Manager
{

    /**
     * @param PermissionResolver $resolver
     * @param RoleRepository     $repository
     */
    public function __construct(PermissionResolver $resolver, RoleRepository $repository)
    {
        $this->repository = $repository;
        parent::__construct($resolver);
    }
}