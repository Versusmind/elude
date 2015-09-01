<?php  namespace App\Http\Controllers\Api;

use App\Libraries\Acl\Repositories\Permission;
use App\Libraries\Acl\Repositories\Role;

class Group extends RoleAware
{
    /**
     * Group constructor.
     */
    public function __construct(\App\Libraries\Acl\Repositories\Group $repository, Role $roleRepository, Permission $permissionRepository)
    {
        parent::__construct($repository, $permissionRepository, $roleRepository);
    }

}