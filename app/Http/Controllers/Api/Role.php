<?php namespace App\Http\Controllers\Api;


class Role extends PermissionAware
{

    /**
     * Role constructor.
     */
    public function __construct(\App\Libraries\Acl\Repositories\Role $repository, \App\Libraries\Acl\Repositories\Permission $permissionRepository)
    {
        parent::__construct($repository, $permissionRepository);
    }
}