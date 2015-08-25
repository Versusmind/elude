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
     * @param \App\Group     $role
     *
     * @return $this
     */
    public function setGroup(\App\User $user, \App\Group $role)
    {
        $user->group()->save($role);

        return $this;
    }
}