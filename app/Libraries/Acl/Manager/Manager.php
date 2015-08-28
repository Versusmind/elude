<?php namespace App\Libraries\Acl\Manager;

use App\Libraries\Acl\Interfaces\GrantableInterface;
use App\Libraries\Acl\Interfaces\PermissionInterface;
use App\Libraries\Acl\PermissionResolver;
use App\Libraries\Acl\Repositories\GrantableRepository;
use Illuminate\Support\Collection;

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
     * @param GrantableInterface $grantable
     */
    public function initialize(GrantableInterface $grantable)
    {
        $this->resolver->setPermissions($grantable->permissions);
        $this->permissions = $this->resolver->resolve();
    }

    /**
     * @param GrantableInterface $grantable
     * @param                    $action
     *
     * @return mixed
     */
    public function isAllow(GrantableInterface $grantable, $action)
    {
        if (is_null($this->permissions)) {
            $this->initialize($grantable);
        }

        return $this->permissions->get($action, false);
    }

    /**
     * @param GrantableInterface $grantable
     *
     * @return array
     */
    public function getAllPermissions(GrantableInterface $grantable)
    {
        if (is_null($this->permissions)) {
            $this->initialize($grantable);
        }

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
        $this->permissions = null;
        return $this->repository->addPermission($grantable, $permission);
    }

    /**
     * @param GrantableInterface  $grantable
     * @param PermissionInterface $permission
     *
     * @return GrantableRepository
     */
    public function deny(GrantableInterface $grantable, PermissionInterface $permission)
    {
        $this->permissions = null;
        return $this->repository->removePermission($grantable, $permission);
    }

    /**
     * @return GrantableRepository
     */
    public function getRepository()
    {
        return $this->repository;
    }
}