<?php namespace App\Libraries\Acl\Repositories;


class User extends RoleAwareRepository
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
     * @param \App\Group $group
     * @return $this
     */
    public function setGroup(\App\User $user, \App\Group $group)
    {
        $user->group()->associate($group);
        $user->load('group');

        return $this;
    }
}