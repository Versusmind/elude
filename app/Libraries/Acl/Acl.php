<?php namespace App\Libraries\Acl;

use App\Libraries\Acl\Manager\Group;
use App\Libraries\Acl\Manager\Role;
use App\Libraries\Acl\Manager\User;
use App\Libraries\Acl\Interfaces\GroupInterface;
use App\Libraries\Acl\Interfaces\PermissionInterface;
use App\Libraries\Acl\Interfaces\RoleInterface;
use App\Libraries\Acl\Interfaces\UserInterface;

class Acl
{

    /**
     * @var Group
     */
    protected $groupManager;

    /**
     * @var Role
     */
    protected $roleManager;

    /**
     * @var User
     */
    protected $userManager;

    /**
     * Acl constructor.
     *
     * @param \App\Libraries\Acl\Manager\Group $groupManager
     * @param \App\Libraries\Acl\Manager\Role  $roleManager
     * @param \App\Libraries\Acl\Manager\User  $userManager
     */
    public function __construct(\App\Libraries\Acl\Manager\Group $groupManager, \App\Libraries\Acl\Manager\Role $roleManager, \App\Libraries\Acl\Manager\User $userManager)
    {
        $this->groupManager = $groupManager;
        $this->roleManager  = $roleManager;
        $this->userManager  = $userManager;
    }

    /**
     * @param \App\Libraries\Acl\Interfaces\UserInterface       $userInterface
     * @param \App\Libraries\Acl\Interfaces\PermissionInterface $permissionInterface
     *
     */
    public function grantUserPermission(UserInterface $userInterface, PermissionInterface $permissionInterface)
    {
        $this->userManager->grant($userInterface, $permissionInterface);
    }

    /**
     * @param \App\Libraries\Acl\Interfaces\UserInterface       $userInterface
     * @param \App\Libraries\Acl\Interfaces\PermissionInterface $permissionInterface
     *
     */
    public function denyUserPermission(UserInterface $userInterface, PermissionInterface $permissionInterface)
    {
        $this->userManager->deny($userInterface, $permissionInterface);
    }

    /**
     * @param \App\Libraries\Acl\Interfaces\RoleInterface       $roleInterface
     * @param \App\Libraries\Acl\Interfaces\PermissionInterface $permissionInterface
     *
     */
    public function grantRolePermission(RoleInterface $roleInterface, PermissionInterface $permissionInterface)
    {
        $this->roleManager->grant($roleInterface, $permissionInterface);
    }

    /**
     * @param \App\Libraries\Acl\Interfaces\RoleInterface       $roleInterface
     * @param \App\Libraries\Acl\Interfaces\PermissionInterface $permissionInterface
     *
     */
    public function denyRolePermission(RoleInterface $roleInterface, PermissionInterface $permissionInterface)
    {
        $this->roleManager->deny($roleInterface, $permissionInterface);
    }

    /**
     * @param \App\Libraries\Acl\Interfaces\GroupInterface      $groupInterface
     * @param \App\Libraries\Acl\Interfaces\PermissionInterface $permissionInterface
     *
     */
    public function grantGroupPermission(GroupInterface $groupInterface, PermissionInterface $permissionInterface)
    {
        $this->groupManager->grant($groupInterface, $permissionInterface);
    }

    /**
     * @param \App\Libraries\Acl\Interfaces\GroupInterface      $groupInterface
     * @param \App\Libraries\Acl\Interfaces\PermissionInterface $permissionInterface
     *
     */
    public function denyGroupPermission(GroupInterface $groupInterface, PermissionInterface $permissionInterface)
    {
        $this->groupManager->deny($groupInterface, $permissionInterface);
    }

    /**
     * @param \App\Libraries\Acl\Interfaces\UserInterface $userInterface
     * @param \App\Libraries\Acl\Interfaces\RoleInterface $roleInterface
     *
     */
    public function grantUserRole(UserInterface $userInterface, RoleInterface $roleInterface)
    {
        $this->userManager->getRepository()->addRole($userInterface, $roleInterface);
    }

    /**

     * @param \App\Libraries\Acl\Interfaces\UserInterface $userInterface
     * @param \App\Libraries\Acl\Interfaces\RoleInterface $roleInterface
     *
     */
    public function denyUserRole(UserInterface $userInterface, RoleInterface $roleInterface)
    {
        $this->userManager->getRepository()->removeRole($userInterface, $roleInterface);
    }

    /**
     * @param \App\Libraries\Acl\Interfaces\GroupInterface $groupInterface
     * @param \App\Libraries\Acl\Interfaces\RoleInterface  $roleInterface
     *
     */
    public function grantGroupRole(GroupInterface $groupInterface, RoleInterface $roleInterface)
    {
        $this->groupManager->getRepository()->addRole($groupInterface, $roleInterface);
    }

    /**
     * @param \App\Libraries\Acl\Interfaces\GroupInterface $groupInterface
     * @param \App\Libraries\Acl\Interfaces\RoleInterface  $roleInterface
     *
     */
    public function denyGroupRole(GroupInterface $groupInterface, RoleInterface $roleInterface)
    {
        $this->groupManager->getRepository()->removeRole($groupInterface, $roleInterface);
    }

    /**
     * @param \App\Libraries\Acl\Interfaces\GroupInterface $groupInterface
     * @param \App\Libraries\Acl\Interfaces\RoleInterface  $roleInterface
     *
     */
    public function grantUserGroup(UserInterface $userInterface, GroupInterface $groupInterface)
    {
        $this->userManager->getRepository()->setGroup($userInterface, $groupInterface);
    }

    /**
     * @param \App\Libraries\Acl\Interfaces\UserInterface $userInterface
     * @param                                             $action
     *
     * @return mixed
     */
    public function isUserAllow(UserInterface $userInterface, $action)
    {
        return $this->userManager->isAllow($userInterface, $action);
    }

    /**
     * @param \App\Libraries\Acl\Interfaces\UserInterface $userInterface
     *
     * @return array
     */
    public function allUserPermissions(UserInterface $userInterface)
    {
        return $this->userManager->getAllActions($userInterface);
    }
}