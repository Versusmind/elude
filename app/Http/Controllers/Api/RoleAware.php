<?php
/**
 * Date: 28/08/2015
 * Time: 12:31
 * FileName : Group.php
 * Project : myo2
 */

namespace App\Http\Controllers\Api;

use App\Libraries\Acl\Repositories\Permission;
use App\Libraries\Acl\Repositories\Role;

abstract class RoleAware extends PermissionAware
{
    /**
     * @var Role
     */
    protected $roleRepository;

    /**
     * @param \App\Libraries\Repository $repository
     * @param Permission $permissionRepository
     */
    public function __construct(\App\Libraries\Repository $repository, Permission $permissionRepository, Role $roleRepository)
    {
        $this->roleRepository = $roleRepository;

        parent::__construct($repository, $permissionRepository);
    }

    public function roleStore($id, $idRole)
    {
        $model = $this->repository->find($id);
        $role = $this->roleRepository->find($idRole);

        if(is_null($model) || is_null($role)) {
            return response()->json([], 404);
        }

        $this->repository->addRole($model, $role);

        return response()->json($model, 204);
    }

    public function roleDestroy($id, $idRole)
    {
        $model = $this->repository->find($id);
        $role = $this->roleRepository->find($idRole);

        if(is_null($model) || is_null($role)) {
            return response()->json([], 404);
        }

        $this->repository->removeRole($model, $role);

        return response()->json($model, 204);
    }
}