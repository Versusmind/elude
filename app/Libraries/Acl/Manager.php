<?php namespace App\Libraries\Acl;


use App\Libraries\Acl\Repositories\Group;
use App\Libraries\Acl\Repositories\Role;
use App\User;
use Illuminate\Support\Collection;
use Libraries\Acl\Interfaces\GrantableInterface;
use Libraries\Acl\Interfaces\GroupInterface;
use Libraries\Acl\Interfaces\PermissionInterface;
use Libraries\Acl\Interfaces\RoleInterface;
use Libraries\Acl\Interfaces\UserInterface;

class Manager
{
    /**
     * @var PermissionResolver
     */
    protected $resolver;

    /**
     * @var \App\Libraries\Acl\Repositories\User
     */
    protected $userRepository;

    /**
     * @var Role
     */
    protected $roleRepository;

    /**
     * @var Group
     */
    protected $groupRepository;

    /**
     * @var User
     */
    protected $user;

    /**
     * @var Collection
     */
    protected $permissions;

    /**
     * Manager constructor.
     * @param PermissionResolver $resolver
     */
    public function __construct(PermissionResolver $resolver)
    {
        $this->resolver = $resolver;
    }

    /**
     * @param User $user
     */
    public function initialize(User $user = null)
    {
        $this->resolver->loadFromUser($user);
        $this->permissions = $this->resolver->resolve();
    }

    /**
     * @param $action
     * @return mixed
     */
    public function isAllow($action)
    {
        return $this->permissions->get($action, false);
    }

    /**
     * @return array
     */
    public function getAllActions()
    {
        $result = $this->permissions->all();

        array_filter($result, function ($item) {
            return $item;
        });

        return array_keys($result);
    }

    public function grant(GrantableInterface $grantable, PermissionInterface $permission)
    {
        $repository = null;
        if ($grantable instanceof UserInterface) {
            $repository = $this->userRepository;
        } elseif ($grantable instanceof RoleInterface) {
            $repository = $this->roleRepository;

        } elseif ($grantable instanceof GroupInterface) {
            $repository = $this->groupRepository;

        } else {
            throw new \RuntimeException(get_class($grantable) . ' is not grantable');
        }

        return $repository->grant($grantable, $permission);
    }

    public function grantUserPermission(PermissionInterface $permission)
    {
        $this->userRepository->grant($this->user, $permission);
    }

    public function denyUserPermission(PermissionInterface $permission)
    {
        $this->userRepository->deny($this->user, $permission);
    }

    public function grantRolePermission(RoleInterface $role, PermissionInterface $permission)
    {
        $this->roleRepository->grant($role, $permission);
    }

    public function denyRolePermission(RoleInterface $role, PermissionInterface $permission)
    {
        $this->roleRepository->deny($role, $permission);
    }

    public function grantGroupPermission(GroupInterface $group, PermissionInterface $permission)
    {
        $this->groupRepository->grant($group, $permission);
    }

    public function denyGroupPermission(GroupInterface $group, PermissionInterface $permission)
    {
        $this->groupRepository->deny($group, $permission);
    }
}