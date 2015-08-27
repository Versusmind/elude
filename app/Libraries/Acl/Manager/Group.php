<?php namespace App\Libraries\Acl\Manager;

use App\Libraries\Acl\Interfaces\GrantableInterface;
use App\Libraries\Acl\PermissionResolver;
use App\Libraries\Acl\Repositories\Group as GroupRepository;

class Group extends Manager
{

    /**
     * @param PermissionResolver $resolver
     * @param GroupRepository    $repository
     */
    public function __construct(PermissionResolver $resolver, GroupRepository $repository)
    {
        $this->repository = $repository;

        parent::__construct($resolver);
    }

    /**
     * @param GrantableInterface $grantable
     */
    public function initialize(GrantableInterface $grantable)
    {
        $this->resolver->setRoles($grantable->roles);

        parent::initialize($grantable);
    }
}