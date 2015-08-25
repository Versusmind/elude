<?php namespace App\Libraries\Acl\Manager;

use App\Libraries\Acl\PermissionResolver;
use App\Libraries\Acl\Repositories\RevokableRepository;
use App\Libraries\Acl\Repositories\Role as RoleRepository;
use Libraries\Acl\Interfaces\PermissionInterface;
use Libraries\Acl\Interfaces\RevokableInterface;

class Role extends Manager
{

    /**
     * @param \App\Libraries\Acl\PermissionResolver $resolver
     * @param \App\Libraries\Acl\Repositories\Role  $repository
     */
    public function __construct(PermissionResolver $resolver, RoleRepository $repository)
    {
        $this->repository = $repository;
        parent::__construct($resolver);
    }

    /**
     * @param RevokableInterface  $revokable
     * @param PermissionInterface $permission
     *
     * @return RevokableRepository
     */
    public function revoke(RevokableInterface $revokable, PermissionInterface $permission)
    {

        return $this->repository->revoke($revokable, $permission);
    }
}