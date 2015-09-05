<?php namespace App\Libraries\Acl\Manager;

/******************************************************************************
 *
 * @package Myo 2
 * @copyright © 2015 by Versusmind.
 * All rights reserved. No part of this document may be
 * reproduced or transmitted in any form or by any means,
 * electronic, mechanical, photocopying, recording, or
 * otherwise, without prior written permission of Versusmind.
 * @link http://www.versusmind.eu/
 *
 * @file User.php
 * @author LAHAXE Arnaud
 * @last-edited 05/09/2015
 * @description User
 *
 ******************************************************************************/

use App\Libraries\Acl\Interfaces\GrantableInterface;
use App\Libraries\Acl\Interfaces\UserRestrictionCapabilitiesInterface;
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
        if(!is_null($grantable->group)) {
            $this->resolver->setGroup($grantable->group);
        }

        $this->resolver->setRoles($grantable->roles);

        parent::initialize($grantable);
    }

    public function isAllow(GrantableInterface $grantable, $action)
    {
        if($grantable instanceof UserRestrictionCapabilitiesInterface) {
            if($grantable->isSuperAdmin()) {
                return true;
            }
        }

        return parent::isAllow($grantable, $action);
    }
}