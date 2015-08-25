<?php
/**
 * Created by PhpStorm.
 * User: arnaud
 * Date: 24/08/2015
 * Time: 23:21
 */

namespace App\Libraries\Acl\Repositories;


use Libraries\Acl\Interfaces\GrantableInterface;
use Libraries\Acl\Interfaces\PermissionInterface;
use Libraries\Acl\Interfaces\RevokableInterface;

abstract class RevokableRepository extends GrantableRepository
{

    /**
     * Permission constructor.
     */
    public function __construct($className)
    {
        parent::__construct($className);
    }

    /**
     * @param RevokableInterface $model
     * @param PermissionInterface $permission
     */
    public function revoke(RevokableInterface $model, PermissionInterface $permission)
    {
        throw new \RuntimeException('Not implemented yet');
    }
}