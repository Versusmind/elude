<?php namespace Libraries\Acl\Interfaces;

use App\Role;

/**
 * Interface RoleInterface
 *
 * @package Libraries\Acl\Interfaces
 */
interface RoleAwareInterface
{

    public function hasRole(GrantableInterface $grantable, Role $role);

    public function addRole(GrantableInterface $grantable, Role $role);

    public function removeRole(GrantableInterface $grantable, Role $role);

}
