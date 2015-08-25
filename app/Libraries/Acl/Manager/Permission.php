<?php namespace App\Libraries\Acl\Manager;

use App\Libraries\Acl\PermissionResolver;
use App\Libraries\Acl\Repositories\Permission as PermissionRepository;
use Illuminate\Support\Collection;
use Libraries\Acl\Interfaces\GrantableInterface;

class Permission extends Manager
{

    /**
     * @param \App\Libraries\Acl\PermissionResolver      $resolver
     * @param \App\Libraries\Acl\Repositories\Permission $repository
     */
    public function __construct(PermissionResolver $resolver, PermissionRepository $repository)
    {
        $this->repository = $repository;
        parent::__construct($resolver);
    }

    /**
     * @param \Libraries\Acl\Interfaces\GrantableInterface $grantable
     *
     */
    public function initialize(GrantableInterface $grantable)
    {
        $this->permissions = new Collection();
    }
}