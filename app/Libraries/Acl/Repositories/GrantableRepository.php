<?php
/**
 * Created by PhpStorm.
 * User: arnaud
 * Date: 24/08/2015
 * Time: 23:21
 */

namespace App\Libraries\Acl\Repositories;

use App\Permission;
use Libraries\Acl\Interfaces\GrantableInterface;
use Libraries\Acl\Interfaces\PermissionInterface;

abstract class GrantableRepository extends Repository
{

    /**
     * Permission constructor.
     */
    public function __construct($className)
    {
        parent::__construct($className);
    }

    /**
     * @param GrantableInterface  $model
     * @param PermissionInterface $permission
     *
     * @return GrantableRepository
     */
    public function grant(GrantableInterface $model, PermissionInterface $permission)
    {
        return $this->addPermission($model, $permission);
    }

    /**
     * @param GrantableInterface  $model
     * @param PermissionInterface $permission
     *
     * @return GrantableRepository
     */
    public function deny(GrantableInterface $model, PermissionInterface $permission)
    {
        return $this->removePermission($model, $permission);
    }

    /**
     * @param GrantableInterface $model
     * @param Permission         $permission
     *
     * @return $this
     */
    public function addPermission(GrantableInterface $model, Permission $permission)
    {
        $model->permissions()->attach($permission);

        return $this;
    }

    /**
     * @param GrantableInterface $model
     * @param Permission         $permission
     *
     * @return $this
     */
    public function removePermission(GrantableInterface $model, Permission $permission)
    {
        $model->permissions()->detach($permission);

        return $this;
    }

    /**
     * @param \Libraries\Acl\Interfaces\GrantableInterface $model
     * @param \App\Permission                              $permission
     *
     * @return mixed
     */
    public function hasPermission(GrantableInterface $model, Permission $permission)
    {
        return $model->permissions->contains($permission->id);
    }
}