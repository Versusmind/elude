<?php namespace App\Http\Controllers\Api;

use App\Libraries\Acl\Repositories\Permission;
use App\Libraries\Acl\Repositories\Role;

abstract class PermissionAware extends ResourcesController
{

    /**
     * @var Permission
     */
    protected $permissionRepository;

    /**
     * @param \App\Libraries\Repository $repository
     * @param Permission $permissionRepository
     */
    public function __construct(\App\Libraries\Repository $repository, Permission $permissionRepository)
    {
        $this->permissionRepository = $permissionRepository;

        parent::__construct($repository);
    }

    public function permissionStore($id, $idPermission)
    {
        $model = $this->repository->find($id);
        $permission = $this->permissionRepository->find($idPermission);

        if(is_null($model) || is_null($permission)) {
            return response()->json([], 404);
        }

        $this->repository->addPermission($model, $permission);

        return response()->json($model, 204);
    }

    public function permissionDestroy($id, $idPermission)
    {
        $model = $this->repository->find($id);
        $permission = $this->permissionRepository->find($idPermission);

        if(is_null($model) || is_null($permission)) {
            return response()->json([], 404);
        }

        $this->repository->removePermission($model, $permission);

        return response()->json($model, 204);
    }
}