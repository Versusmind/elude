<?php namespace App\Http\Controllers\Api;

use App\Libraries\Acl\Repositories\Group;
use App\Libraries\Acl\Repositories\Permission;
use App\Libraries\Acl\Repositories\Role;

class User extends RoleAware
{

    protected $groupRepository;

    /**
     * User constructor.
     */
    public function __construct(\App\Libraries\Acl\Repositories\Group $repository, Role $roleRepository, Permission $permissionRepository, Group $groupRepository)
    {
        $this->groupRepository = $groupRepository;

        parent::__construct($repository, $permissionRepository, $roleRepository);
    }

    public function groupUpdate($id, $idGroup)
    {
        $model = $this->repository->find($id);
        $group = $this->groupRepository->find($idGroup);

        if(is_null($model) || is_null($group)) {
            return response()->json([], 404);
        }

        $this->repository->setGroup($model, $group);

        return response()->json([], 204);
    }
}