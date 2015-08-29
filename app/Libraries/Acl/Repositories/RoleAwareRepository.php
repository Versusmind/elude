<?php namespace App\Libraries\Acl\Repositories;


use App\Libraries\Acl\Interfaces\GrantableInterface;
use App\Libraries\Acl\Interfaces\RoleAwareInterface;

abstract class RoleAwareRepository extends GrantableRepository implements RoleAwareInterface
{

    /**
     * Permission constructor.
     */
    public function __construct($className)
    {
        parent::__construct($className);
    }

    /**
     * @param GrantableInterface $grantable
     * @param \App\Role $role
     * @return $this
     */
    public function addRole(GrantableInterface $grantable, \App\Role $role)
    {
        if($this->hasRole($grantable, $role)) {
            return $this;
        }

        $grantable->roles()->attach($role);
        $grantable->load('roles');

        return $this;
    }

    /**
     * @param GrantableInterface $grantable
     * @param \App\Role $role
     * @return $this
     */
    public function removeRole(GrantableInterface $grantable, \App\Role $role)
    {
        if(!$this->hasRole($grantable, $role)) {
            return $this;
        }

        $grantable->roles()->detach($role);
        $grantable->load('roles');

        return $this;
    }

    /**
     * @param GrantableInterface $grantable
     * @param \App\Role $role
     * @return mixed
     */
    public function hasRole(GrantableInterface $grantable, \App\Role $role)
    {
        return $grantable->roles->contains($role->id);
    }
}