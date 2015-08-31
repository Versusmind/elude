<?php namespace App\Libraries\Acl\Interfaces;

use App\User;

interface UserRestrictionInterface
{

    /**
     * Test if the user given can edit the model
     *
     * @param \App\User $user
     * @param array     $parameters
     *
     * @return mixed
     */
    public function isUserAllow(User $user, array $parameters = []);
}