<?php namespace App\Libraries\Acl\Repositories;

use App\Group;

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
     * @param Group     $role
     *
     * @return $this
     */
    public function setGroup(\App\User $user, Group $role)
    {
        $user->group()->save($role);

        return $this;
    }
}