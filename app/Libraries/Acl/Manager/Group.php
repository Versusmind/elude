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
 * @file Group.php
 * @author LAHAXE Arnaud
 * @last-edited 05/09/2015
 * @description Group
 *
 ******************************************************************************/

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