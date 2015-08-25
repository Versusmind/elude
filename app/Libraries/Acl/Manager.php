<?php namespace App\Libraries\Acl;


use App\Libraries\Acl\Repositories\GrantableRepository;
use App\Libraries\Acl\Repositories\Group;
use App\Libraries\Acl\Repositories\Role;
use App\User;
use Illuminate\Support\Collection;
use Libraries\Acl\Interfaces\GrantableInterface;
use Libraries\Acl\Interfaces\GroupInterface;
use Libraries\Acl\Interfaces\PermissionInterface;
use Libraries\Acl\Interfaces\RevokableInterface;
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

    /**
     * @param GrantableInterface $grantable
     * @param PermissionInterface $permission
     * @return GrantableRepository
     */
    public function grant(GrantableInterface $grantable, PermissionInterface $permission)
    {

        return $this->getRepository($grantable)->grant($grantable, $permission);
    }

    /**
     * @param GrantableInterface $grantable
     * @param PermissionInterface $permission
     * @return GrantableRepository
     */
    public function deny(GrantableInterface $grantable, PermissionInterface $permission)
    {

        return $this->getRepository($grantable)->deny($grantable, $permission);
    }

    /**
     * @param RevokableInterface $grantable
     * @param PermissionInterface $permission
     * @return mixed
     */
    public function revoke(RevokableInterface $grantable, PermissionInterface $permission)
    {

        return $this->getRepository($grantable)->revoke($grantable, $permission);
    }

    /**
     * @param GrantableInterface $grantable
     * @return GrantableRepository
     */
    public function getRepository(GrantableInterface $grantable)
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

        return $repository;
    }
}