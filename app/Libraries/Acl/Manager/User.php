<?php namespace App\Libraries\Acl\Manager;

use App\Libraries\Acl\Interfaces\GrantableInterface;
use App\Libraries\Acl\PermissionResolver;
use App\Libraries\Acl\Repositories\User as UserRepository;

class User extends Manager
{

    /**
     * @param PermissionResolver $resolver
     * @param UserRepository     $repository
     */
    public function __construct(PermissionResolver $resolver, UserRepository $repository)
    {
        $this->repository = $repository;
        parent::__construct($resolver);
    }

    /**
     * @param GrantableInterface $grantable
     */
    public function initialize(GrantableInterface $grantable)
    {
        $this->resolver->setGroup($grantable->group);
        $this->resolver->setRoles($grantable->roles);

        parent::initialize($grantable);
    }
}