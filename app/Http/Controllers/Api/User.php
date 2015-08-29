<?php namespace App\Http\Controllers\Api;

class User extends RoleAware
{

    protected $groupRepository;

    /**
     * User constructor.
     */
    public function __construct(\App\Libraries\Acl\Repositories\User $repository, \App\Libraries\Acl\Repositories\Role $roleRepository, \App\Libraries\Acl\Repositories\Permission $permissionRepository, \App\Libraries\Acl\Repositories\Group $groupRepository)
    {
        $this->groupRepository = $groupRepository;

        parent::__construct($repository, $permissionRepository, $roleRepository);
    }

    public function groupUpdate($id, $idGroup)
    {
        $model = $this->repository->find($id);
        $group = $this->groupRepository->find($idGroup);

        if (is_null($model) || is_null($group)) {
            return response()->json([], 404);
        }

        $this->repository->setGroup($model, $group);

        return response()->json($model, 204);
    }
}