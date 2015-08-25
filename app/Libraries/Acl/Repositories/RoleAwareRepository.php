<?php
/**
 * Created by PhpStorm.
 * User: arnaud
 * Date: 24/08/2015
 * Time: 23:21
 */

namespace App\Libraries\Acl\Repositories;

use App\Role;
use Libraries\Acl\Interfaces\GrantableInterface;
use Libraries\Acl\Interfaces\RoleAwareInterface;

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
     * @param \Libraries\Acl\Interfaces\GrantableInterface $grantable
     * @param \App\Role                                    $role
     *
     * @return $this
     */
    public function addRole(GrantableInterface $grantable, Role $role)
    {
        $grantable->roles()->attach($role);

        return $this;
    }

    /**
     * @param \Libraries\Acl\Interfaces\GrantableInterface $grantable
     * @param \App\Role                                    $role
     *
     * @return $this
     */
    public function removeRole(GrantableInterface $grantable, Role $role)
    {
        $grantable->roles()->detach($role);

        return $this;
    }

    /**
     * @param \Libraries\Acl\Interfaces\GrantableInterface $grantable
     * @param \App\Role                                    $role
     *
     * @return mixed
     */
    public function hasRole(GrantableInterface $grantable, Role $role)
    {
        return $grantable->roles->contains($role->id);
    }
}