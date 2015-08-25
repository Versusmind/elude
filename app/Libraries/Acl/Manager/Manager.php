<?php namespace App\Libraries\Acl\Manager;

use App\Libraries\Acl\PermissionResolver;
use App\Libraries\Acl\Repositories\GrantableRepository;
use Illuminate\Support\Collection;
use Libraries\Acl\Interfaces\GrantableInterface;
use Libraries\Acl\Interfaces\PermissionInterface;

abstract class Manager
{

    /**
     * @var PermissionResolver
     */
    protected $resolver;

    /**
     * @var \App\Libraries\Acl\Repositories\GrantableRepository
     */
    protected $repository;

    /**
     * @var Collection
     */
    protected $permissions;

    /**
     * Manager constructor.
     *
     * @param PermissionResolver $resolver
     */
    public function __construct(PermissionResolver $resolver)
    {
        $this->resolver = $resolver;
    }

    /**
     * @param \Libraries\Acl\Interfaces\GrantableInterface $grantable
     *
     */
    public function initialize(GrantableInterface $grantable)
    {
        $this->resolver->setPermissions($grantable->permissions);
        $this->permissions = $this->resolver->resolve();
    }

    /**
     * @param $action
     *
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
     * @param GrantableInterface  $grantable
     * @param PermissionInterface $permission
     *
     * @return GrantableRepository
     */
    public function grant(GrantableInterface $grantable, PermissionInterface $permission)
    {

        return $this->repository->grant($grantable, $permission);
    }

    /**
     * @param GrantableInterface  $grantable
     * @param PermissionInterface $permission
     *
     * @return GrantableRepository
     */
    public function deny(GrantableInterface $grantable, PermissionInterface $permission)
    {

        return $this->repository->deny($grantable, $permission);
    }
}