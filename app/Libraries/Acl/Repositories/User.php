<?php namespace App\Libraries\Acl\Repositories;

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


class User extends RoleAwareRepository
{

    /**
     * Permission constructor.
     */
    public function __construct()
    {
        parent::__construct(\App\User::class);
    }

    /**
     * @param \App\User $user
     * @param \App\Group $group
     * @return $this
     */
    public function setGroup(\App\User $user, \App\Group $group)
    {
        $user->group()->associate($group);
        $user->load('group');

        return $this;
    }

    public function exists(array $conditions)
    {
        return $this->makeQuery()->where($conditions)->count() > 0;
    }

    public function getByEmail($email)
    {
        return $this->makeQuery()->where(['email' => $email])->first();
    }
}