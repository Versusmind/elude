<?php namespace App\Libraries\Acl\Manager;

use App\Libraries\Acl\PermissionResolver;
use App\Libraries\Acl\Repositories\Group as GroupRepository;
use App\Role;
use Libraries\Acl\Interfaces\GrantableInterface;

class Group extends Manager
{

    /**
     * @param \App\Libraries\Acl\PermissionResolver $resolver
     * @param \App\Libraries\Acl\Repositories\Group $repository
     */
    public function __construct(PermissionResolver $resolver, GroupRepository $repository)
    {
        $this->repository = $repository;

        parent::__construct($resolver);
    }

    /**
     * @param \Libraries\Acl\Interfaces\GrantableInterface $grantable
     *
     */
    public function initialize(GrantableInterface $grantable)
    {
        $this->resolver->setRoles($grantable->roles);

        parent::initialize($grantable);
    }

    /**
     * @param \App\Group $group
     * @param \App\Role  $role
     */
    public function addRole(\App\Group $group, Role $role)
    {
        $this->repository->addRole($group, $role);
    }

    /**
     * @param \App\Group $group
     * @param \App\Role  $role
     */
    public function removeRole(\App\Group $group, Role $role)
    {
        $this->repository->removeRole($group, $role);
    }
}