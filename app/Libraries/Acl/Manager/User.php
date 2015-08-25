<?php namespace App\Libraries\Acl\Manager;

use App\Group;
use App\Libraries\Acl\PermissionResolver;
use App\Libraries\Acl\Repositories\User as UserRepository;
use App\Role;
use App\Libraries\Acl\Interfaces\GrantableInterface;

class User extends Manager
{

    /**
     * @param PermissionResolver $resolver
     * @param UserRepository $repository
     */
    public function __construct(PermissionResolver $resolver, UserRepository $repository)
    {
        $this->repository = $repository;
        parent::__construct($resolver);
    }

    /**
     * @param GrantableInterface $grantable
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
    public function addRole(\App\User $user, Role $role)
    {
        $this->repository->addRole($user, $role);
    }

    /**
     * @param \App\User $user
     * @param \App\Role $role
     *
     */
    public function removeRole(\App\User $user, Role $role)
    {
        $this->repository->removeRole($user, $role);
    }

    /**
     * @param \App\User  $user
     * @param \App\Group $group
     *
     */
    public function changeGroup(\App\User $user, Group $group)
    {
        $this->repository->setGroup($user, $group);
    }
}