<?php namespace App\Libraries\Acl\Manager;

use App\Libraries\Acl\PermissionResolver;
use App\Libraries\Acl\Repositories\RevokableRepository;
use App\Libraries\Acl\Repositories\Role as RoleRepository;
use App\Libraries\Acl\Interfaces\PermissionInterface;
use App\Libraries\Acl\Interfaces\RevokableInterface;

class Role extends Manager
{

    /**
     * @param PermissionResolver $resolver
     * @param RoleRepository $repository
     */
    public function __construct(PermissionResolver $resolver, RoleRepository $repository)
    {
        $this->repository = $repository;
        parent::__construct($resolver);
    }

    /**
     * @param RevokableInterface $revokable
     * @param PermissionInterface $permission
     */
    public function revoke(RevokableInterface $revokable, PermissionInterface $permission)
    {

        return $this->repository->revoke($revokable, $permission);
    }
}