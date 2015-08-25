<?php namespace App\Libraries\Acl\Repositories;


use App\Role;
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
     * @param Role $role
     * @return $this
     */
    public function addRole(GrantableInterface $grantable, Role $role)
    {
        $grantable->roles()->attach($role);

        return $this;
    }

    /**
     * @param GrantableInterface $grantable
     * @param Role $role
     * @return $this
     */
    public function removeRole(GrantableInterface $grantable, Role $role)
    {
        $grantable->roles()->detach($role);

        return $this;
    }

    /**
     * @param GrantableInterface $grantable
     * @param Role $role
     * @return mixed
     */
    public function hasRole(GrantableInterface $grantable, Role $role)
    {
        return $grantable->roles->contains($role->id);
    }
}