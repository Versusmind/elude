<?php namespace App\Libraries\Acl\Repositories;


use App\Group;
use App\Permission;
use App\Role;


class User extends GrantableRepository
{

    /**
     * Permission constructor.
     */
    public function __construct()
    {
        parent::__construct(\App\User::class);
    }

    /**
     * @param \App\User $user
     * @param Role $role
     * @return $this
     */
    public function addRole(\App\User $user, Role $role)
    {
        $user->roles()->attach($role);

        return $this;
    }

    /**
     * @param \App\User $user
     * @param Role $role
     * @return $this
     */
    public function removeRole(\App\User $user, Role $role)
    {
        $user->roles()->detach($role);

        return $this;
    }

    /**
     * @param \App\User $user
     * @param Group $role
     * @return $this
     */
    public function setGroup(\App\User $user, Group $role)
    {
        $user->group()->save($role);

        return $this;
    }
}