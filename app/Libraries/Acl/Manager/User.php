<?php namespace App\Libraries\Acl\Manager;

use App\Group;
use App\Libraries\Acl\PermissionResolver;
use App\Libraries\Acl\Repositories\User as UserRepository;
use App\Role;
use Libraries\Acl\Interfaces\GrantableInterface;

class User extends Manager
{

    /**
     * @param \App\Libraries\Acl\PermissionResolver $resolver
     * @param \App\Libraries\Acl\Repositories\User  $repository
     */
    public function __construct(PermissionResolver $resolver, UserRepository $repository)
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
        $this->resolver->setGroup($grantable->group);
        $this->resolver->setRoles($grantable->roles);

        parent::initialize($grantable);
    }

    /**
     * @param \App\User $user
     * @param \App\Role $role
     *
     */
    public function addUserRole(\App\User $user, Role $role)
    {
        $this->repository->addRole($user, $role);
    }

    /**
     * @param \App\User $user
     * @param \App\Role $role
     *
     */
    public function removeUserRole(\App\User $user, Role $role)
    {
        $this->repository->removeRole($user, $role);
    }

    /**
     * @param \App\User  $user
     * @param \App\Group $group
     *
     */
    public function changeUserGroup(\App\User $user, Group $group)
    {
        $this->repository->setGroup($user, $group);
    }
}