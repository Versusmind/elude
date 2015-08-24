<?php namespace App\Libraries\Acl\Repositories;


class Group extends GrantableRepository
{
    /**
     * Permission constructor.
     */
    public function __construct()
    {
        parent::__construct(\App\User::class);
    }

    /**
     * @param \App\Group $group
     * @param Role $role
     */
    public function addRole(\App\Group $group, Role $role)
    {
        $group->roles()->attach($role);
    }

    /**
     * @param \App\Group $group
     * @param Role $role
     * @return $this
     */
    public function removeRole(\App\Group $group, Role $role)
    {
        $group->roles()->detach($role);

        return $this;
    }
}