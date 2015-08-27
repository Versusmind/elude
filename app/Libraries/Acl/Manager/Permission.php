<?php namespace App\Libraries\Acl\Manager;

use App\Libraries\Acl\Interfaces\GrantableInterface;
use App\Libraries\Acl\PermissionResolver;
use App\Libraries\Acl\Repositories\Permission as PermissionRepository;
use Illuminate\Support\Collection;

class Permission extends Manager
{

    /**
     * @param PermissionResolver   $resolver
     * @param PermissionRepository $repository
     */
    public function __construct(PermissionResolver $resolver, PermissionRepository $repository)
    {
        $this->repository = $repository;
        parent::__construct($resolver);
    }

    /**
     * @param GrantableInterface $grantable
     */
    public function initialize(GrantableInterface $grantable)
    {
        $this->permissions = new Collection();
    }
}